<?php

namespace app\modules\api\controllers;

use yii\helpers\ArrayHelper;
use app\modules\api\models\Users;

class UserController extends Controller
{
    public function init()
    {
        $this->params['HttpBearerAuth']['only'] = ['noAuth'];
        $this->setResource(new Users());
        parent::init();

    }

    public function actionAuthPhone()
    {
        $this->responseData = Users::authPhone(ArrayHelper::getValue($this->getRequestData(), 'phone'));
        $this->setResponseParams(static::RESPONSE_PARAMS_AUTH_PHONE);
    }

    public function actionAuth()
    {
        $this->responseData = Users::auth($this->getRequestData());
        $this->setResponseParams(static::RESPONSE_PARAMS_AUTH);

        if ($this->responseData && is_object($this->responseData) && !$this->responseData->getErrors()) {
            $this->responseData = ['token' => $this->responseData->token, 'user' => $this->responseData->toArray()];
        }
    }
}
