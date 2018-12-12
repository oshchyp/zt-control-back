<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 14:02
 */

namespace app\modules\api\controllers;


use app\models\filter\FilterDataInterface;
use app\modules\api\models\FirmPeoplesFilter;

class FirmPeoplesController extends Controller
{


    public function actionIndex(){

        $this->activeIndex();
    }

    public function actionList()
    {
        if ($this->getFilterInstance()) {
            $this->filter($this->getFilterInstance());
        }
        $this->setPagination();
        $this->activeIndex();
    }

    public function actionUpdate($id){
        $this->activeUpdate($id);
    }

    public function actionCreate(){
        $this->activeCreate();
    }

    public function actionDelete($id=null){
        $this->activeDelete($id);
    }

    /**
     * @return FilterDataInterface
     */
    public function getFilterInstance() : FilterDataInterface {
        return null;
    }


}