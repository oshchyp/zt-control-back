<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 12:35
 */

namespace app\models\filter;


use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class Filter
 * @package app\models\filter
 */
class Filter extends Model
{

    /**
     * @var ActiveQuery
     */
    private $query;

    public function rules()
    {
        $rules = [];
        $allAttributes = [];
        if ($this->fieldsForSearchAll()) {
            foreach ($this->fieldsForSearchAll() as $key => $value) {
                $attr = is_numeric($key) ? $value : $key;
                $rules[] = [$attr, 'string'];
                $allAttributes[] = $attr;
            }
        }
        if ($this->fieldsForSearchIndividual()) {
            foreach ($this->fieldsForSearchIndividual() as $key => $value) {
                $attr = is_numeric($key) ? $value : $key;
                $rules[] = [$attr, 'string'];
                $allAttributes[] = $attr;
            }
        }
        if ($this->fieldsForEquating()) {
            foreach ($this->fieldsForEquating() as $key => $value) {
                $attr = is_numeric($key) ? $value : $key;
                $rules[] = [$attr, 'string'];
                $allAttributes[] = $attr;
            }
        }

        if ($this->fieldsComparisonMore()) {
            foreach ($this->fieldsComparisonMore() as $key => $value) {
                $attr = is_numeric($key) ? $value : $key;
                $rules[] = [$attr, 'integer'];
                $allAttributes[] = $attr;
            }
        }


        return $rules ? $rules : parent::rules();
    }


    /**
     *
     */
    public function trimAllAttributes()
    {
        foreach ($this as $attribute => $value) {
            if (is_string($value)) {
                $this->$attribute = trim($value);
            }
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param ActiveQuery $query
     * @return $this
     */
    public function setQuery(ActiveQuery $query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return $this
     */
    public function filter()
    {
        $this->joins();
        $this->trimAllAttributes();
        $methods = get_class_methods($this);
        foreach ($methods as $v) {
            if (substr($v, 0, 6) == 'filter' && $v !== 'filter') {
                $this->$v();
            }
        }
        $this->sortBy();
        return $this;
    }

    /**
     * @return $this
     */
    public function sortBy()
    {
        if ($this->getSortField() && $this->getSortValue()) {
            $this->getQuery()->orderBy([$this->getSortField() => $this->getSortValue()]);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function filterSearchAll()
    {
        $searchString = $this->stringForSearchAll();
        $fields = $this->getFilterFields($this->fieldsForSearchAll());
        if ($searchString && $fields) {
            $where = ['or'];
            foreach ($fields as $attribute => $fieldName) {
                $methodName = $attribute . 'SearchAll';
                if (method_exists($this, $methodName)) {
                    $where[] = $this->$methodName();
                } else {
                    $where[] = ['LIKE', $fieldName, $searchString];
                }
            }
            $this->getQuery()->andWhere($where);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function filterSearchIndividual()
    {
        if ($fields = $this->getFilterFields($this->fieldsForSearchIndividual())) {
            foreach ($fields as $attribute => $fieldName) {
                if ($this->$attribute) {
                    $methodName = $attribute . 'SearchIndividual';
                    //   dump($methodName);
                    if (method_exists($this, $methodName)) {
                        $this->$methodName();
                    } else {
                        $this->getQuery()->andWhere(['LIKE', $fieldName, $this->$attribute]);
                    }
                }
            }
        }
        return $this;
    }

    public function filterComparisonMore()
    {
        if ($fields = $this->getFilterFields($this->fieldsComparisonMore())) {
            foreach ($fields as $attribute => $fieldName) {
                if ($this->$attribute !== null) {
                    $this->getQuery()->andWhere(['or',['>', $fieldName, (float)$this->$attribute],[$fieldName=>$this->$attribute]]);
                 //   $this->getQuery()->andWhere(['OR',['AND',['<',$fieldName, (float)$this->$attribute]],['OR',[$fieldName=>$this->$attribute]]]);

                }
            }
           // dump($this->getQuery()->createCommand()->getRawSql(),1);
        }
    }

    /**
     * @return $this
     */
    public function filterEquating()
    {
        if ($fields = $this->getFilterFields($this->fieldsForEquating())) {
            foreach ($fields as $attribute => $fieldName) {
                if (method_exists($this, $attribute . 'Equating')) {
                    $methodName = $attribute . 'Equating';
                    $this->$methodName();
                } else {
                    $this->getQuery()->andWhere([$fieldName => $this->$attribute]);
                }
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function joins()
    {
        $rules = $this->joinRules();
        foreach ($rules as $v) {
            $rule = is_string($v) ? [$v, $v] : $v;
            $attribute = $rule[0];
            if ($this->$attribute || ($this->stringForSearchAll() && $this->existingFields($attribute, 'fieldsForSearchAll'))) {
                $this->getQuery()->joinWith($rule[1] . ' AS ' . $rule[0]);
            }
        }
    }

    /**
     * @param array $rules
     * @return array
     */
    protected function getFilterFields($rules = [])
    {
        $result = [];
        if ($rules) {
            foreach ($rules as $attribute => $fieldName) {
                if (is_numeric($attribute)) {
                    $attribute = $fieldName;
                }
                $result[$attribute] = $fieldName;
            }
        }
        return $result;
    }

    protected function existingFields($attribute, $method)
    {
        return key_exists($attribute, $this->getFilterFields($this->$method()));
    }

    /**
     * @param array $config
     * @param ActiveQuery|null $query
     * @return Filter
     */
    public static function getInstance(array $config = [], ActiveQuery $query = null)
    {
        $instance = new static($config);
        if ($query) {
            $instance->setQuery($query);
        }
        return $instance;
    }

    /**
     * @param array $config
     * @param ActiveQuery|null $query
     * @return ActiveQuery
     */
    public static function find(array $config = [], ActiveQuery $query = null)
    {
        return static::getInstance($config, $query)->filter()->getQuery();
    }

}