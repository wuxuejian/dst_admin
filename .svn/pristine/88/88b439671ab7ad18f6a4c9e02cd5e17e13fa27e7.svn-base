<?php
/**
 * 电池修正通知 模型
 */
namespace backend\models;
class BatteryCorrectNotice extends \common\models\BatteryCorrectNotice
{
    public function rules()
    {
        $rules = [
            [['notice_sender','contact_name','mark','correct_res'],'trim'],
            [['notice_sender','contact_name','mark','correct_res'],'filter','filter'=>'htmlspecialchars'],
            [['is_corrected'], 'default', 'value'=>0],
        ];
        return array_merge($rules,parent::rules());
    }

    /**
     * 关联【人员】表
     */
    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'modify_aid'
        ]);
    }










}