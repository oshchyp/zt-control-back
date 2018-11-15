<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.10.2018
 * Time: 11:34
 */

namespace app\models\filter;


use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class FilterData extends ActiveRecord
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
    private $_sortType = 'ASC';

//    /**
//     * @return array
//     */
    public function attributes()
    {
        return array_merge(parent::attributes(),['searchString','sortField','sortType']);
    }

    public function rules()
    {
        return [
            ['searchString','sortField','sortType'],'string'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getQuery()
    {
        if (!$this->_query){
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
         $this->_sortField = $sortField;
    }

    /**
     * @return string
     */
    public function getSortType()
    {
        if (mb_strtolower($this->_sortType) == 'desc')
            return SORT_DESC;
        else
            return SORT_ASC;
    }

    /**
     * @param string $sortType
     */
    public function setSortType($sortType)
    {
        $this->_sortType = $sortType;
    }

    /**
     * @return array
     */
    public static function methodsByFilter(){
        $result=[];
        $allMethods = get_class_methods(static::className());
        foreach ($allMethods as $methodName){
             if (substr($methodName, 0, 6) == 'filter'){
                 $result[] = $methodName;
             }
        }
        return $result;
    }

    public function runFilterMethods(){
        if ($filterMethods = static::methodsByFilter()){
            foreach ($filterMethods as $methodName){
                $this->$methodName();
            }
        }
    }

    /**
     * @return $this
     */
    public function filterSearchAll()
    {
        $searchString = $this->getSearchString();
        $fields = $this->getFilterFields($this->fieldsSearchAll());
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
     * @param array $params
     * @return ActiveQuery
     */
    public function search($params=[]){
        if (!$this->load($params,'')){
            return $this->getQuery();
        }

        if ($this->validate()) {
            $this->runFilterMethods();
        }

        return $this->getQuery();

    }


}