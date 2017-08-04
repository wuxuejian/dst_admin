<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_back".
 *
 * @property integer $id
 * @property integer $state
 * @property string $number
 * @property integer $c_customer_id
 * @property integer $p_customer_id
 * @property string $other_customer_name
 * @property string $customer_tel
 * @property string $oper_user1
 * @property string $append_url1
 * @property string $customer_addr
 * @property string $car_ids
 * @property string $back_cause
 * @property string $back_time
 * @property integer $break_contract_type
 * @property string $contract_time
 * @property string $break_contract_money
 * @property string $cancel_back_cause
 * @property string $oper_user2
 * @property integer $is_reject
 * @property string $reject_cause
 * @property string $oper_user3
 * @property string $wz_text
 * @property string $damage_money
 * @property integer $repair_type
 * @property string $back_time2
 * @property string $append_url2
 * @property string $oper_user4
 * @property string $penalty_money
 * @property string $foregift_money
 * @property string $back_money
 * @property string $back_time3
 * @property string $oper_user5
 * @property string $append_url3
 * @property string $append_url4
 * @property string $note
 * @property string $oper_user6
 * @property string $storage_car_ids
 * @property string $oper_user7
 * @property string $oper_time1
 * @property string $oper_time2
 * @property string $oper_time3
 * @property string $oper_time4
 * @property string $oper_time5
 * @property string $oper_time6
 * @property string $oper_time7
 */
class CarBack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_back';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state', 'c_customer_id', 'p_customer_id', 'break_contract_type', 'is_reject', 'repair_type'], 'integer'],
            [['car_ids', 'wz_text'], 'string'],
            [['oper_time1', 'oper_time2', 'oper_time3', 'oper_time4', 'oper_time5', 'oper_time6', 'oper_time7'], 'safe'],
            [['number', 'other_customer_name', 'oper_user1', 'oper_user2', 'oper_user3', 'oper_user4', 'oper_user5', 'oper_user6', 'oper_user7'], 'string', 'max' => 50],
            [['customer_tel'], 'string', 'max' => 30],
            [['append_url1', 'customer_addr', 'back_cause', 'cancel_back_cause', 'reject_cause', 'append_url2', 'append_url3', 'append_url4'], 'string', 'max' => 100],
            [['back_time', 'contract_time', 'back_time2', 'back_time3'], 'string', 'max' => 25],
            [['break_contract_money', 'damage_money', 'penalty_money', 'foregift_money', 'back_money'], 'string', 'max' => 10],
            [['note', 'storage_car_ids'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'State',
            'number' => 'Number',
            'c_customer_id' => 'C Customer ID',
            'p_customer_id' => 'P Customer ID',
            'other_customer_name' => 'Other Customer Name',
            'customer_tel' => 'Customer Tel',
            'oper_user1' => 'Oper User1',
            'append_url1' => 'Append Url1',
            'customer_addr' => 'Customer Addr',
            'car_ids' => 'Car Ids',
            'back_cause' => 'Back Cause',
            'back_time' => 'Back Time',
            'break_contract_type' => 'Break Contract Type',
            'contract_time' => 'Contract Time',
            'break_contract_money' => 'Break Contract Money',
            'cancel_back_cause' => 'Cancel Back Cause',
            'oper_user2' => 'Oper User2',
            'is_reject' => 'Is Reject',
            'reject_cause' => 'Reject Cause',
            'oper_user3' => 'Oper User3',
            'wz_text' => 'Wz Text',
            'damage_money' => 'Damage Money',
            'repair_type' => 'Repair Type',
            'back_time2' => 'Back Time2',
            'append_url2' => 'Append Url2',
            'oper_user4' => 'Oper User4',
            'penalty_money' => 'Penalty Money',
            'foregift_money' => 'Foregift Money',
            'back_money' => 'Back Money',
            'back_time3' => 'Back Time3',
            'oper_user5' => 'Oper User5',
            'append_url3' => 'Append Url3',
            'append_url4' => 'Append Url4',
            'note' => 'Note',
            'oper_user6' => 'Oper User6',
            'storage_car_ids' => 'Storage Car Ids',
            'oper_user7' => 'Oper User7',
            'oper_time1' => 'Oper Time1',
            'oper_time2' => 'Oper Time2',
            'oper_time3' => 'Oper Time3',
            'oper_time4' => 'Oper Time4',
            'oper_time5' => 'Oper Time5',
            'oper_time6' => 'Oper Time6',
            'oper_time7' => 'Oper Time7',
        ];
    }
    
    /*
     * 检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
    * 注：车辆基本信息列表、车辆实时数据监控列表、选择车辆combogrid等使用。
    * @$isStrictlyLimited: 是否严格按所属运营公司来限制数据的显示。
    * 当参数为false时，地上铁人员也能看其他运营公司车辆数据，如车辆基本信息列表；
    * 当参数为true时，包括地上铁在内各运营公司都只能看自己的数据，如选择车辆combogrid。
    */
    public static function isLimitedToShowByAdminOperatingCompany($isStrictlyLimited=false){
    	$arr = [];
    	//显示当前登录用户对应运营公司的车辆-20160325
    	$super = $_SESSION['backend']['adminInfo']['super'];
    	$username = $_SESSION['backend']['adminInfo']['username'];
    	//除了开发人员和超级管理员不受管控外，其他人必须检测
    	if(!$super && $username != 'administrator'){
    		$adminInfo_operatingCompanyIds = $_SESSION['backend']['adminInfo']['operating_company_ids'];
    		if($isStrictlyLimited){
    			$arr = ['status'=>true,'adminInfo_operatingCompanyId'=>$adminInfo_operatingCompanyIds];
    		}else{
    			//除了地上铁人员外，只能查看匹配运营公司的车辆
    			if(!$adminInfo_operatingCompanyIds){
    				$adminInfo_operatingCompanyIds = 10000;
    			}
    			$arr = ['status'=>true,'adminInfo_operatingCompanyId'=>$adminInfo_operatingCompanyIds];
    		}
    	}
    	return $arr;
    }
}
