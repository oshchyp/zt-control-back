<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.10.2018
 * Time: 13:57
 */

namespace app\models\filter;

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
     * @var string
     */
    private $_searchString;

    /**
     * @var string
     */
    private $_sortField;

    /**
     * @var string
     */
    private $_sortType;

    /**
     * @return array
     */
    public function rules()
    {
        $fields = [
            [['searchString', 'sortField'], 'string'],
            ['sortType', 'integer']
        ];
        if ($this->rulesFilterModel()) {
            $fields = array_merge($fields, $this->rulesFilterModel());
        }
        return $fields;
    }

    /**
     * @return array
     */
    public function rulesFilterModel()
    {
        return [];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        if ($this->attributesFilterModel()) {
            $attributes = array_merge($attributes, $this->attributesFilterModel());
        }
        return $attributes;
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
     * @return string
     */
    public function getSearchString()
    {
        return $this->_searchString;
    }

    /**
     * @param string $searchString
     */
    public function setSearchString($searchString)
    {
        $this->_searchString = $searchString;
    }

    /**
     * @return string
     */
    public function getSortField()
    {
        return $this->_sortField;
    }

    /**
     * @param string $sortField
     */
    public function setSortField($sortField)
    {
        if (mb_strtolower($sortField) == 'desc')
            $this->_sortType = SORT_DESC;
        else
            $this->_sortType = SORT_ASC;
    }

    /**
     * @return string
     */
    public function getSortType()
    {
       return $this->_sortType;
    }

    /**
     * @param string $sortType
     */
    public function setSortType($sortType)
    {
        $this->_sortType = $sortType;
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

    public function setJoinByAttributeName($attribute){
         $arr = explode('.',$attribute);
         if (count($arr) == 2 ){
             $joinName = $arr[0];
             $setterJoin = $joinName.'SetJoin';
             if (method_exists(static::className(),$setterJoin)){
                 $this->$setterJoin();
             } else if ($this->getRelation($joinName,false)){
                 $this->getQuery()->joinWith($joinName.' AS '.$joinName);
             }
         }
    }

    /**
     * @return array
     */
    public static function methodsByFilter()
    {
        $result = [];
        $allMethods = get_class_methods(static::className());
        foreach ($allMethods as $methodName) {
            if (substr($methodName, 0, 11) == 'filterQuery') {
                $result[] = $methodName;
            }
        }
        return $result;
    }

    public function runFilterMethods()
    {
        if ($filterMethods = static::methodsByFilter()) {
            foreach ($filterMethods as $methodName) {
                $this->$methodName();
            }
        }
    }

    /**
     * @param $attribute
     * @return array
     */
    public function filterRulesByAttribute($attribute){
        $arr = explode('|',$attribute);
        $result = [
            'attributeName' => $arr[0],
            'filters' => [],
        ];
        if (count($arr)>1){
            for ($i=1;  $i < count($arr); $i++){
                $rule = $arr[$i];
                $ruleExp = explode(':',$rule);
                $result['filters'][] =[
                    'name' => $ruleExp[0],
                    'argument' => isset($ruleExp[1]) ? $ruleExp[1] : null
                ];
            }
        }
        return $result;
    }

    public function filterQuery(){
        foreach ($this->attributes() as $attribute){
            $filterRules = $this -> filterRulesByAttribute($attribute);
            if ($filterRules['filters']){
                foreach ($filterRules['filters'] as $v){
                    $methodName = 'filterQuery'.$v['name'];
                    $this->setJoinByAttributeName($filterRules['attributeName']);
                    $this->$methodName($filterRules['attributeName'],$this->getAttribute($attribute),$v['argument']);
                }
            }
        }
    }
//
//    /**
//     * @return $this
//     */
//    public function filterQuerySearchAll()
//    {
//        if ($searchString = $this->getSearchString() && $fields = $this->fieldsSearchAll()) {
//            $where = ['or'];
//            foreach ($fields as $attribute) {
//                $this->setJoinByAttributeName($attribute);
//                $where[] = ['LIKE', $attribute, $searchString];
//            }
//            $this->getQuery()->andFilterWhere($where);
//        }
//        return $this;
//    }
//
//
//    /**
//     * @return $this
//     */
//    public function filterQuerySearchIndividual()
//    {
//        if ($fields = $this->fieldsSearchIndividual()) {
//            foreach ($fields as $attribute) {
//                if ($this->getAttribute($attribute)) {
//                    $this->setJoinByAttributeName($attribute);
//                    $this->getQuery()->andFilterWhere(['LIKE', $attribute, $this->getAttribute($attribute)]);
//                }
//            }
//        }
//        return $this;
//    }
//
//    /**
//     * @return $this
//     */
//    public function filterQueryEquating()
//    {
//        if ($fields = $this->fieldsEquating()) {
//            foreach ($fields as $attribute) {
//                if ($this->getAttribute($attribute)) {
//                    $this->setJoinByAttributeName($attribute);
//                    $this->getQuery()->andFilterWhere([$attribute => $this->getAttribute($attribute)]);
//                }
//            }
//        }
//        return $this;
//    }


    public function filterQueryRange($attribute,$value,$type){
        $sign = $type{0};
        $eq = iconv_strlen($type) == 2 ? $type{1} : null;
        $condition = $eq ? ['or', [$sign, $attribute, $value], [$eq,$attribute,$value]] : [$sign,$attribute,$value];
        $this->getQuery()->andFilterWhere($condition);
    }

    public function filterQueryAndWhere ($attribute,$value,$type){
        $this->getQuery()->andFilterWhere([$type,$attribute,$value]);
    }

    /**
     * @return $this
     */
    public function filterQueryComparisonMore()
    {
        if ($fields = $this->fieldsComparisonMore()) {
            foreach ($fields as $attribute) {
                if ($this->getAttribute($attribute)) {
                    $this->setJoinByAttributeName($attribute);
                    $this->getQuery()->andFilterWhere(['or', ['>', $attribute, (float)$this->getAttribute($attribute)], [$attribute => $this->getAttribute($attribute)]]);
                }
            }
        }
        return $this;
    }


    /**
     * @return array
     */
    public function fieldsSearchAll()
    {
        return [];
    }

    /**
     * @return array
     */
    public function fieldsSearchIndividual()
    {
        return [];
    }

    /**
     * @return array
     */
    public function fieldsComparisonMore()
    {
        return [];
    }

    /**
     * @return array
     */
    public function fieldsEquating()
    {
        return [];
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function search($params = [])
    {
        if (!$this->load($params, '')) {
            return $this->getQuery();
        }

        if ($this->validate()) {
            $this->filterQuery();
        }

        return $this->getQuery();

    }

}