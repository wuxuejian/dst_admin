<?php
namespace backend\models;
class SystemDaemon extends \common\models\SystemDaemon
{
    public function rules()
    {
        $rules = [
            [['name', 'script_path', 'description'],'trim'],
            [['name', 'script_path', 'description'],'filter','filter'=>'htmlspecialchars'],
        ];
        return array_merge($rules,parent::rules());
    }



}