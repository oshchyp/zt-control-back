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
     * @return array
     */
    public function stringForSearchAll();

    /**
     * @return array
     */
    public function fieldsForSearchAll();

    /**
     * @return array
     */
    public function fieldsForSearchIndividual();

    /**
     * @return array
     */
    public function fieldsForEquating();

    /**
     * @return array
     */
    public function fieldsComparisonMore();

    /**
     * @return array
     */
    public function joinRules();

    /**
     * @return mixed
     */
    public function getSortValue();

    /**
     * @return string
     */
    public function getSortField();

}