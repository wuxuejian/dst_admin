<?php

namespace frontend\modules\company\models;

use Yii;

/**
 * This is the model class for table "bus_role_user".
 *
 * @property integer $id
 * @property string $role_user
 * @property string $role_id
 */
class BusRoleUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bus_role_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id'], 'required'],
            [['role_id','role_user'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_user' => 'Role User',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * @name 获取用户角色id数组
     * @param $userInfo 用户id
     * @return array
     */
    public function getUserRole($userInfo){
        $privi = BusRoleUser::find()
            ->where('role_user=:role_user',[':role_user'=>$userInfo['itemid']])
            ->asArray()
            ->orderBy('role_id asc')
            ->all();
        foreach($privi as $rs){
            $urle[]= $rs['role_id'];
            foreach($urle as $r){
                    $usRole[] =$r;
            }
        }
        if(is_array($usRole)){
            $usRoles = array_unique($usRole);
        }else{
            $usRoles='';
        }
        if(!empty($usRoles)){
            asort($usRoles);
        }

        return $usRoles;
    }

}
