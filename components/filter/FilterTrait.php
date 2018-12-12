<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.10.2018
 * Time: 13:57
 */

namespace app\components\filter;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Trait FilterTrait
 * @package app\models\filter
 * @mixin ActiveRecord
 */
trait FilterTrait
{

    /**
     * @var ActiveQuery
     */
    private $_query;


    /**
     * @return array
     */
    public function attributesInRules()
    {
        $result = [];
        foreach ($this->rules() as $rule) {
            if (is_array($rule[0])) {
                foreach ($rule[0] as $attribute) {
                    $result[] = $attribute;
                }
            } else {
                $result[] = $rule[0];
            }
        }
        return $result;
    }


    /**
     * @return array
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        if ($this->attributesAdd()) {
            $attributes = array_merge($attributes, $this->attributesAdd());
        }
        return $attributes;
    }

    /**
     * @return array
     */
    public function attributesAdd()
    {
        $result = [];
        foreach ($this->attributesInRules() as $attribute) {
            if (!in_array($attribute, parent::attributes())) {
                $result[] = $attribute;
            }
        }
        return $result;
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function sortValidate($attribute)
    {
        $value = mb_strtolower($this->getAttribute($attribute));
        $this->setAttribute($attribute, $value == 'desc' ? SORT_DESC : SORT_ASC);
        return true;
    }

    /**
     * @return ActiveQuery
     */
    public function getQuery()
    {
        if (!$this->_query) {
            $this->_query = static::find();
        }
        return $this->_query;
    }

    /**
     * @param ActiveQuery $query
     */
    public function setQuery(ActiveQuery $query)
    {
        $this->_query = $query;
    }

    /**
     * @param string $attribute
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        $getter = 'get' . $attribute;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else if (property_exists(static::className(), $attribute)) {
            return $this->$attribute;
        } else {
            // $attribute = explode('|',$attribute)[0];
        }

        return parent::getAttribute($attribute);
    }

    public function getFieldNameInQuery($attribute)
    {
        if (!strstr($attribute, '.')) {
            $attribute = static::tableName() . '.' . $attribute;
        }
        return $attribute;
    }

    public function setJoinByAttributeName($attribute)
    {
        $arr = explode('.', $attribute);
        if (count($arr) == 2) {
            $joinName = $arr[0];
            $setterJoin = $joinName . 'SetJoin';
            if (method_exists(static::className(), $setterJoin)) {
                $this->$setterJoin();
            } else if ($this->getRelation($joinName, false)) {
                $this->getQuery()->joinWith($joinName . ' AS ' . $joinName);
            }
        }
    }

    /**
     * @return array
     */
    private function _rulesFilter()
    {
        $result = $this->rulesFilter();
        foreach ($this->attributesInRules() as $attribute) {
            if (strstr($attribute, '|')) {
                $result[] = $attribute;
            }
        }
        return $result;
    }

    public function runFilterMethods()
    {
        foreach ($this->_rulesFilter() as $attribute) {
            $filterRules = $this->rulesFilterByAttribute($attribute);
            $value = in_array($attribute, $this->attributes()) ? $this->getAttribute($attribute) : $this->getAttribute($filterRules['attributeName']);

            if ($filterRules['filters'] && $value) {
                foreach ($filterRules['filters'] as $v) {
                    $methodName = 'filterQuery' . $v['name'];

                    $this->setJoinByAttributeName($filterRules['attributeName']);
                    if (!method_exists(static::className(), $methodName)) {
                        $v['argument'] = $v['name'];
                        $methodName = 'filterQueryAndWhere';
                    }
                    $this->$methodName($filterRules['attributeName'], $value, $v['argument']);
                }
            }
        }
    }

    /**
     * @param $attribute
     * @return array
     */
    public function rulesFilterByAttribute($attribute)
    {
        $arr = explode('|', $attribute);
        $result = [
            'attributeName' => $arr[0],
            'filters' => [],
        ];
        if (count($arr) > 1) {
            for ($i = 1; $i < count($arr); $i++) {
                $rule = $arr[$i];
                $ruleExp = explode(':', $rule);
                $result['filters'][] = [
                    'name' => $ruleExp[0],
                    'argument' => isset($ruleExp[1]) ? $ruleExp[1] : null
                ];
            }
        }
        return $result;
    }


    /**
     * @param $attribute
     * @param $value
     * @param $type
     */
    public function filterQueryAndOrWhere($attribute, $value, $type)
    {
        $sign = explode(',', $type);
        $this->getQuery()->andFilterWhere(['or', [$sign[0], $this->getFieldNameInQuery($attribute), $value], [$sign[1], $this->getFieldNameInQuery($attribute), $value]]);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $type
     */
    public function filterQueryAndWhere($attribute, $value, $type)
    {
        $this->getQuery()->andFilterWhere([$type, $this->getFieldNameInQuery($attribute), $value]);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $type
     */
    public function filterQueryOrWhere($attribute, $value, $type)
    {
        $this->getQuery()->orFilterWhere([$type, $this->getFieldNameInQuery($attribute), $value]);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $fields
     */
    public function filterQuerySearch($attribute, $value, $fields)
    {
        $fields = explode(',', $fields);
        $where = ['or'];
        foreach ($fields as $attribute => $fieldName) {
            $fieldExplode = explode('@', $fieldName);
            $fieldName = $fieldExplode[0];
            $sign = isset($fieldExplode[1]) ? $fieldExplode[1] : 'LIKE';
            $methodName = $attribute . 'filterQuerySearch';
            $this->setJoinByAttributeName($fieldName);
            if (method_exists($this, $methodName)) {
                $where[] = $this->$methodName();
            } else {
                $where[] = [$sign, $this->getFieldNameInQuery($fieldName), $value];
            }
        }
        $this->getQuery()->andWhere($where);
    }

    public function filterQuerySort($attribute, $value)
    {
        $this->getQuery()->addOrderBy([$attribute => $value]);
    }


    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function _search($params = [])
    {
        if (!$this->load($params, '')) {
            return $this->getQuery();
        }

        if ($this->validate()) {
            $this->runFilterMethods();
        }

        return $this->getQuery();

    }

    /**
     * @param array $params
     * @param ActiveQuery|null $query
     * @return ActiveQuery
     */
    public static function search($params = [], ActiveQuery $query = null)
    {
        $instance = new static(['query' => $query]);
        return $instance->_search($params);
    }

}