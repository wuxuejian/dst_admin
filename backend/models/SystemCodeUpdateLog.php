<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_system_code_update_log".
 *
 * @property integer $id
 * @property integer $product
 * @property integer $update_type
 * @property string $update_date
 * @property string $version_number
 * @property string $module
 * @property string $note
 * @property string $oper_user
 * @property string $update_title
 */
class SystemCodeUpdateLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_system_code_update_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product', 'update_type'], 'integer'],
            [['update_date'], 'safe'],
            [['version_number'], 'string', 'max' => 10],
            [['module', 'oper_user'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 500],
            [['update_title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product' => 'Product',
            'update_type' => 'Update Type',
            'update_date' => 'Update Date',
            'version_number' => 'Version Number',
            'module' => 'Module',
            'note' => 'Note',
            'oper_user' => 'Oper User',
            'update_title' => 'Update Title',
        ];
    }
}
