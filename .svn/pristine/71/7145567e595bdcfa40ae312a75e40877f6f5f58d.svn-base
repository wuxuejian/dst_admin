<?php
/**
 * IC卡金额调剂控制器
 * @author wangmin
 */
namespace backend\modules\card\controllers;
use backend\controllers\BaseController;
use backend\models\ChargeCard;
use backend\models\ChargeCardSwap;
use yii;
use yii\data\Pagination;
class SwapController extends BaseController{
    /**
     * IC卡金额调剂
     */
    public function actionDo(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>''
            ];
            $ccCode = yii::$app->request->post('cc_code');
            if(!$ccCode){
                $returnArr['info'] = '无法获取到卡号，读卡失败！';
                echo json_encode($returnArr);
                return;
            }
            $cardInfo = ChargeCard::find()
                ->select(['cc_id','cc_code','recharge_times'])
                ->where(['cc_code'=>$ccCode])
                ->asArray()->one();
            if(!$cardInfo){
                $returnArr['info'] = '系统无该卡数据！';
                echo json_encode($returnArr);
                return;
            }
            $model = new ChargeCardSwap;
            $model->load(yii::$app->request->post(),'');
            $model->cc_id = $cardInfo['cc_id'];
            $model->write_status = 'error';
            $model->atime = date('Y-m-d H:i:s');
            $model->aaid = $_SESSION['backend']['adminInfo']['id'];
            if($model->type == 'add'){
                $model->after_money = $model->before_money + $model->money;
            }else{
                $model->after_money = $model->before_money - $model->money;
            }
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '充值操作成功！';
                $returnArr['data'] = [
                    'swapId'=>$model->id,
                    'money'=>$model->money,
                    'type'=>$model->type,
                    'cc_code'=>$cardInfo['cc_code'],
                    'rechargeTimes'=>$cardInfo['recharge_times'],
                ];
            }else{
                $errors = $model->getErrors();
                if($errors){
                    $returnArr['info'] = join('',array_column($errors,0));
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            return json_encode($returnArr);
        }else{
            return $this->render('do');
        }
    }

    /**
     * 写卡成功回调
     */
    public function actionWriteSuccess(){
        $swapId = yii::$app->request->get('swapId');
        $swapInfo = ChargeCardSwap::find()
            ->select(['type','cc_id','after_money'])
            ->where([
                'write_status'=>'error',
                'id'=>$swapId,
            ]) ->asArray()->one();
        if($swapInfo){
            ChargeCardSwap::updateAll(['write_status'=>'success'],['id'=>$swapId]);
            if($swapInfo['type'] == 'add'){
                //调用了充值接口充值次数加1
                ChargeCard::updateAllCounters([
                    'recharge_times'=>1
                    ],[
                    'cc_id'=>$swapInfo['cc_id'],
                ]);
            }
            //更新卡里余额
            ChargeCard::updateAll([
                    'cc_current_money'=>$swapInfo['after_money'],
                    'cm_update_datetime'=>date('Y-m-d H:i:s'),
                ],[
                    'cc_id'=>$swapInfo['cc_id'],
            ]);
        }
    }

    /**
     * ic卡金额调剂记录
     */
    public function actionList(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('list',[
            'buttons'=>$buttons,
        ]);
    }

    /**
     * card/swap/get-list-data
     */
    public function actionGetListData(){
        $returnDatas = [
            'rows'=>[],
            'total'=>0,
        ];
        $query = ChargeCardSwap::find()
            ->select([
                '{{%charge_card_swap}}.`id`',
                '{{%charge_card_swap}}.`type`',
                '{{%charge_card_swap}}.`before_money`',
                '{{%charge_card_swap}}.`money`',
                '{{%charge_card_swap}}.`after_money`',
                '{{%charge_card_swap}}.`write_status`',
                '{{%charge_card_swap}}.`note`',
                '{{%charge_card_swap}}.`atime`',
                '{{%charge_card}}.`cc_code`',
                '{{%admin}}.`username`',
            ])->joinWith('chargeCard',false)
            ->joinWith('admin',false);
        //其他查询条件
        $query->andFilterWhere([
            'like',
            '{{%charge_card}}.`cc_code`',
            yii::$app->request->get('cc_code')
        ]);
        $query->andFilterWhere([
            '{{%charge_card_swap}}.`type`'=>yii::$app->request->get('type')
        ]);
        //按操作时间查询
        $query->andFilterWhere([
        	'{{%charge_card_swap}}.`atime` >'=>yii::$app->request->get('start_atime')
        ]);
        $query->andFilterWhere([
        	'{{%charge_card_swap}}.`atime` <'=>yii::$app->request->get('end_atime')
        ]);
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'cc_code':
                    $orderBy = '{{%charge_card}}.`'.$sortColumn.'` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`'.$sortColumn.'` ';
                    break;
                default:
                    $orderBy = '{{%charge_card_swap}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
            $orderBy = '{{%charge_card_swap}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        //$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pageSize = isset($_GET['rows']) && $_GET['rows'] >= 0 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnDatas['rows'] = $data;
        $returnDatas['total'] = $total;
        return json_encode($returnDatas);
    }

    /**
     * 导出调剂记录
     * card/swap/export-excel
     */
 	public function actionExportExcel()
    {
    	$json_result = $this->actionGetListData();
    	$ret_data = json_decode($json_result,true);
    	$filename = 'IC电卡调剂记录.csv'; //设置文件名
    	$str = "电卡编号,调剂类型,调剂前金额,调剂金额,调剂后金额,写卡状态,操作时间,操作账号,备注\n";
    	if($ret_data['rows']){
    		foreach ($ret_data['rows'] as $row){
    			$cc_code = $row['cc_code']."\t";
    			$type = $row['type'] == 'add' ? '增加':'减少';
    			$status = $row['write_status'] == 'success' ? '成功':'失败';
    			$atime = $row['atime']."\t";
    			$str .="{$cc_code},{$type},{$row['before_money']},{$row['money']},{$row['after_money']},{$status},{$atime},{$row['username']},{$row['note']}"."\n";
    		}
    	}
    	
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    
    function export_csv($filename,$data)
    {
    	$filename = mb_convert_encoding($filename, "GBK","UTF-8");
    	//		header("Content-type: text/html; charset=utf-8");
    	header("Content-type:text/csv;charset=GBK");
    	header("Content-Disposition:attachment;filename=".$filename);
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	//	header("Content-Length:".strlen($data));
    	echo $data;
    }
}