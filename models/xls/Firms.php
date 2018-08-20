<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.08.2018
 * Time: 17:29
 */

namespace app\models\xls;


use app\models\Contacts;
use app\models\helper\Main;
use app\models\Points;
use app\models\Regions;

class Firms extends \app\models\Firms implements ModelInterface
{

    public $contactName;

    public $contactPhone;

    public $contactEmail;

    public $region;

    public $point;

    public function rules()
    {
        return [
            [array_values(static::columnXls()), 'safe']
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        foreach (static::columnXls() as $column => $attr) {
            if (!in_array($attr, $fields)) {
                $fields[] = $attr;
            }
        }
        return $fields;
    }


    public function setValues()
    {
        foreach (static::columnXls() as $column => $attr) {
            if (is_object($this->$attr)) {
                $this->$attr = trim($this->$attr->getValue());
            }
        }
    }

    public function loadModel($parser)
    {
        $this->fromParser = $parser->filePath;
        $this->setValues();
        $this->convertContactPhone();
        $this->setContactUid();
        $this->setRegionUid();
        $this->setPointUid();
        $this->generateUid();
        $this->save();
    }

    public function generateUid()
    {
        if (!$this->uid)
            $this->uid = Main::generateUid();
        return $this;
    }

    public function setContactUid()
    {
        $where = [
            ['uid' => $this->contactUID],
            ['phone' => $this->contactPhone],
            ['email' => $this->contactEmail]
        ];

        $contactModel = null;
        foreach ($where as $w) {
            $modelBy = Contacts::find()->where($w)->one();
            if (!$contactModel && $modelBy) {
                $contactModel = $modelBy;
            } else if ($contactModel && $modelBy && $contactModel->id !== $modelBy->id) {
                static::updateAll(['contactUID'=>$contactModel->uid],['contactUID'=>$modelBy->uid]);
                $modelBy->delete();
            }
        }

        if (!$contactModel) {
            $contactModel = new Contacts();
        }


        if ($this->contactPhone) {
            $contactModel->phone = $this->contactPhone;
        }
        if ($this->contactEmail) {
            $contactModel->email = (string)$this->contactEmail;
        }

        if ($contactModel->getIsNewRecord()) {
            $contactModel->name = $this->contactName ? (string)$this->contactName : null;
            $contactModel->uid = Main::generateUid();
        }

   //     $contactModel->fromParser = $this->
        if ($contactModel->save()) {
            $this->contactUID = $contactModel->uid;
        } else if (!in_array($this->name,['Балканы','ПЕЛИВАН','МИРНОПІЛЬСЬКЕ','Злагода Південь','Ізгрев','СПК ДРУЖБА','ПЕТРОПАВЛОВСКИЙ'])) {
//            dump($contactModel->getErrors());
//            dump($this->toArray(), 1);
        }


        if ($this->name == 'Евріка') {
            //    dump($contactModel->phone,1);
        }

        return $this;
    }

    public function setRegionUid()
    {
        $save = false;
        if (!$regionModel = Regions::findByName($this->region)) {
            $regionModel = new Regions();
            $save = true;
        }

        if (!$regionModel->name) {
            $regionModel->name = $this->region;
            $save = true;
        }

        if (!$regionModel->uid) {
            $regionModel->uid = Main::generateUid();
            $save = true;
        }

        if ($save) {
            if (!$regionModel->save()) {
                return $this;
            }
        }

        $this->regionUID = $regionModel->uid;
        return $this;
    }

    public function setPointUid()
    {
        if (!$pointModel = Points::findByName($this->point)) {
            $pointModel = new Points();
            $pointModel->name = $this->point;
            $pointModel->uid = Main::generateUid();
        }

        if (!$pointModel->regionUID && $this->regionUID) {
            $pointModel->regionUID = $this->regionUID;
            $save = $pointModel->save();
        } else if ($pointModel->getIsNewRecord()) {
            $save = $pointModel->save();
        } else {
            $save = true;
        }
        if ($save) {
            $this->pointUID = $pointModel->uid;
        }
        return $this;
    }

    public function convertContactPhone()
    {
        $ph = $this->contactPhone;
        if ($this->contactPhone) {
            $this->contactPhone = '+380' . (string)$this->contactPhone;
            $this->contactPhone = str_replace([' '], '', $this->contactPhone);
        }
        if ($this->contactPhone == '+380') {
           // dump($ph, 1);
        }
        return $this;
    }

    public static function columnXls()
    {
        return [
            'A' => 'rdpu',
            'B' => 'name',
            'C' => 'square',
            'D' => 'contactName',
            'E' => 'contactPhone',
            'F' => 'contactEmail',
            'G' => 'region',
            'I' => 'point',
        ];
    }

    public static function findByRdpu($rdpu)
    {
        return static::find()->where(['rdpu' => $rdpu])->one();
    }

    public static function getInstance($data)
    {
        if (isset($data['rdpu']) && is_object($data['rdpu']) && $model = static::findByRdpu($data['rdpu']->getValue())) {
            return $model;
        }
        return new static();
    }

    /*
     * LAST ID
     *
     * FIRMS 3759
     *
     * CONTACTS 20371
     *
     * */

}