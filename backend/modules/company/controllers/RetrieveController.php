<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/7/24
 * Time: 10:25
 */

namespace frontend\modules\company\controllers;

use Yii;
use common\models\ProProduceUser;
use common\models\SysSms;



class RetrieveController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actions()
    {
        return  [
            'captcha' => [
                'class' => 'backend\classes\MyCaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'backColor'=>0x000000,//背景颜色
                'maxLength' => 6, //最大显示个数
                'minLength' => 5,//最少显示个数
                'padding' => 5,//间距
                'height'=>40,//高度
                'width' => 130,  //宽度
                'foreColor'=>0xffffff, //字体颜色
                'offset'=>5, //设置字符偏移量 有效果
            ]
        ];
    }

    public function actionIndex(){
        $datas['urls'] = array(
            'verify' => \Yii::$app->urlManager->createUrl('company/retrieve/verify'),
            'show2' => \Yii::$app->urlManager->createUrl('company/retrieve/show-verify'),

        );

        return $this->renderPartial('index',['s'=>1,'datas'=>$datas]);
    }

    public function actionSendCode(){
        $mobile = Yii::$app->request->post('mobile');
        $session = Yii::$app->session;
        $session->open();
        $users = new ProProduceUser();
        $user =  $users->find()->where(['mobile'=>$mobile])->one();
        $userId = $user->itemid;
        $code = rand(100000,999999);
        $user->password_reset_token = md5($code.'haofeng');
        $user->verify_time = time()+60;
        if($user->save()){
            $response = ['success'=>true,'message'=>$code];
        }else{
            $response = ['success'=>false,'message'=>''];
        }
        //调用短信接口发送随机码;

        $message = new SysSms();
        $content = "验证码为".$code."（切勿告知他人）";
        $fromId = $userId;
        $toId = $userId;
        $have = 0;
        $type = 3;
        $message->RealTimeSend($mobile,$content,$fromId,$toId,$have,$type);
        echo json_encode($response);
        exit;
    }
    /**
     * 手机是否绑定
     */
    public function actionVerify(){
        $data = Yii::$app->request->post();

        // 获取Yii自带的验证码
        $session = Yii::$app->session;
        $session->open();
        $verifiCode = $session['__captcha/company/retrieve/captcha'];
        // 校验验证码是否正确
        if($verifiCode!==strtolower($data['code']))
        {
            $response = ['success'=>false,'message'=>'验证码不正确！'];
            echo json_encode($response);
            exit;
        }

        $users = new ProProduceUser();
        $user =  $users->find()->where('status_lock = 0 and deleted = 0 and mobile = :mobile',[':mobile'=>$data['mobile']])->one();
        if($user){
            $num= $user->itemid;
            $mobile= $user->mobile;
            $k =  base64_encode('haofeng'.$mobile.$num);
            $response = ['success'=>true,'mobile'=>$k];
            echo  json_encode($response);
            exit;
        }else{
            $response = ['success'=>false,'message'=>'电话未绑定，请联系管理员！'];
            echo  json_encode($response);
            exit;
        }

    }

    public function actionShowVerify(){

        $datas['urls'] = array(
            'pass' => \Yii::$app->urlManager->createUrl('company/retrieve/set-pass'),
            'show3' => \Yii::$app->urlManager->createUrl('company/retrieve/show-update'),
            'email' => \Yii::$app->urlManager->createUrl('company/retrieve/send-email'),
        );

        $data = Yii::$app->request->get('k');
        $str = base64_decode($data);
        $id = substr($str,18);
        $mobiles = substr($str,7,11);
        $users = new ProProduceUser();
        $user  = $users->find()->where(['itemid'=>$id,'mobile'=>$mobiles])->one();
        if($user){
            $username = $user->worker_name;
            $mobile = substr_replace($mobiles,'****',3,4);
            $email =  $user->email;
            $email2 = $email;
            $length= strpos($email,"@");
            $str1 = substr($email,0,1);
            $str2 = substr($email,$length-1);
            $email = $str1.'*****'.$str2;
            return $this->renderPartial('index',['s'=>2,'mobile'=>$mobile,'mobile2'=>$mobiles,'username'=>$username,'email'=>$email,'email2'=>$email2,'datas'=>$datas]);
        }else{

        }


    }
   //验证随机码
    public function actionSetPass(){
        $data = Yii::$app->request->post();
        //验证随机码
        $users = new ProProduceUser();
        $code = md5($data['code'].'haofeng');
        $now_time =
        $user =  $users->find()->where(['mobile'=>$data['mobile'],'worker_name'=>$data['username'],'password_reset_token'=>$code])->one();
        if($user){
            $id = $user->itemid;
        //修改密码界面
            $k =  base64_encode('haofeng'.$id);
            $response = ['success'=>true,'k'=>$k];
            echo  json_encode($response);
            //return $this->renderPartial('index',['s'=>3]);
        }else{
            $response = ['success'=>false,'message'=>''];
            echo  json_encode($response);
        }



    }
    public function actionShowUpdate(){
        $datas['urls'] = array(
            'update' => \Yii::$app->urlManager->createUrl('company/retrieve/update-pass'),
            'show4' => \Yii::$app->urlManager->createUrl('company/retrieve/show-success'),

        );

        $data = Yii::$app->request->get();
        if($data['t']){
            $now = time();
            $strt= base64_decode($data['t']);
            $oldTime = substr($strt,7);
            $h = 60*60*2;
            $oldTime = $oldTime+$h;
            if($oldTime < $now){
                return $this->renderPartial('index',['s'=>1,'datas'=>$datas]);
                exit;
            }
        }
        $str = base64_decode($data['k']);
        $id = substr($str,7);
        $strb = base64_decode($data['b']);
        $bossId = substr($strb,7);

        return $this->renderPartial('index',['s'=>3,'id'=>$id,'datas'=>$datas,'bossId'=>$bossId]);
    }
    public function actionUpdatePass(){
    //修改密码
        $response = ['success'=>false,'message'=>'修改失败！'];
        $data = Yii::$app->request->post();
       // echo $data['id'];exit;
       if($data['p1'] != $data['p2']){
            $response = ['success'=>false,'message'=>'两次输入的密码不一样！'];
            exit;
        }
        $users = new ProProduceUser();
        $key = clone $users;
        $userkey = $key->getAuthKey();
        $pw = $data['p1'].$userkey;
        $module = $users->findOne($data['id']);

        $module->password_hash = md5($pw);
        $module->auth_key = $userkey;
        if($module->save()){
            $response = ['success'=>ture,'message'=>'修改成功！'];
        }else{
            $response = ['success'=>false,'message'=>'修改失败！'];
        };
         echo json_encode($response);
    }
    public function actionShowSuccess(){
        return $this->renderPartial('index',['s'=>4]);
    }
    public function actionSendEmail(){
       $data = Yii::$app->request->post();
       $user = new ProProduceUser();
        $now = time();
        $users = $user->find()->select('itemid,boss_id')->where(['worker_name'=>$data['username'],'email'=>$data['email']])->asArray()->one();
        $id = base64_encode('haofeng'.$users['itemid']);
        $bossId = base64_encode('haofeng'.$users['boss_id']);
        $nowt =  base64_encode('haofeng'.$now);
        $urlText = "尊敬的".$data['username']."，您好:";
        $urlText .="您在云南图文点击了“忘记密码”按钮，故系统自动为您发送了这封邮件。您可以点击以下链接修改您的密码：";
        $urlText .= $_SERVER['HTTP_HOST'].\Yii::$app->urlManager->createUrl('company/retrieve/show-update').'&k='.$id.'&b='.$bossId.'&t='.$nowt.'。';
        $urlText .="此链接有效期为两个小时，请在两小时内点击链接进行修改（不能点击则复制到浏览器地址栏打开），每天最多允许找回5次密码。如果您不需要修改密码，或者您从未点击过“忘记密码”按钮，请忽略本邮件。";
        // 调用邮箱接口发送随机码
        $message = Yii::$app->mailer->compose();
        $result = $message->setFrom(Yii::$app->params['email']['username'])
            ->setTo($data['email'])
            ->setSubject('云南图文印通修改密码')
            ->setTextBody($urlText)
            ->send();

    }
}