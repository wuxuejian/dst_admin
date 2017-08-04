<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_finance_company".
 *
 * @property integer $id
 * @property string $number
 * @property string $password
 * @property string $company_name
 * @property string $director_name
 * @property string $director_mobile
 * @property string $director_post
 */
class FinanceCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_finance_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'director_name'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 32],
            [['company_name'], 'string', 'max' => 255],
            [['director_mobile'], 'string', 'max' => 11],
            [['director_post'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'password' => 'Password',
            'company_name' => 'Company Name',
            'director_name' => 'Director Name',
            'director_mobile' => 'Director Mobile',
            'director_post' => 'Director Post',
        ];
    }
}
