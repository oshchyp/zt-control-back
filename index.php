<?php


// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

if (YII_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__.'/config/web.php';

function dump($var, $kill = false)
{
    if (YII_DEBUG) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        if ($kill) {
            die();
        }
    }
}

//$t = shell_exec('sudo composer require phpoffice/phpspreadsheet');
//dump($t,1);

(new yii\web\Application($config))->run();
