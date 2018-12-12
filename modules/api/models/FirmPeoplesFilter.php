<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.12.2018
 * Time: 15:02
 */

namespace app\modules\api\models;


use app\components\behaviors\PhoneHandling;
use app\components\filter\FilterDataInterface;
use app\components\filter\FilterDataTrait;

abstract class FirmPeoplesFilter extends FirmPeoples implements FilterDataInterface
{

    use FilterDataTrait;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name','phone','email','firms.name','stringForSearchAll'],'string']
        ];
    }

    public function rulesFilter(){
        return [
            [['name','phone','email','firms.name'],'andWhere',['like']],
            [['stringForSearchAll'],'search',[['name','phone','email','firms.name']]]
        ];
    }

    public function attributesAdd()
    {
        return ['firms.name','stringForSearchAll'];
    }

    public function behaviors()
    {
        return [
               'phoneHandling' => [
                    'class' => PhoneHandling::className()
                ]
         ];
    }

}