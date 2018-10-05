<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.10.2018
 * Time: 10:38
 */

namespace app\modules\api\models\traits;


use app\models\ActiveRecord;
use yii\db\ActiveQuery;

trait CRUD
{

    /**
     * @var ActiveRecord|string className
     */
    public $resourceClassName;

    /**
     * @var ActiveRecord
     */
    public $resourceObject;

    /**
     * @var ActiveQuery
     */
    public $query;

  //  public function set

}