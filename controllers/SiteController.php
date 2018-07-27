<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;

class SiteController extends Controller
{
    /*
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
       
        $pat = Yii::getAlias('@app').'/logs/api';
        $contentDir = [];
        if (Yii::$app->request->get('pat')) {
            $pat = base64_decode(Yii::$app->request->get('pat'));
        }
        if (is_file($pat)) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return json_decode(file_get_contents($pat));
        } elseif (is_dir($pat)) {
            $scanDir = scandir($pat);
            if ($scanDir) {
                foreach ($scanDir as $v) {
                    if (is_dir($pat.'/'.$v) || is_file($pat.'/'.$v)) {
                        $contentDir[] = [
                        'name' => $v,
                        'link' => base64_encode($pat.'/'.$v),
                    ];
                    }
                }
            }
        }
        //  dump($contentDir, 1);

        return $this->render('index', ['contentDir' => $contentDir]);
    }

    public function actionError()
    {
        return $this->render('error');
    }

    
}
