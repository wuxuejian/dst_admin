<?php
/*
 * 前置机管理 模型
 */
namespace backend\models;
class ChargeFrontmachine extends \common\models\ChargeFrontmachine{

    public function rules()
    {
        $rules =  [
            //去两端空格
            [['addr','port','access_level','password','register_number','db_username','db_password','db_port','db_name','note'],
                'filter','filter'=>'trim'
            ],
            //防注入
            [['addr','port','access_level','password','register_number','db_username','db_password','db_port','db_name','note'],
                'filter','filter'=>'htmlspecialchars'
            ],
        ];
        return array_merge($rules,parent::rules());
    }


    /**
     * 获取第一条有效数据作为默认的前置机ID（电桩监控模块使用）
     */
    public static function getDefaultFrontMachineId(){
        $row = ChargeFrontmachine::find()
            ->select(['id'])
            ->where(['is_del'=>0])
            ->orderBy('id ASC')
            ->asArray()->one();
        if (!empty($row)) {
            return $row['id'];
        } else {
            return 0;
        }
    }


    /**
     * 根据前置机id链接前置机
     */
    public static function connect($id = 1){
        $fmInfo = self::find()->select([
                'addr','db_username','db_password','db_port','db_name'
            ])->where(['id'=>intval($id)])
            ->asArray()->one();
        if(!$fmInfo){
            return [false,'没有前置机数据！'];
        }
        try {
            $fmConnection = new \yii\db\Connection([
                'dsn' => "mysql:host={$fmInfo['addr']};port={$fmInfo['db_port']};dbname={$fmInfo['db_name']}",
                'username' => $fmInfo['db_username'],
                'password' => $fmInfo['db_password'],
                'charset' => 'utf8',
                //'tablePrefix' => $tablePrefix
            ]);
            return [true,$fmConnection];
        } catch (Exception $e) {
            return [false,'前置机数据库连接异常！'];
        }
    }
}