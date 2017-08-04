<?php
/**
 * @Desc:   会员app登录 
 * @author: wangmin
 * @date:   2016-01-05 10:53
 */
namespace backend\models;
class VipAppLogin extends \common\models\VipAppLogin{
    /**
     * 登录指定账号
     * @param array $type 类型（支持id/mobile）
     * @param mixed $val  类型对应的值
     */
    public static function login($type = 'id',$val){
        switch ($type) {
            case 'id':
                $vipId = $val;
                break;
            case 'mobile':
                $vipInfo = Vip::find()->select(['id'])
                    ->where(['mobile'=>$val])
                    ->asArray()->one();
                if(!$vipInfo){
                    return [];
                }
                $vipId = $vipInfo['id'];
                break;
        }
        $model = self::findOne(['vip_id'=>$vipId]);
        if(!$model){
            $model = new VipAppLogin();
        }
        $model->vip_id = $vipId;
        $model->key = md5(uniqid(true));
        $model->key_ctime = time();
        $model->key_etime = time() + 86400 * 15;
        if($model->save(false)){
            $record = $model->getAttributes();
            unset($record['id']);
            unset($record['vip_id']);
            return $record;
        }else{
            return [];
        }
    }

    /**
     * 检测app用户是否已经登录
     * @param array  $type 类型（支持id/mobile）
     * @param mixed  $val  类型对应的值
     * @param string $key  登录key码
     */
    public static function checkLogin($type = 'id',$val,$key){
        switch ($type) {
            case 'id':
                $vipId = $val;
                break;
            case 'mobile':
                $vipInfo = Vip::find()->select(['id'])
                    ->where(['mobile'=>$val])
                    ->asArray()->one();
                if(!$vipInfo){
                    return false;
                }
                $vipId = $vipInfo['id'];
                break;
        }
        $model = self::findOne(['vip_id'=>$vipId]);
        if(!$model){
            return false;
        }
        if($model->getOldAttribute('key') == $key && $model->getOldAttribute('key_etime') > time()){
            return true;
        }
        return false;
    }
}