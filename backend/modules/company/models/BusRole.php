<?php

namespace frontend\modules\company\models;

use Yii;
use frontend\modules\company\models\BusRoleUser;
use common\models\ProProduceUser;
/**
 * This is the model class for table "bus_role".
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $boss_id
 * @property string $code
 * @property string $name
 * @property string $memo
 * @property integer $deleted
 */
class BusRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bus_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_id', 'boss_id', 'deleted'], 'integer'],
            [['name'], 'required'],
            [['code', 'name'], 'string', 'max' => 20],
            [['memo'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Site ID',
            'boss_id' => 'Boss ID',
            'code' => 'Code',
            'name' => 'Name',
            'memo' => 'Memo',
            'deleted' => 'Deleted',
        ];
    }
    /**
     * @name 根据id获取角色
     * @return 角色信息
     */
    public function getRoles() 
    {
        return $this->hasMany(BusRoleUser::className(), ['role_id' => 'id']);
    }

    /**
     * @name 返回id=>角色名称的数组
     * @param $roleId
     * @return mixed
     */
    public function getRoleName($roleId)
    {
        $RoleName=$this->find()
            		   ->where('deleted=0')
            		   ->andWhere(['id'=>$roleId])
            		   ->asArray()
            		   ->all();
       foreach($RoleName as $rn)
       {
          $idName[$rn['id']]=$rn['name'];
       }
        if(!empty($idName)){
            ksort($idName);
        }else{
            $idName=array();
        }

       return $idName;
    }
    
    /**
     * @name 获取商户下所有用户（角色表和用户表关联）
     * @author tanbenjiang
     * @date 2015-8-18
     * 
     */
    public function getBusUser()
    {
    	return $this->hasMany(ProProduceUser::className(), ['itemid'=>'role_user'])
    				->viaTable(BusRoleUser::tableName(),['role_id'=>'id']);
    }
}
