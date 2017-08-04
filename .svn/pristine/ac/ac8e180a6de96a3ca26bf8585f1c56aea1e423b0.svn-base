<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%battery}}".
 *
 * @property string $id
 * @property string $battery_model
 * @property string $battery_type
 * @property string $connection_type
 * @property string $battery_maker
 * @property string $battery_spec
 * @property double $system_voltage
 * @property double $system_capacity
 * @property double $system_power
 * @property string $system_nums
 * @property double $single_voltage
 * @property double $single_capacity
 * @property double $module_capacity
 * @property string $module_nums
 * @property string $create_time
 * @property string $creator_id
 * @property integer $is_del
 */
class Battery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%battery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['system_voltage', 'system_capacity', 'system_power', 'single_voltage', 'single_capacity', 'module_capacity'], 'number'],
            [['system_nums', 'module_nums', 'creator_id', 'is_del'], 'integer'],
            [['create_time'], 'safe'],
            [['battery_model', 'battery_type', 'connection_type', 'battery_maker', 'battery_spec'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，电池表',
            'battery_model' => '电池型号',
            'battery_type' => '电池类型',
            'connection_type' => '连接类型:国标、比亚迪',
            'battery_maker' => '电池生产厂家',
            'battery_spec' => '电池规格',
            'system_voltage' => '电池系统额定电压',
            'system_capacity' => '电池系统额定容量',
            'system_power' => '电池系统额定电能',
            'system_nums' => '电池系统电池串联数量',
            'single_voltage' => '单体电池额定电压',
            'single_capacity' => '单体电池额定容量',
            'module_capacity' => '电池模块容量',
            'module_nums' => '电池模块数量',
            'create_time' => '创建时间',
            'creator_id' => '创建人员id',
            'is_del' => '是否已删除',
        ];
    }
}
