<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.09.2018
 * Time: 13:32
 */

namespace app\controllers;


use toriphes\console\Runner;
use vova07\console\ConsoleRunner;
use yii\filters\AccessControl;
use yii\web\Controller;

class MigrationController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'ips' => ['46.250.16.180']
                ]
            ],
            'denyCallback' => function () {
                die('Access is denied');
            }
        ];
        return $behaviors;
    }

    public function actionIndex(){
        $runner = new ConsoleRunner(['phpBinaryPath' => '/usr/bin/php', 'file' => 'php '.\Yii::getAlias('@app/yii')]);
        $output = $runner->run('migrate');
        dump($runner->phpBinaryPath);
        echo \Yii::getAlias('@app/yii');

        exec('/usr/bin/php '.\Yii::getAlias('@app/yii'). ' migrate');

    }

}