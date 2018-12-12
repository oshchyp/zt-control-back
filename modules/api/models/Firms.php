<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 21.11.2018
 * Time: 12:24
 */

namespace app\modules\api\models;


use app\models\Contacts;
use app\models\FirmCultures;
use app\modules\api\components\EstablishRelation;
use app\modules\api\models\interfaces\ModelAsResource;

class Firms extends \app\models\Firms implements ModelAsResource
{


    public $saveContacts = null;

    /**
     * @var null
     */
    public $saveCultures = null;

    /**
     * @var null
     */
    public $saveDistances = null;


    /**
     * @return array
     */
    public function fields()
    {
        $fields = ['nearElevator', 'contacts', 'cultures', 'mainCulture', 'distances', 'region', 'point', 'processedSquare', 'mainContact', 'owner', 'manager', 'status',
            'sender' => 'senderConvert'];
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
     * @param $contacts
     */
    public function setContacts($contacts)
    {
        $this->saveContacts = $contacts;
    }

    /**
     * @param $value
     */
    public function setCultures($value)
    {
        $this->saveCultures = $value;
    }

    /**
     * @param $value
     */
    public function setDistances($value)
    {
        $this->saveDistances = $value;
    }

    /**
     *
     */
    public function saveContacts()
    {
        if ($this->saveContacts === null)
            return;
        $transaction = Contacts::getDb()->beginTransaction();
        try {
            Contacts::deleteAll(['firmUID' => $this->uid]);
            if ($this->saveContacts && is_array($this->saveContacts)) {
                foreach ($this->saveContacts as $contactInfo) {
                    $object = new Contacts();
                    $object->attributes = $contactInfo;
                    $object->firmUID = $this->uid;
                    $object->save();
                    if ($object->hasErrors()) {
                        $this->addErrors(['contacts' => $object->getErrors()]);
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        } catch (\Throwable $throwable) {
            $transaction->rollBack();
            throw $throwable;
        }

    }

    /**
     * @param bool $delete
     */
    public function saveCultures($delete = true)
    {
        if ($this->saveCultures === null)
            return;
        if ($delete) {
            FirmCultures::deleteAll(['firmUID' => $this->uid]);
        }
        if ($this->saveCultures && is_array($this->saveCultures)) {
            foreach ($this->saveCultures as $info) {
                $object = new FirmCultures();
                $object->attributes = $info;
                $object->firmUID = $this->uid;
                $object->save();
                if ($object->hasErrors()) {
                    $this->addErrors(['сultures' => $object->getErrors()]);
                }
            }
        }
    }

    /**
     *
     */
    public function saveDistances()
    {

        if ($this->saveDistances === null)
            return;

        FirmsDistances::deleteAll(['firmUID' => $this->uid]);
        if ($this->saveDistances && is_array($this->saveDistances)) {
            foreach ($this->saveDistances as $item) {
                $instance = new FirmsDistances();
                $instance->attributes = $item;
                $instance->firmUID = $this->uid;
                $instance->save();
                if ($instance->hasErrors()) {
                    $this->addErrors(['distances' => $instance->getErrors()]);
                }
            }
        }
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

}