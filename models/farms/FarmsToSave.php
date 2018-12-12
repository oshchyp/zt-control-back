<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 12:03
 */

namespace app\models\farms;


use app\components\behaviors\EstablishUID;
use app\components\models\ModelAsResourceInterface;

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

        $rules = parent::rules();
        $rules[] = [['cultures'],'safe'];
        return $rules;
    }

    public function behaviors()
    {
        return [
            'establishUID' => [
                'class' => EstablishUID::className()
            ],
        ];
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