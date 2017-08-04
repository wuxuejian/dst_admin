<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_spots}}".
 *
 * @property string $id
 * @property string $station_id
 * @property string $code_from_compony
 * @property string $code_from_factory
 * @property string $model
 * @property string $charge_type
 * @property string $charge_pattern
 * @property string $connection_type
 * @property double $wire_length
 * @property integer $charge_gun_nums
 * @property double $rated_output_voltage
 * @property double $rated_output_current
 * @property double $rated_output_power
 * @property string $manufacturer
 * @property string $purchase_date
 * @property string $install_date
 * @property string $install_type
 * @property string $install_site
 * @property string $lng
 * @property string $lat
 * @property string $status
 * @property string $mark
 * @property integer $systime
 * @property string $sysuser
 * @property integer $is_del
 * @property string $fm_id
 * @property string $logic_addr
 */
class ChargeSpots extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_spots}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_id', 'charge_gun_nums', 'systime', 'is_del', 'fm_id'], 'integer'],
            [['wire_length', 'rated_output_voltage', 'rated_output_current', 'rated_output_power'], 'number'],
            [['purchase_date', 'install_date'], 'safe'],
            [['code_from_compony', 'code_from_factory', 'manufacturer', 'sysuser','sim'], 'string', 'max' => 50],
            [['model', 'charge_type', 'charge_pattern', 'connection_type'], 'string', 'max' => 30],
            [['install_type', 'lng', 'lat', 'status'], 'string', 'max' => 20],
            [['install_site', 'logic_addr'], 'string', 'max' => 100],
            [['mark'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，充电桩表',
            'station_id' => '该电桩所属充电站ID（cs_charge_station.cs_id）',
            'code_from_compony' => '公司内部对电桩的编号（电桩编号）',
            'code_from_factory' => '生产厂家对电桩的编号（出厂编号）',
            'model' => '电桩型号:MODEL_A、MODEL_B之类',
            'charge_type' => '电桩类型:直流桩、交流桩等',
            'charge_pattern' => '充电模式:快充、慢充等',
            'connection_type' => '连接方式:国标、比亚迪等（协议类型）',
            'wire_length' => '线长',
            'charge_gun_nums' => '充电枪个数',
            'rated_output_voltage' => '额定输出电压',
            'rated_output_current' => '额定输出电流',
            'rated_output_power' => '额定输出功率',
            'manufacturer' => '生产厂家:科陆电子、中兴通讯等',
            'purchase_date' => '购置日期',
            'install_date' => '安装日期',
            'install_type' => '安装方式:立式、壁挂式等',
            'install_site' => '安装地点',
            'lng' => '经度',
            'lat' => '纬度',
            'status' => '电桩状态:待机、充电、故障、禁用、离线',
            'mark' => '备注',
            'systime' => '系统时间',
            'sysuser' => '操作人员',
            'is_del' => '是否已删除',
            'fm_id' => '所属前置机id（cs_charge_frontmachine）',
            'logic_addr' => '电桩逻辑地址（独一无二的）',
        ];
    }
}
