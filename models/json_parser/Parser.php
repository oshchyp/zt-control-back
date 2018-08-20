<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.08.2018
 * Time: 11:21
 */

namespace app\models\json_parser;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;

class Parser extends Model
{

    public $jsonFilesDir = '@app/files/json/from_1c';

    public $jsonFilesPath = [];

    public $jsonContentArray = [];

    public function parserRules()
    {
        return [
            ['Contract', 'class' => Contracts::className()],
            ['firm_uid', 'class' => Firms::className()],
            ['Contact_uid', 'class' => Contacts::className()],
            ['firm_uid', 'class' => ContactsSetPost::className()],

        ];
    }

    public function setJsonContentArray($array = [])
    {
        if ($array) {
            foreach ($array as $k => $v) {
                if ($v && is_array($v)) {
                    foreach ($v as $saveData) {
                        $this->jsonContentArray[$k][] = $saveData;
                    }
                }
            }
        }
    }

    public function loadJsonFilesPath()
    {
        if (is_dir(Yii::getAlias($this->jsonFilesDir))) {
            $this->jsonFilesPath = FileHelper::findFiles(Yii::getAlias($this->jsonFilesDir));
        }
    }

    public function loadJsonContentArray()
    {
        if ($this->jsonFilesPath) {
            foreach ($this->jsonFilesPath as $filePath) {
                $this->setJsonContentArray(Json::decode(file_get_contents($filePath), true));
            }
        }
    }

    public function saveJsonInfoAll()
    {
        foreach ($this->parserRules() as $rule) {
            $this->saveJsonInfo($rule);
        }
    }

    public function saveJsonInfo($rule)
    {
        if (isset($this->jsonContentArray[$rule[0]]) && $dataArray = $this->jsonContentArray[$rule[0]]) {
            $className = $rule['class'];
            foreach ($dataArray as $dataSave) {
                $object = $className::getInstance($dataSave);
                $object->attributes = $dataSave;
                $object->loadModel();
                $object->save();
            }
        }
    }

    public function setObjectParams($object, $params)
    {
        if ($params) {
            foreach ($params as $attr => $value) {
                if (strstr($attr, '::')) {
                    $attr = str_replace('::', '', $attr);
                    $object::$attr = $value;
                } else {
                    $object->$attr = $value;
                }
            }
        }
    }

    public function parse()
    {
        $this->loadJsonFilesPath();
        $this->loadJsonContentArray();
        $this->saveJsonInfoAll();
    }

}