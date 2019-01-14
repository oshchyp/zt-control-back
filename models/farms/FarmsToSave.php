<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 12:03
 */

namespace app\models\farms;


use app\components\behaviors\EstablishUID;
use app\components\bitAccess\BitAccessBehavior;
use app\components\models\ModelAsResourceInterface;
use app\models\Firms;

/**
 * Class FarmsToSave
 * @package app\modules\api\models
 * @property array $cultures
 */

class FarmsToSave extends \app\models\Farms implements FarmInterface, ModelAsResourceInterface
{

    protected $_cultures;

    public function rules()
    {
        return [
            [['square'], 'number'],
            [['uid', 'name', 'regionUID', 'pointUID'], 'string', 'max' => 250],
            [['uid'], 'unique'],
            [['cultures'],'safe']
        ];

    }

    public function behaviors()
    {
        return [
            'establishUID' => [
                'class' => EstablishUID::className()
            ],
            'BitAccess' => [
                'class' => BitAccessBehavior::className(),
                'errorForbidden' => 'Farms of this firm is forbidden to edit'
            ]
        ];
    }

    /**
     * @return int
     */
    public function getResourceBitOld()
    {
        return $this->getFirmBit();
    }

    /**
     * @return int
     */
    public function getResourceBitNew(){
        return $this->getFirmBit();
    }

    /**
     * @return int
     */
    public function getFirmBit(){
        if ($firm = Firms::find()->where(['uid'=>$this->firmUID])->one()){
            return $firm->elevatorBit;
        }
        return 0;
    }
    /**
     * @return mixed
     */
    public function getCultures()
    {
        return $this->_cultures;
    }

    /**
     * @param mixed $cultures
     */
    public function setCultures($cultures): void
    {
        $this->_cultures = $cultures;
    }


    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        FarmCultures::deleteAll(['farmUID'=>$this->getUID()]);
        FarmCulturesMultipleSave::getInstance($this,FarmCultures::className(),$this->getCultures())->saveFarmCulturesInstances();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return string
     */
    public function getUID()
    {
        return $this->uid;
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return [];
    }
}