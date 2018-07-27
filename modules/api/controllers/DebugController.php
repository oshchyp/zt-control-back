<?php

namespace app\modules\api\controllers;

use app\models\Permissions;
use app\models\PermissionsByteMask;
use Yii;
use app\modules\api\models\Users;

class DebugController extends Controller
{
    public function init()
    {
        $this->params['HttpBearerAuth']['only'] = ['index1'];
        //$this->setResource(new Users());
        parent::init();
    }

    public function actionIndex()
    {

        $perm = new PermissionsByteMask();
        dump(1 << 1,1);
    }
}
