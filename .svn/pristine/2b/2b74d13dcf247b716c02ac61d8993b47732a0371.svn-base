<?php

namespace frontend\modules\company\models;

use Yii;
use common\models\Department;
use common\models\ProProduceUser;
use common\models\BusCompany;

/**
 * @name 商户选择人员模型
 * @author tanbenjiang
 * @date 2015-8-22
 */
class SelectMan extends Department
{
	/**
	 * @name 获取全部门店
	 * @author tanbenjiang
	 * @date 2015-8-22
	 * 
	 */	
	public function getBossInfo($bossId='', $search='')
	{
		$bossId = $bossId!='' ? $bossId : '';
		$bossInfo = BusCompany::find()->select(['id','bus_type','bus_sn','company_name'])
									  ->where(['deleted'=>0])
									  ->andFilterWhere(['id'=>$bossId])
									  ->andFilterWhere(['like','company_name',$search])
									  ->asArray()
									  ->all();
		
		return $bossInfo;
	}
	
	/**
	 * @name 获取商户下的所有部门
	 * @param integer $bossId 商户ID
	 * @author tanbenjiang
	 * @date 2015-8-21
	 * 
	 */
	public function getDepartment($bossId)
	{
		$department = Department::find()->with(['produceUser'])
										->where(['deleted'=>0])
										->andFilterWhere(['boss_id'=>$bossId])
										->asArray()
										->all();
		
		return $department;
	}
	
	/**
	 * @name 获取商户下的所有成员
	 * @param interger $bossId 商户ID
	 * @author tanbenjiang
	 * @date 2015-8-21
	 * 
	 */
	public function getUser($bossId)
	{
		$user = ProProduceUser::find()->select(['itemid','boss_id','part_id','worker_name','user_name'])
									  ->where(['status_lock'=>0, 'deleted'=>0])
									  ->andFilterWhere(['boss_id'=>$bossId])
									  ->asArray()
									  ->all();
		return $user;
	}
	
	/**
	 * @name 获取商户下的所有角色
	 * @param integer $bossId 商户ID
	 * @author tanbenjiang
	 * @date 2015-8-21
	 * 
	 */
	public function getRole($bossId)
	{
		$role = BusRole::find()->with(['busUser'])
							   ->where(['deleted'=>0])
							   ->andFilterWhere(['boss_id'=>$bossId])
							   ->asArray()
							   ->all();
		
		return $role;
	}
}
