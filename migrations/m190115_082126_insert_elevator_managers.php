<?php

use yii\db\Migration;

/**
 * Class m190115_082126_insert_elevator_managers
 */
class m190115_082126_insert_elevator_managers extends Migration
{

//Сарата:
//Георгиев Руслан Ильич 0671582179
//Мартынов Игорь Васильевич 0639963164 (админ)
//Мартынов Иван Геннадьевич 0967080370
//Татаров Владислав Олегович 0979077953
//
//Хмельник:
//Пройчев Александр Анатольевич 0675190684
//Ткачук Павел Петрович 0969878284
//Нестеренко Алексей Владимирович 0977142787 (админ)
//Куруч Сергей Ильич 0673701877
//Борисюк Олег Петрович 0971752959


    public function managers (){
        return [
            [
                'firstName' => 'Александр Анатольевич',
                'lastName' => 'Пройчев',
                'phone' => '0675190684',
                'type' => '2',
                'elevatorBit' => 4,
                'elevatorViewBit' => 4,
                'uid' => ''
            ],
            [
                'firstName' => 'Павел Петрович',
                'lastName' => 'Ткачук',
                'phone' => '0969878284',
                'type' => '2',
                'elevatorBit' => 4,
                'elevatorViewBit' => 4,
                'uid' => ''
            ],
            [
                'firstName' => 'Алексей Владимирович',
                'lastName' => 'Нестеренко',
                'phone' => '0977142787',
                'type' => '2',
                'elevatorBit' => 4,
                'elevatorViewBit' => 6,
                'uid' => ''
            ],
            [
                'firstName' => 'Сергей Ильич',
                'lastName' => 'Куруч',
                'phone' => '0673701877',
                'type' => '2',
                'elevatorBit' => 4,
                'elevatorViewBit' => 4,
                'uid' => ''
            ],
            [
                'firstName' => 'Олег Петрович',
                'lastName' => 'Борисюк',
                'phone' => '0971752959',
                'type' => '2',
                'elevatorBit' => 4,
                'elevatorViewBit' => 4,
                'uid' => ''
            ],
            [
                'firstName' => 'Руслан Ильич',
                'lastName' => 'Георгиев',
                'phone' => '0671582179',
                'type' => '2',
                'elevatorBit' => 2,
                'elevatorViewBit' => 2,
                'uid' => ''
            ],
            [
                'firstName' => 'Игорь Васильевич',
                'lastName' => 'Мартынов',
                'phone' => '0639963164',
                'type' => '2',
                'elevatorBit' => 2,
                'elevatorViewBit' => 6,
                'uid' => ''
            ],
            [
                'firstName' => 'Иван Геннадьевич',
                'lastName' => 'Мартынов',
                'phone' => '0967080370',
                'type' => '2',
                'elevatorBit' => 2,
                'elevatorViewBit' => 2,
                'uid' => ''
            ],
            [
                'firstName' => 'Владислав Олегович',
                'lastName' => 'Татаров',
                'phone' => '0979077953',
                'type' => '2',
                'elevatorBit' => 2,
                'elevatorViewBit' => 2,
                'uid' => ''
            ],
        ];
    }

    public function managersConvert (){
        $result = $this->managers();
        foreach ($result as $k=>$item){
            $result[$k]['uid'] = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
            $result[$k]['phone'] = '38'.$item['phone'];
        }
        return $result;
    }

    public function mangersPhones(){
        $phones = [];
        foreach ($this->managers() as $item){
            $phones[]=$item['phone'];
        }
        return $phones;
    }


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       foreach ($this -> managersConvert () as $item){
           $userExist = (new \yii\db\Query())
               ->from('users')
               ->where(['phone' => $item['phone']])
               ->count();
           if (!$userExist){
               $this->insert('users',$item);
               $lastID = Yii::$app->db->getLastInsertID();

               Yii::$app->authManager->db->createCommand()
                   ->delete(Yii::$app->authManager->assignmentTable, ['user_id' => $lastID])
                   ->execute();

               $roleObj = Yii::$app->authManager->createRole('firms');
               Yii::$app->authManager->assign($roleObj, $lastID);
               $roleObj = Yii::$app->authManager->createRole('farms');
               Yii::$app->authManager->assign($roleObj, $lastID);
           }
       }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       foreach ($this->mangersPhones() as $phone){
           $phone = '38'.$phone;
           $userInfo = (new \yii\db\Query())
               ->from('users')
               ->where(['phone' => $phone])
               ->one();

           if ($userInfo){
               $this->delete('users',['phone'=>$phone]);
               Yii::$app->authManager->db->createCommand()
                   ->delete(Yii::$app->authManager->assignmentTable, ['user_id' => $userInfo['id']])
                   ->execute();
           }

       }
    }


}
