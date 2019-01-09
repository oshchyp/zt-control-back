<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 12:24
 */

namespace app\models\firms;


use app\models\asrelation\FirmManagersAsRelation;
use app\models\asrelation\FirmOwnersAsRelation;
use app\models\asrelation\FirmStatusesAsRelation;
use app\models\Contacts;
use app\models\Culture;
use app\models\Elevators;
use app\models\FirmCultures;
use app\components\models\EstablishRelation;
use app\components\models\ModelAsResourceInterface;
use app\models\Points;
use app\models\Regions;

class Firms extends \app\models\Firms implements ModelAsResourceInterface
{

    /**
     * @return array
     */
    public function fields()
    {
        $fields = [
            'nearElevator', 'contacts', 'cultures', 'mainCulture', 'distances', 'region', 'point',
            'processedSquare', 'mainContact', 'owner', 'manager', 'status', 'sender' => 'senderConvert'
        ];
        return array_merge(parent::fields(), $fields);
    }

    function getSenderConvert()
    {
        foreach (static::distributionStatuses() as $item) {
            if ($item['id'] == $this->sender) {
                return $item;
            }
        }
        return [
            'name' => 'Не известно',
            'id' => 0
        ];
    }

    /**
     * @return array
     */
    public static function relations()
    {
        return ['contacts', 'region', 'point', 'nearElevator',
            'cultures.culture', 'cultures.regionCultureRelation', 'distances.point', 'mainCulture', 'owner', 'manager'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistances()
    {
        return $this->hasMany(FirmsDistances::className(), ['firmUID' => 'uid']);
    }

    public function getProcessedSquare()
    {
        $result = 0;
        if ($this->cultures) {
            foreach ($this->cultures as $item) {
                if ($item->year == date('Y')) {
                    $result += $item->square;
                }
            }
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return EstablishRelation::hasOne($this, FirmOwnersAsRelation::instance(), ['uid' => 'ownerUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return EstablishRelation::hasOne($this, FirmManagersAsRelation::instance(), ['uid' => 'managerUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus(){
        return EstablishRelation::hasOne($this,FirmStatusesAsRelation::instance(),['id'=>'statusID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['uid' => 'regionUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoint()
    {
        return $this->hasOne(Points::className(), ['uid' => 'pointUID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNearElevator()
    {
        return $this->hasOne(Elevators::className(), ['uid' => 'nearElevatorUID']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contacts::className(), ['firmUID' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainContact()
    {
        return $this->hasOne(Contacts::className(), ['firmUID' => 'uid'])->where(['main' => 1]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCultures()
    {
        return $this->hasMany(FirmCultures::className(), ['firmUID' => 'uid'])->orderBy(['year' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainCulture()
    {
        return $this->hasOne(Culture::className(), ['uid' => 'mainCultureUID']);
    }

}