<?php
/**
 * @Desc:	会员通知 模型 
 * @author: chengwk
 * @date:	2015-12-05
 */
namespace backend\models;
use yii\db\ActiveRecord;

class VipNotice extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%vip_notice}}';
    }

    public function rules()
    {
        return [
            [['vn_content'], 'string'],
            [['vn_public_time'], 'safe'],
            [['vn_systime', 'vn_sysuser_id', 'vn_is_del'], 'integer'],
            [['vn_code', 'vn_type'], 'string', 'max' => 30],
            [['vn_title'], 'string', 'max' => 100],
            [['vn_icon_path', 'vn_hyperlink'], 'string', 'max' => 150],
            [['vn_mark'], 'string', 'max' => 200],
            [['vn_sysuser'], 'string', 'max' => 50]
        ];
    }
    
	

	
}
