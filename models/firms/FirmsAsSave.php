<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.01.2019
 * Time: 09:37
 */

namespace app\models\firms;


use app\models\Contacts;
use app\models\FirmCultures;

class FirmsAsSave extends \app\models\Firms
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
}