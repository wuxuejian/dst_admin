<?php
/**
 * 会员通知控制器（由系统单向发布的通知）
 */
namespace backend\modules\interfaces\controllers;
use backend\models\VipNotice;
use backend\models\ConfigCategory;
use yii;
use yii\web\Controller;
use yii\data\Pagination;
class NoticeController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     * app获取通知地址
     * notice_get-notice
     */
	public function actionGetNotice(){
        //var_dump(yii::getAlias('@web'));
        $returnArr = [
            'error'=>1,
            'msg'=>'',
            'data'=>[],
        ];
        $lastSearchDay = isset($_REQUEST['last_search_day']) ? $_REQUEST['last_search_day'] : '';
        do{
            $data = $this->getNoticeByDay($lastSearchDay);
            //var_dump($data);
            if($data){
                $lastSearchDay = date('Y-m-d',strtotime($data['search_day']));
//                 $lastSearchDay = date('Y-m-d',strtotime($data['search_day']) - 86400);
                //var_dump($lastSearchDay);
                $returnArr['data'][] = $data;
            }

        }while(count($returnArr['data']) <= 5 && $data !== false);
        //echo '<pre>';
        if(!$returnArr['data']){
            $returnArr['error'] = 1;
            $returnArr['msg'] = '没有更多消息了！';
        }else{
            $returnArr['error'] = 0;
        }
        return json_encode($returnArr);
    }

    /**
     * 获取指定日期的通知数据
     */
    protected function getNoticeByDay($day = ''){
        $returnArr = [
            'search_day'=>'',
            'list'=>[],
        ];
        if(!$day){
            $lastNotice = VipNotice::find()
                ->select(['vn_systime'])
                ->where(['vn_is_del'=>0])
                ->orderBy('vn_id desc')->asArray()->one();
        }else{
            //找到指定日期和他上一天的分界记录的时间
            $lastNotice = VipNotice::find()
                ->select(['vn_systime'])
                ->andWhere(['<=','vn_systime',strtotime($day)])
                ->andWhere(['vn_is_del'=>0])
                ->orderBy('vn_id desc')->asArray()->one();
        }
        if(!$lastNotice){
            return false;
        }
        $returnArr['search_day'] = date('Y-m-d',$lastNotice['vn_systime']);
        //查询最新记录所在日期的全部数据
        $data = VipNotice::find()
            ->select(['vn_id','vn_title','vn_icon_path'])
            ->andWhere([
                '>=',
                'vn_systime',
                strtotime($returnArr['search_day'].' 00:00:00')
            ])->andWhere([
                '<=',
                'vn_systime',
                strtotime($returnArr['search_day'].' 23:59:59')
            ])->andWhere(['vn_is_del'=>0])
            ->orderBy('vn_id desc')->asArray()->all();
        $url = yii::$app->urlManager->createAbsoluteUrl(['interfaces/notice/content']);
        $url = explode('?',$url);
        $url = dirname($url[0]);
        foreach($data as $k=>$v){
            //$data[$k]['thumb_type'] = 'small';//no/small/big
            $data[$k]['url'] = yii::$app->urlManager->createAbsoluteUrl(['interfaces/notice/content','vn_id'=>$v['vn_id']]);//no/small/big
            if($v['vn_icon_path']){
                $data[$k]['vn_icon_path'] = $url.ltrim($v['vn_icon_path'],'.');
            }
        }
        $returnArr['list'] = $data;
        return $returnArr;

    }

	/**
	 *	获取会员通知
     *  interfaces/notice/list
	 */
/*    public function actionList(){
        if(yii::$app->request->isPost){
            $page = (isset($_POST['page']) && $_POST['page'] > 0) ? intval($_POST['page']) : 1;
            $pageSize = (isset($_POST['rows']) && $_POST['rows'] <= 50) ? intval($_POST['rows']) : 20;
            $query = VipNotice::find()
                    ->select(['vn_id','vn_type','vn_title','vn_icon_path','vn_hyperlink','vn_public_time'])
                    ->where(['vn_is_del'=>0]);
            $total = $query->count();
            $orderBy = '`vn_id` desc';
            $pages = new Pagination(['totalCount' =>$total]);
            $data = $query->offset(($page-1) * $pageSize)->limit($pageSize)
                ->orderBy($orderBy)->asArray()->all();
            return json_encode($data);
        }else{
            return $this->render('list');
        }
    }*/

	
    /**
     * 读取通知
     * 
     */
    public function actionContent(){
        $id = yii::$app->request->get('vn_id');
        if(!$id){
            return $this->render('content-no');
        }
        $notice = VipNotice::find()
            ->select(['vn_title','vn_public_time','vn_type','vn_content'])
            ->where(['vn_id'=>$id,'vn_is_del'=>0])->asArray()->one();
        if(!$notice){
            return $this->render('content-no');
        }else{
            $config = (new ConfigCategory)->getCategoryConfig(['vn_type'],'value');
            return $this->render('content',[
                'notice'=>$notice,
                'vn_type'=>$config['vn_type'],
            ]);
        }
        
    }
	
}