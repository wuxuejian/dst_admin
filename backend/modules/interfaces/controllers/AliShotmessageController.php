<?php
namespace backend\modules\interfaces\controllers;
use backend\models\Vip;
use yii;
use yii\web\Controller;
class AliShotmessageController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     * 配置短信类型
     */
    protected static $messageType = [
        // true account of dst
        'reg'=>['注册验证','SMS_4015126'],//注册类型验证码
        'changePWD'=>['变更验证','SMS_4015123'],//变更验证证码

    ];

    protected static $interval = 80;//短信请求间隔时间

    /**
     * 发送验证码如果用户不存在则创建
     */
    public function actionCreateUser(){
        $datas = [];
        $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        if(!$mobile || !$type){
            $datas['error'] = 1;
            $datas['msg'] = '参数不合法！';
            echo json_encode($datas);
            return false;
        }
        if(!isset(self::$messageType[$type])){
            $datas['error'] = 1;
            $datas['msg'] = '短信类型错误！';
            echo json_encode($datas);
            return false;
        }
        //检测改账号是否在interval时间内请求过短信
        $vipInfo = Vip::find()
            ->select(['id','mobile','shot_message_code','sm_reqtime','sm_number'])
            ->where(['mobile'=>$mobile])->asArray()->one();
        if(!$vipInfo){
            $vipModel = new Vip;
            $vipModel->code = '';
            $vipModel->mobile = $mobile;
            if($vipModel->validate() && $vipModel->save(false)){
                $vipInfo = $vipModel->getAttributes();
            }else{
                $datas['error'] = 1;
                $datas['msg'] = join('',array_column($vipModel->getErrors(),0));
                echo json_encode($datas);
                return false;
            }
        }
        $deadlineTime = $vipInfo['sm_reqtime'] + self::$interval;
        if($deadlineTime > time()){
            $datas['error'] = 0;
            $datas['shot_message_code'] = $vipInfo['shot_message_code'];
            $datas['sm_number'] = $vipInfo['sm_number'];
            echo json_encode($datas);
            return true;
        }
        $messageCode = str_pad(mt_rand(0,999999),'0');
        $smNumber = mt_rand(100,999);
        $saveInfo = Vip::updateAll([
            'shot_message_code'=>$messageCode,
            'sm_number'=>$smNumber,
            'sm_reqtime'=> time(),
        ],[
            'id'=>$vipInfo['id']
        ]);
        if(!$saveInfo){
            $datas['error'] = 1;
            $datas['msg'] = '发送失败！';
            echo json_encode($datas);
            return false;
        }
        //调用接口发送消息
        $params = [
            'code'=>$messageCode,
            'product'=>'[地上铁]',
        ];
        $sendInfo = self::sendMessageToUser($type,$params,$mobile);
        if(!$sendInfo->success){
            $datas['error'] = 1;
            $datas['msg'] = $sendInfo->msg;//返回接口错误
            return json_encode($datas);
        }
        $datas['error'] = 0;
        $datas['shot_message_code'] = $messageCode;
        $datas['sm_number'] = $smNumber;
        return json_encode($datas);
    }

    /**
     * 发送验证码用户必需已经存在
     */
    public function actionUserExists(){
        $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        if(!$mobile || !$type){
            $datas['error'] = 1;
            $datas['msg'] = '参数不合法！';
            return json_encode($datas);
        }
        if(!isset(self::$messageType[$type])){
            $datas['error'] = 1;
            $datas['msg'] = '短信类型错误！';
            return json_encode($datas);
        }
        //检测改账号是否在interval时间内请求过短信
        $vipInfo = Vip::find()
            ->select(['id','mobile','shot_message_code','sm_reqtime','sm_number'])
            ->where(['mobile'=>$mobile])->asArray()->one();
        if(!$vipInfo){
            $datas['error'] = 1;
            $datas['msg'] = '该账号不存在！';
            return json_encode($datas);
        }
        $deadlineTime = $vipInfo['sm_reqtime'] + self::$interval;
        if($deadlineTime > time()){
            $datas['error'] = 0;
            $datas['shot_message_code'] = $vipInfo['shot_message_code'];
            $datas['sm_number'] = $vipInfo['sm_number'];
            return json_encode($datas);
        }
        $messageCode = str_pad(mt_rand(0,999999),'0');
        $smNumber = mt_rand(100,999);
        $saveInfo = Vip::updateAll([
            'shot_message_code'=>$messageCode,
            'sm_number'=>$smNumber,
            'sm_reqtime'=> time(),
        ],[
            'id'=>$vipInfo['id']
        ]);
        if(!$saveInfo){
            $datas['error'] = 1;
            $datas['msg'] = '发送失败！';
            return json_encode($datas);
        }
        //调用接口发送消息
        $params = [
            'code'=>$messageCode,
            'product'=>'[地上铁]',
        ];
        $sendInfo = self::sendMessageToUser($type,$params,$mobile);
        if(!$sendInfo->success){
            $datas['error'] = 1;
            $datas['msg'] = $sendInfo->msg;//返回接口错误
            return json_encode($datas);
        }
        $datas['error'] = 0;
        $datas['shot_message_code'] = $messageCode;
        $datas['sm_number'] = $smNumber;
        return json_encode($datas);
    }

    /**
     * 调用接口发送消息
     * @param string $type         消息类型
     * @param array  $params       模块参数（key=>value）
     * @param string $telephone    短信接收号码
     */
    protected static function sendMessageToUser($type,$params,$telephone){
        $aliDaYuDir = dirname(dirname(getcwd())).'/extension/taobao-sdk-PHP-shot-message';
        include_once($aliDaYuDir.'/TopSdk.php');
        $messageType = self::$messageType;
        $c = new \TopClient;
        // true account of dst
        $c->appkey = '23318373';
        $c->secretKey = 'ac1303f029af0aa1dcbf1e0209a49ec2';
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        //$req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($messageType[$type][0]);
        $req->setSmsParam(json_encode($params));
        $req->setRecNum($telephone);
        $req->setSmsTemplateCode($messageType[$type][1]);
        return $resp = $c->execute($req)->result;
    }

}