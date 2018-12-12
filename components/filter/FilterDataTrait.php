<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.10.2018
 * Time: 13:57
 */

namespace app\components\filter;

use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Trait FilterTrait
 * @package app\models\filter
 * @mixin ActiveRecord
 */
trait FilterDataTrait
{

    /**
     * @var ActiveQuery
     */
    private $_query;


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
        return [];
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function sortValidate($attribute)
    {
        $value = mb_strtolower($this->getAttr($attribute));
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
    public function getAttr($attribute)
    {
        $getter = 'get' . str_replace('.','',$attribute);
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

        $custMethod = str_replace(['|','.'],'',$attribute).'InQuery';
        if (method_exists(static::className(),$custMethod)){
            return $this->$custMethod();
        } else if ($n = ArrayHelper::getValue($this->attributesNameInQuery($attribute),$attribute)){
            return $n;
        }

        if (!strstr($attribute, '.') && !strstr($attribute,static::tableName() . '.')) {
            $attribute = static::tableName() . '.' . $attribute;
        }
        return $attribute;
    }

    public function attributesNameInQuery($attribute){
        return null;
    }

    public function setJoinByAttributeName($attribute)
    {
        $setterJoin = str_replace('.','',$attribute) . 'SetJoin';
        if (method_exists(static::className(), $setterJoin)) {
            $join = $this->$setterJoin();
            if (is_array($join)){
                $this->setJoinUniq($join);
            }
        //    die('3werwed');
            return;
        }
        $arr = explode('.', $attribute);
        if (count($arr) == 2) {
            $joinName = $arr[0];
            if ($this->getRelation($joinName, false)) {
                $this->getQuery()->joinWith($joinName . ' AS ' . $joinName);
            }
        }
    }

    public function existJoin($join){
        if ($this->getQuery()->join){
            foreach ($this->getQuery()->join as $j){
                if (in_array($join,$j)){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param array $join
     */
    public function setJoinUniq(array $join){
        [$type,$table,$on] = $join;
        $type .= ' JOIN';
        if (!$this->existJoin($table)){
            $this->getQuery()->join($type,$table,$on);
        }
    }

    /**
     * @return array
     */
    private function _rulesFilter()
    {
        $rules = $this->rulesFilter();

        if ($rules) {
            foreach ($rules as $k => $rule) {
                if (is_string($rule)) {
                    $rules = array_merge($rules, $this->rulesFilterByAttributeString($rule));
                }
            }
        }
        return $this->_rulesFilterConverted($rules);
    }

    /**
     * @param $rules
     * @return array
     */
    private static function _rulesFilterConverted($rules)
    {
        $result = [];
        foreach ($rules as $rule){

            $method = static::getFilterMethodName($rule[1]);
            if (!$method){
                $rule[2] = [$rule[1]];
                $rule[1] = 'filterQueryAndWhere';
            }
            foreach ($rule[0] as $attribute){
                $resultPush = [$attribute,$rule[1]];
                if (isset($rule[2])){
                    $resultPush[2] = $rule[2];
                }
                $result[] = $resultPush;
            }
        }
        return $result;
    }

    /**
     * @param $name
     * @param bool $nullIfAbsent
     * @return null|string
     */
    public static function getFilterMethodName($name,$nullIfAbsent=true){
        $method = 'filterQuery' . $name;
        if ($nullIfAbsent && !method_exists(static::className(),$method))
            return null;
        return $method;
    }


    /**
     * @param $attribute
     * @param bool $exception
     * @param null $default
     * @return array|null
     */
    public function rulesFilterByAttributeString($attribute, $exception = true, $default = null)
    {
        $arr = explode('|', $attribute);
        if (count($arr) > 1) {
            $result = [];
            for ($i = 1; $i < count($arr); $i++) {
                $arrPush = explode(':', $arr[$i]);
                if (isset($explode1[1])) {
                    $arrPush[1] = explode(',', $arrPush[1]);
                }
                $result[] = $arrPush;
            }
            return $result;
        } else if ($exception) {
            throw new InvalidArgumentException('attribute must have separator |');
        }

        return $default;
    }

    public function runFilterMethods()
    {
        foreach ($this->_rulesFilter() as $rule) {

          $attribute = $rule[0];
          $value = $this->getAttr($attribute);

            if ($value !== null){
                $method = $this->getFilterMethodName($rule[1],false);
                $this->setJoinByAttributeName($attribute);
                if (isset($rule[2])){

                    $this->$method($this->getFieldNameInQuery($attribute), $value,...$rule[2]);
                } else {
                    $this->$method($this->getFieldNameInQuery($attribute), $value);
                }

            }
        }
    }


    /**
     * @param $attribute
     * @param $value
     * @param $sign
     * @param string $typeWhere
     */
    public function filterQueryWhere($attribute, $value,$sign,$typeWhere='and'){
        $method = $typeWhere.'FilterWhere';
        $this->getQuery()->$method([$sign, $attribute, $value]);
        if (mb_strtolower($sign) == 'like'){
            $this->getQuery()->addOrderBy([
                '('.$attribute.' = "'.$value.'")' => SORT_DESC,
                '('.$attribute.' LIKE "'.$value.'%")' => SORT_DESC,
                $attribute => SORT_ASC
            ]);
          //  $this->getQuery()->addGroupBy($attribute);
        }
    }

    public function filterQueryWherePackageOneValue($attribute, $value,$fields=[],$typeWhere='and',$typeWhereInside='or',$defaultSign = 'LIKE'){
        $method = $typeWhere.'FilterWhere';
        $where = [$typeWhereInside];
        foreach ($fields as $fieldName) {
            $fieldExplode = explode('@', $fieldName);
            $fieldName = $fieldExplode[0];
            $sign = isset($fieldExplode[1]) ? $fieldExplode[1] : $defaultSign;
            $methodName = str_replace('.','',$fieldName) . 'filterQuerySearch';
            $this->setJoinByAttributeName($fieldName);
            if (method_exists($this, $methodName)) {
                $where[] = $this->$methodName($fieldName,$value);
            } else {
                $where[] = [$sign, $this->getFieldNameInQuery($fieldName), $value];
            }
        }
       // die();
        $this->getQuery()->$method($where);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $sign1
     * @param null $sign2
     */
    public function filterQueryRange($attribute, $value, $sign1, $sign2=null)
    {
       if ($sign2){
           $this-> getQuery()-> andFilterWhere(['or',[$sign1,$attribute,$value],[$sign2,$attribute,$value]]);
       } else {
           $this->filterQueryWhere($attribute, $value,$sign1);
       }
     //  dump($this-> getQuery()->createCommand()->getRawSql(),1);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $sign
     */
    public function filterQueryAndWhere($attribute, $value, $sign)
    {
        $this->filterQueryWhere($attribute, $value,$sign,'and');

    }

    /**
     * @param $attribute
     * @param $value
     * @param $sign
     */
    public function filterQueryOrWhere($attribute, $value, $sign)
    {
        $this->filterQueryWhere($attribute, $value,$sign,'or');
    }

    public function filterQuerySeveralValues($attribute, $value,$sign = 'or'){
        $this->getQuery()->andFilterWhere([]);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $fields
     */
    public function filterQuerySearch($attribute, $value, $fields)
    {
        $this->filterQueryWherePackageOneValue($attribute, $value,$fields,'and','or','LIKE');
    }

    public function filterQuerySort($attribute, $value)
    {
        $this->getQuery()->addOrderBy([$attribute => $value]);
     //   $this->getQuery()->addGroupBy($attribute);
    }


    public function beforeSearch(){

    }

    public function afterSearch(){

    }


    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function _search($params = [])
    {

        $this->load($params,'');
//        dump($params);
//        dump($this->getAttribute('owner.name|sort'));
        if ($this->validate()) {
            $this->beforeSearch();
            $this->runFilterMethods();
            $this->afterSearch();
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
        $instance = new static();
        if ($query){
            $instance -> setQuery($query);
        }
        return $instance->_search($params);
    }

}