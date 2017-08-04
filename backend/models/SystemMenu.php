<?php
namespace backend\models;
class SystemMenu extends \common\models\SystemMenu
{
    public function rules()
    {
        $rules = [
            [['name','icon_class','mca', 'target_url', 'note'], 'trim'],
            [['name','icon_class','mca', 'target_url', 'note'], 'filter','filter'=>'htmlspecialchars'],
            [['pid','list_order'],'default','value'=>0]
        ];
        return array_merge($rules,parent::rules());
    }



}
