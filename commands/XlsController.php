<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 07.08.2018
 * Time: 10:41
 */

namespace app\commands;

use app\models\Firms;
use app\models\xls\Parser;
use app\models\xls\ParserActiveRecord;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\VarDumper;

class XlsController extends Controller
{
    public function actionIndex(){
        $xlsObject = ParserActiveRecord::getInstance(
            [
                'filePath'=>'/Users/programmer_5/PhpstormProjects/ZT-CRM/files/xls/firms.xlsx',
                'ignoreRows' => [0],
                'model' => Firms::className(),
                'columns' => [
                    'C' => 'phone',
                    'D' => 'name',
                ],
            ]
        );
        $xlsObject->saveXls();
        return ExitCode::OK;
    }

    function actionCats(){
        $categories = [
            [
                'id' => 1,
                'name' => 'parent 1',
                'parent' => 0,
            ],
            [
                'id' => 2,
                'name' => 'parent 2',
                'parent' => 0,
            ],
            [
                'id' => 3,
                'name' => 'parent 3',
                'parent' => 0,
            ],
            [
                'id' => 4,
                'name' => 'child 1',
                'parent' => 1,
            ],
            [
                'id' => 5,
                'name' => 'child 2',
                'parent' => 4,
            ],
            [
                'id' => 6,
                'name' => 'child 3',
                'parent' => 2,
            ]

        ];

        $cats = $this->viewCats($categories,0);
        VarDumper::dump($cats);die();
    }

    function viewCats($categories,$parent){
        $result = [];
        foreach ($categories as $k => $catInfo){
            if ($catInfo['parent'] == $parent){
                $catInfo['subs'] = $this -> viewCats($categories,$catInfo['id']);
                $result[] = $catInfo;
            }
        }
        return $result;
    }
}