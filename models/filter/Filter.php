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

class Filter extends Model
{

    /**
     * @var
     */
    private $query;

    public function rules()
    {
        $rules = [];
        $fields = [
            [$this->fieldsForSearchAll(),'string'],
            [$this->fieldsForSearchIndividual(),'string'],
            [$this->fieldsForEquating(),'string']
        ];
       foreach ($fields as $arr){
           if ($arr[0]){
               $arr[0] =  array_values($arr[0]);
               $rules[] = $arr;
           }
       }
       return $rules ? $rules : parent::rules();
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
        $methods = get_class_methods($this);
        foreach ($methods as $v) {
            if (substr($v, 0, 6)=='filter' && $v !=='filter') {
                $this->$v();
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function filterSearchAll(){
        $searchString = $this->stringForSearchAll();
        $fields = $this->fieldsForSearchAll();
        if ($searchString && $fields){
            foreach ($fields as $attribute => $fieldName){
                if (method_exists($this,$attribute.'SearchAll')){
                    $methodName = $attribute.'SearchAll';
                    $this->$methodName();
                } else {
                    $this->getQuery()->orWhere(['LIKE',$fieldName,$searchString]);
                }
             }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function filterSearchIndividual(){
        if ($fields = $this->getFilterFields('fieldsForSearchIndividual')){
            foreach ($fields as $attribute => $fieldName){
                if ($this->$attribute) {
                    $methodName = $attribute.'SearchIndividual';
                 //   dump($methodName);
                    if (method_exists($this,$methodName)){
                        $this->$methodName();
                    } else{
                        $this->getQuery()->andWhere(['LIKE', $fieldName, $this->$attribute]);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function filterEquating(){
        if ($fields = $this->getFilterFields('fieldsForEquating')){
            foreach ($fields as $attribute => $fieldName){
                if (method_exists($this,$attribute.'Equating')){
                    $methodName = $attribute.'Equating';
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
        foreach ($rules as $v){
            $rule = is_string($v) ? [$v,$v] : $v;
            $attribute = $rule[0];
            if ($this->$attribute || ($this->stringForSearchAll() && $this->existingFields($attribute,'fieldsForSearchAll'))){
                $this->getQuery()->joinWith($rule[1].' AS '.$rule[0]);
            }
        }
    }

    /**
     * @param $method
     * @return array
     */
    protected function getFilterFields($method){
        $result = [];
        if ($this->$method()){
            foreach ($this->$method() as $attribute => $fieldName){
                if (is_numeric($attribute)){
                    $attribute = $fieldName;
                }
                $result[$attribute] = $fieldName;
            }
        }
        return $result;
    }

    protected function existingFields($attribute, $method){
         return key_exists($attribute,$this->getFilterFields($method));
    }

    /**
     * @param array $config
     * @param ActiveQuery|null $query
     * @return Filter
     */
    public static function getInstance(array $config=[],ActiveQuery $query=null){
        $instance = new static($config);
         if ($query){
             $instance->setQuery($query);
         }
         return $instance;
    }

    /**
     * @param array $config
     * @param ActiveQuery|null $query
     * @return ActiveQuery
     */
    public static function find(array $config=[],ActiveQuery $query=null){
        return static::getInstance($config,$query)->filter()->getQuery();
    }

}