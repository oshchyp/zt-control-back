<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 10:32
 */

namespace app\modules\api\models;

use app\modules\api\models\interfaces\FarmCultureInterface;
use app\modules\api\models\interfaces\FarmInterface;
use yii\base\Component;
use yii\base\Exception;
use yii\base\Model;


class FarmCulturesMultipleSave extends Model
{

    /**
     * @var FarmInterface
     */
    protected $farm;

    /**
     * @var array
     */
    protected $data=[];

    /**
     * @var FarmCultureInterface
     */
    protected $farmCulturesClassName;

    /**
     * @var FarmCultureInterface[]
     */
    private   $_farmCulturesInstances;
    /**
     * @return FarmInterface
     */
    public function getFarm()
    {
        return $this->farm;
    }

    /**
     * @param FarmInterface $farm
     */
    public function setFarm(FarmInterface $farm): void
    {
        $this->farm = $farm;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return FarmCultureInterface::className()
     */
    public function getFarmCulturesClassName()
    {
        return $this->farmCulturesClassName;
    }

    /**
     * @param $farmCulturesClassName
     * @throws Exception
     */
    public function setFarmCulturesClassName($farmCulturesClassName): void
    {
        $farmCulturesObject = new $farmCulturesClassName();
        if (!$farmCulturesObject instanceof FarmCultureInterface){
            throw new Exception('Farm cultures class  instance must inherit FarmCultureInterface');
        }
        $this->farmCulturesClassName = $farmCulturesClassName;
    }

    /**
     * @return FarmCultureInterface[]
     */
    public function getFarmCulturesInstances()
    {
        return $this->_farmCulturesInstances;
    }

    /**
     * @return $this
     */
    public function createFarmCulturesInstances(){
        $instancesQ = count($this->getData());
        $this->_farmCulturesInstances = [];
        $farmCulturesClassName = $this->getFarmCulturesClassName();
        for ($i=0; $i<$instancesQ; $i++){
            $instance = new $farmCulturesClassName();
            $instance->setFarmUID($this->getFarm()->getUID());
         //   dump($this->getFarm()->getUID()); die();
            $this->_farmCulturesInstances[] = $instance;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function loadFarmCulturesInstances(){
        Model::loadMultiple($this->getFarmCulturesInstances(),$this->getData(),'');
        return $this;
    }

    /**
     * @return bool
     */
    public function validateFarmCulturesInstances(){
        if (!Model::validateMultiple($this->getFarmCulturesInstances())){
            foreach ($this->getFarmCulturesInstances() as $culture){
                if ($culture->getErrors()){
                     $this->getFarm()->addError('farmCultures',$culture->getErrors());
                }
            }
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function saveFarmCulturesInstances(){
        if ($this->getFarmCulturesInstances() && $this->loadFarmCulturesInstances()->validateFarmCulturesInstances()){
            foreach ($this->getFarmCulturesInstances() as $item){
                $item->save();
            }
            return true;
        }
        return false;
    }


    /**
     * @param FarmInterface $farm
     * @param $farmCulturesClassName
     * @param array $data
     * @return FarmCulturesMultipleSave
     */
    public static function getInstance(FarmInterface $farm, $farmCulturesClassName, $data=[]){
//dump($data);die();
        $instance = new static();
        $instance->setData($data);
        $instance->setFarm($farm);
        $instance->setFarmCulturesClassName($farmCulturesClassName);
        $instance->createFarmCulturesInstances();
        return $instance;
    }

}