<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vehicle}}".
 *
 * @property string $id
 * @property integer $vip_id
 * @property string $vehicle
 * @property string $vhc_model
 * @property string $vhc_con_type
 * @property string $mark
 * @property integer $is_del
 */
class Vehicle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vehicle}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'is_del'], 'integer'],
            [['vehicle', 'vhc_con_type'], 'string', 'max' => 20],
            [['vhc_model'], 'string', 'max' => 50],
            [['mark'], 'string', 'max' => 200],
            [['vehicle'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '车辆ID（会员的车）',
            'vip_id' => '所属会员的ID',
            'vehicle' => '车牌号',
            'vhc_model' => '车型',
            'vhc_con_type' => '充电连接方式',
            'mark' => '备注',
            'is_del' => '是否已删除',
        ];
    }
}
