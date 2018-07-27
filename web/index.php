<?php

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

//try {
//
//$dbh = new PDO('mysql:host=localhost;dbname=ztsys_crm','mcore_db', 'E8c0J3r9');
//
//
//    $q = $dbh->query('SELECT * FROM users WHERE id = "' . $_GET['id'].'"');
//    foreach($dbh->query('SELECT * FROM users WHERE id = "' . $_GET['id'].'"') as $row) {
//        print_r($row);
//    }
//} catch (PDOException $e) {
//
//    print "Error!: " . $e->getMessage() . "<br/>";
//    die();
//
//}




// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

if (YII_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__.'/../config/web.php';



(new yii\web\Application($config))->run();
