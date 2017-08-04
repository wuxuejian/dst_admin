<?php
namespace backend\models;
class SystemTask extends \common\models\SystemTask
{
    public function rules()
    {
        $rules = [
            [['name','exec_command','exec_frequency'], 'trim'],
            [['name','exec_command','exec_frequency'], 'filter','filter'=>'htmlspecialchars']
        ];
        return array_merge($rules,parent::rules());
    }



}
