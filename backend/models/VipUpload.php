<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%vip_upload}}".
 *
 * @property string $id
 * @property string $main_type
 * @property string $sub_type
 * @property string $file_path
 * @property integer $vip_id
 * @property integer $upload_time
 */
class VipUpload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_upload}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'upload_time'], 'integer'],
            [['main_type', 'sub_type'], 'string', 'max' => 30],
            [['file_path'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，会员上传文件表',
            'main_type' => '文件主类型（是文件mime主类型：image/text/application等）',
            'sub_type' => '子类型（是上传时自定义的存放目录名称，如image可分可为头像、驾照等）',
            'file_path' => '文件存储路径',
            'vip_id' => '上传会员ID',
            'upload_time' => '系统时间',
        ];
    }
}
