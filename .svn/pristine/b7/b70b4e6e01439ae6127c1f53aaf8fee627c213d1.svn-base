<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_station}}".
 *
 * @property string $cs_id
 * @property string $cs_code
 * @property string $cs_name
 * @property string $cs_type
 * @property string $cs_status
 * @property string $cs_address
 * @property string $cs_address_province
 * @property string $cs_address_city
 * @property string $cs_address_area
 * @property string $cs_address_street
 * @property string $cs_lng
 * @property string $cs_lat
 * @property string $cs_fm_id
 * @property integer $cs_is_open
 * @property string $cs_commissioning_date
 * @property string $cs_building_user
 * @property string $cs_manager_name
 * @property string $cs_manager_mobile
 * @property string $cs_service_telephone
 * @property resource $cs_opentime
 * @property resource $cs_powerrate
 * @property double $cs_servicefee
 * @property resource $cs_parkingfee
 * @property string $cs_mark
 * @property string $cs_pic_path
 * @property string $cs_create_time
 * @property string $cs_creator_id
 * @property string $app_tips
 * @property integer $cs_is_del
 */
class ChargeStation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_station}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cs_fm_id', 'cs_is_open', 'cs_creator_id', 'cs_is_del', 'spots_fast_num', 'spots_slow_num','province_id','city_id','area_id'], 'integer'],
            [['cs_commissioning_date', 'cs_create_time'], 'safe'],
            [['cs_opentime', 'cs_powerrate', 'cs_parkingfee'], 'string'],
            [['cs_servicefee'], 'number'],
            [['cs_code', 'cs_name'], 'string', 'max' => 50],
            [['cs_type', 'cs_status', 'cs_building_user', 'spots_connection_type'], 'string', 'max' => 30],
            [['cs_address'], 'string', 'max' => 100],
            [['cs_address_province', 'cs_address_city', 'cs_address_area', 'cs_lng', 'cs_lat', 'cs_manager_name', 'cs_service_telephone'], 'string', 'max' => 20],
            [['cs_address_street'], 'string', 'max' => 40],
            [['cs_manager_mobile'], 'string', 'max' => 11],
            [['cs_mark', 'app_tips'], 'string', 'max' => 255],
            [['cs_pic_path'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cs_id' => '主键，充电站表',
            'cs_code' => '电站编号',
            'cs_name' => '电站名称',
            'cs_type' => '电站类型：自营、联营',
            'cs_status' => '电站状态：正常、在建、停用',
            'cs_address' => '电站详细地址',
            'cs_address_province' => '电站详细地址-省份',
            'cs_address_city' => '电站详细地址-城市',
            'cs_address_area' => '电站详细地址-区县',
            'cs_address_street' => '电站详细地址-街道',
            'cs_lng' => '电站经度',
            'cs_lat' => '电站纬度',
            'cs_fm_id' => '所属前置机id（cs_charge_frontmachine.id）',
            'cs_is_open' => '是否开放',
            'cs_commissioning_date' => '投运日期',
            'cs_building_user' => '使用单位',
            'cs_manager_name' => '负责人姓名',
            'cs_manager_mobile' => '负责人手机',
            'cs_service_telephone' => '服务电话',
            'cs_opentime' => '开放时间',
            'cs_powerrate' => '电费费率',
            'cs_servicefee' => '服务费',
            'cs_parkingfee' => '停车费',
            'cs_mark' => '电站备注',
            'cs_pic_path' => '照片路径',
            'cs_create_time' => '创建时间',
            'cs_creator_id' => '创建人id',
            'app_tips' => 'App Tips',
            'cs_is_del' => '是否已删除',
        ];
    }
}
