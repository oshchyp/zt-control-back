<?php

namespace app\modules\crmApi\controllers;

use Yii;

/**
 * Default controller for the `api` module.
 */
class DefaultController extends Controller
{
    public function init()
    {
        $this->params['HttpBearerAuth']['only'] = ['index1'];
        parent::init();
    }

    /**
     * Renders the index view for the module.
     *
     * @return string
     */
    public function actionIndex()
    {
        dump(getallheaders(), 1);
        Yii::$app->response->setStatusCode(404);
    }

    public function actionError()
    {
        Yii::$app->response->statusCode = 404;
        // echo '2';
    }
}
