<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 19.09.2018
 * Time: 10:48
 */

namespace app\models\filter;


interface ModelFilterInterface
{

    /**
     * @return mixed
     */
    public function stringForSearchAll();

    /**
     * @return mixed
     */
    public function fieldsForSearchAll();

    /**
     * @return mixed
     */
    public function fieldsForSearchIndividual();

    /**
     * @return mixed
     */
    public function fieldsForEquating();

    /**
     * @return mixed
     */
    public function joinRules();

}