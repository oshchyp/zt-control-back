<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 14.01.2019
 * Time: 09:46
 */

namespace app\components\bitAccess;


use yii\base\Component;
use yii\db\ActiveQuery;

class BitAccessFilter extends Component
{

    /**
     * @var ActiveQuery
     */
    protected $query;

    /**
     * @var string
     */
    protected $resourceBitField;

    /**
     * @var integer
     */
    protected $userBit;

    /**
     * @return ActiveQuery
     */
    public function getQuery(): ActiveQuery
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery(ActiveQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getResourceBitField()
    {
        return $this->resourceBitField;
    }

    /**
     * @param string $resourceBitField
     */
    public function setResourceBitField($resourceBitField): void
    {
        $this->resourceBitField = $resourceBitField;
    }

    /**
     * @return integer
     */
    public function getUserBit()
    {
        if ($this->userBit === null){
            $this->userBit = \Yii::$app->user ? \Yii::$app->user->identity->elevatorBit : null;
        }
        return $this->userBit;
    }

    /**
     * @param integer $userBit
     */
    public function setUserBit($userBit): void
    {
        $this->userBit = $userBit;
    }

    public function filter(){
        $this->getQuery()->andWhere($this->getResourceBitField().' & '.$this->getUserBit());
    }

    /**
     * @param ActiveQuery $query
     * @param $resourceBitField
     * @param null $userBit
     * @return BitAccessFilter
     */
    public static function getInstance(ActiveQuery $query, $resourceBitField, $userBit=null){
        return new static([
            'query' => $query,
            'resourceBitField' => $resourceBitField,
            'userBit' => $userBit
        ]);
    }

}