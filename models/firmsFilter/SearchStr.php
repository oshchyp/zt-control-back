<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.08.2018
 * Time: 13:21
 */

namespace app\models\firmsFilter;


use app\models\Posts;

class SearchStr extends \app\models\filter\SearchStr
{

   use CommonTrait;

   public function directorFilter(){

           $this->query
               -> leftJoin('firmContacts','firms.uid = firmContacts.firmUID')
               -> leftJoin('contacts','contacts.uid = firmContacts.contactUID AND contacts.postID IN ('.implode(Posts::findDirectorsID()).')');

           $method = $this->q === 0 ? 'andWhere' : 'orWhere';
       $this->query-> $method(['like','contacts.name',$this->search]);

       $this->q++;
   }

   public function regionFilter(){

   }

   public function pointFilter(){

   }

}