<?php
/**
 * @Desc:	充电站管理 控制器
 * @author: chengwk
 * @date:	2016-01-14
 */
namespace backend\modules\charge\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\ChargeStation;
use backend\models\ChargeFrontmachine;
use backend\classes\UserLog;//日志类
use backend\models\ChargeSpots;
use backend\classes\MyUploadFile; //文件上传类
use common\models\Excel;

class ChargeStationController extends BaseController
{
    // 访问“充电站管理”视图
    public function actionIndex()
    {
		$configItems = ['cs_type','cs_status'];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value'); 
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data);
    }
    
    /**
     * 获取“充电站列表”
     */
    public function actionGetList()
    {
        $query = ChargeStation::find()
            ->select([
                '{{%charge_station}}.*',
               'cs_fm'=> '{{%charge_frontmachine}}.addr',
               'cs_creator'=> '{{%admin}}.username'
            ])
            ->joinWith('chargeFrontmachine',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['cs_is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['LIKE','cs_code',yii::$app->request->get('cs_code')]);
        $query->andFilterWhere(['LIKE','cs_name',yii::$app->request->get('cs_name')]);
        $query->andFilterWhere(['=','cs_type',yii::$app->request->get('cs_type')]);
        $query->andFilterWhere(['=','cs_status',yii::$app->request->get('cs_status')]);
        $query->andFilterWhere(['LIKE','cs_address',yii::$app->request->get('cs_address')]);
        $total = $query->count();
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field.' '.$direction;
		}else{
			$orderStr = 'cs_id DESC';
		}	
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();

        // 查出各电站拥有的电桩数量，并组合到电站数据里。
        $res = chargeSpots::find()
            ->select(['station_id','charger_num'=>'COUNT(id)'])
            ->where(['is_del'=>0])
            ->groupBy('station_id')
            ->indexBy('station_id')
            ->asArray()->all();
        foreach($data as &$controllerGLItem){
            if(isset($res[$controllerGLItem['cs_id']])){
                $controllerGLItem['charger_num'] = $res[$controllerGLItem['cs_id']]['charger_num'];
            }else{
                $controllerGLItem['charger_num'] = 0;
            }
        }

        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 获取省城市地区数据
     */
    public function actionGetRegionList(){
    	$parent_id = intval(yii::$app->request->get('parent_id'));
    	
    	$connection = yii::$app->db3;
    	$provinces = $connection->createCommand(
    			"select * from zc_region where parent_id={$parent_id}"
    	)->queryAll();
    	return json_encode($provinces);
    }
    
    /**
     * 新增
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $model = new ChargeStation();
            $formData = yii::$app->request->post();
            $model->load($formData,'');
            //将开放时间、电费费率、停车费序列化后再保存
            $model->cs_opentime = serialize($formData['cs_opentime']);
            $model->cs_powerrate = serialize($formData['cs_powerrate']);
            $model->cs_parkingfee = serialize($formData['cs_parkingfee']);
            $returnArr = [];
            if($model->validate()){
                $model->cs_create_time = date('Y-m-d H:i:s');
                $model->cs_creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '添加电站成功！';
                    // 添加日志
                    $logStr = "充电站管理-新增充电站【" . ($model->cs_code) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '添加电站失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr); 
        }else{
        	$connection = yii::$app->db3;
        	$provinces = $connection->createCommand(
        			"select * from zc_region where region_type=1"
        			)->queryAll();
//         	$citys = $connection->createCommand(
//         			"select * from zc_region where parent_id=2"
//         	)->queryAll();
//         	$areas = $connection->createCommand(
//         			"select * from zc_region where parent_id=3"
//         	)->queryAll();
        	
        	
			//获取combo配置数据
            $configItems = ['cs_type','cs_status','connection_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            
            
            $frontmachine = ChargeFrontmachine::find()
                ->select([ 'id','addr','port', 'register_number'])
                ->where(['is_del'=>0])
                ->asArray()->all();
            return $this->render('addEditWin',[
				'config'=>$config,
            	'provinces'=>$provinces,
            	'citys'=>array(),
            	'areas'=>array(),
				'initData'=>[
                    'action'=>'add',
                    'frontmachine'=>$frontmachine
                ]
			]);
		}
    }

    //修改
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $cs_id = intval(yii::$app->request->post('cs_id')) or die("缺少参数（cs_id）！");
            $model = ChargeStation::findOne(['cs_id'=>$cs_id]) or die('找不到对应记录！');
            $formData = yii::$app->request->post();
            //print_r($formData);exit;
            $model->load($formData,'');
            //将开放时间、电费费率、停车费序列化后再保存
            $model->cs_opentime = serialize($formData['cs_opentime']);
            $model->cs_powerrate = serialize($formData['cs_powerrate']);
            $model->cs_parkingfee = serialize($formData['cs_parkingfee']);
            //拼接电站图片路径
            $model->cs_pic_path = $formData['cs_pic_path_0'].';'.$formData['cs_pic_path_1'].';'.$formData['cs_pic_path_2'];
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改电站成功！';
                    // 同步更新该电站下的所有电桩经纬度及位置等信息！
                    ChargeSpots::updateAll(
                        [
                            'lng'=>$model->cs_lng,
                            'lat'=>$model->cs_lat,
                            'install_site'=>$model->cs_address,
                            'fm_id'=>$model->cs_fm_id
                        ],
                        ['station_id'=>$model->cs_id]
                    );
                    // 添加日志
                    $logStr = "充电站管理-修改充电站【" . ($model->cs_code) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改电站失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            $cs_id = intval(yii::$app->request->get('cs_id')) or die("缺少参数（cs_id）！");
            //获取combo配置数据
            $configItems = ['cs_type','cs_status','connection_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            $frontmachine = ChargeFrontmachine::find()
                ->select([ 'id','addr','port', 'register_number'])
                ->where(['is_del'=>0])
                ->asArray()->all();
            $ChargeStationInfo = ChargeStation::find()->where(['cs_id'=>$cs_id])->asArray()->one() or die('读取数据失败！');
            //将开放时间、电费费率、停车费反序列化
            $ChargeStationInfo['cs_opentime'] = unserialize($ChargeStationInfo['cs_opentime']);
            $ChargeStationInfo['cs_powerrate'] = unserialize($ChargeStationInfo['cs_powerrate']);
            $ChargeStationInfo['cs_parkingfee'] = unserialize($ChargeStationInfo['cs_parkingfee']);
            //电站照片路径
            $stationImages = explode(';',$ChargeStationInfo['cs_pic_path']);
            $imgNum = count($stationImages);
            for($i=0;$i<$imgNum;$i++){
                $ChargeStationInfo['cs_pic_path_'.$i] = $stationImages[$i];
            }
            
            $connection = yii::$app->db3;
            $provinces = $connection->createCommand(
            		"select * from zc_region where region_type=1"
            )->queryAll();
			$citys = $connection->createCommand(
					"select * from zc_region where region_type=2 and parent_id={$ChargeStationInfo['province_id']}"
			)->queryAll();
			$areas = $connection->createCommand(
					"select * from zc_region where region_type=3 and parent_id={$ChargeStationInfo['city_id']}"
			)->queryAll();
            return $this->render('addEditWin',[
				'config'=>$config,
                'stationImages'=>$stationImages,
            	'provinces'=>$provinces,
            	'citys'=>$citys,
            	'areas'=>$areas,
				'initData'=>[
					'action'=>'edit',
                    'frontmachine'=>$frontmachine,
					'ChargeStationInfo'=>$ChargeStationInfo
				]
			]);
		}
    }
    
    /**
     * 删除
     */
    public function actionRemove()
    {
        $cs_id = intval(yii::$app->request->get('cs_id')) or die("缺少参数（cs_id）！");
        $returnArr = [];
        // 这里控制的是提示先删除其下的电桩才能删电站！
        $res = ChargeSpots::find()->where(['station_id'=>$cs_id,'is_del'=>0])->asArray()->all();
        if(!empty($res)) {
            $returnArr['status'] = false;
            $returnArr['info'] = '必须先将该电站下的所有电桩全部删除掉，然后才能删除该电站！';
            echo json_encode($returnArr); exit;
        }
        if(ChargeStation::updateAll(['cs_is_del'=>1],['cs_id'=>$cs_id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除电站成功！';
            // 添加日志
            $logStr = "充电站管理-删除充电站【id：{$cs_id}】";
            UserLog::log($logStr, 'sys');
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除电站失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 上传电站照片
     */
    public function actionUploadStationImage(){
        if (yii::$app->request->isPost) {
            // 判断超全局数组$_FILES['yourCustomName']能否接收到上传的文件。
            if (!isset($_FILES['stationImage'])){
                $datas['error'] = 1;
                $datas['msg'] = '程序有错误：上传表单中文件框的name属性值与$_FILES接收时使用的不一致！';
                echo json_encode($datas); exit;
            }
            // 具体处理上传文件
            // print_r($_FILES['stationImage']);exit;
            // 注意：这里是一次上传多个文件。这里循环处理。
            $names = $_FILES['stationImage']['name'];
            $keys = array_keys($names);
            $imgNum = 0;
            $error = 0;
            $errMsg = '';
            $filePath = [];
            foreach ($keys as $key) {
                $file = [];
                $imgNum++;
                $file["name"] = $_FILES['stationImage']['name'][$key];            // 被上传文件的名称
                $file["type"] = $_FILES['stationImage']['type'][$key];;        // 被上传文件的类型，image/jpeg等
                $file["size"] = $_FILES['stationImage']['size'][$key];;        // 被上传文件的大小，以字节计
                $file["tmp_name"] = $_FILES['stationImage']['tmp_name'][$key];; // 存储在服务器的文件的临时副本的名称
                $file["error"] = $_FILES['stationImage']['error'][$key];;        // 由文件上传导致的错误代码,0表示正常
                $res = (new MyUploadFile())->handleUploadFile($file, 'chargeStation');
                if ($res['error']) {
                    $error += 1;
                    $errMsg .= '第' . $imgNum . '张照片' . $res['msg'];
                    $filePath[] = '';
                } else {
                    $error += 0;
                    $filePath[] = $res['filePath'];
                }
            }
            if ($error == 0) { // 全部照片上传成功
                $datas['status'] = 1;
                $datas['info'] = '照片全部上传成功！';
                $datas['filePath'] = $filePath;
            } elseif($error > 0 && $error < count($keys)) { // 部分照片上传失败
                $datas['status'] = 2;
                $datas['info'] = $errMsg;
                $datas['filePath'] = $filePath;
            } else { // 照片全部上传失败
                $datas['status'] = 0;
                $datas['info'] = $errMsg;
            }
            echo json_encode($datas); exit;
        } else {
            return $this->render('uploadStationImageWin');
        }
    }

    /**
     * 添加充电桩
     */
    public function actionAddPole() {
        if (yii::$app->request->isPost) {
            $model = new ChargeSpots();
            $formData = yii::$app->request->post();
            $model->load($formData,'');
            $returnArr = [];
            if ($model->validate()) {
                $model->systime = time();
                $model->sysuser = $_SESSION['backend']['adminInfo']['username'];
                //防止电桩表结构变动，只能将电站表中位置、经纬度等字段也同步保存到电桩表
                $stationId  = $formData['station_id'];
                $stationInfo = ChargeStation::find()
                    ->select(['cs_address','cs_lng','cs_lat','cs_fm_id'])
                    ->where(['cs_id'=>$stationId])
                    ->asArray()->one();
                $model->install_site = $stationInfo['cs_address'];
                $model->lng = $stationInfo['cs_lng'];
                $model->lat = $stationInfo['cs_lat'];
                $model->fm_id = $stationInfo['cs_fm_id'];
                if ($model->save(false)) {
                    $returnArr['status'] = true;
                    $returnArr['info'] = '添加充电桩成功！';
                    // 添加日志
                    $logStr = "充电站管理-添加充电桩【" . ($model->code_from_compony) . "】";
                    UserLog::log($logStr, 'sys');
                } else {
                    $returnArr['status'] = false;
                    $returnArr['info'] = '添加充电桩失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        } else {
            $cs_id = intval(yii::$app->request->get('cs_id')) or die("缺少参数（cs_id）！");
            $station = ChargeStation::find()
                ->select(['cs_id','cs_name','cs_fm_id'])
                ->where(['cs_id'=>$cs_id])
                ->asArray()->one();
            //获取combo配置数据
            $configItems = ['charge_type', 'connection_type', 'install_type', 'manufacturer', 'status', 'model','charge_pattern'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems, 'value');
            return $this->render('addPoleWin', [
                'config' => $config,
                'station' => $station
            ]);
        }
    }

    /**
     * 查看某充电站的详情
     */
    public function actionScanStationDetails()
    {
        $cs_id = intval(yii::$app->request->get('cs_id')) or die("缺少参数（cs_id）！");
        //获取combo配置数据
        $configItems = ['cs_type','cs_status','charge_pattern','status','manufacturer','connection_type','charge_type','install_type','model'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //查电站信息
        $stationInfo = ChargeStation::find()
            ->select([
                '{{%charge_station}}.*',
                'cs_fm'=> '{{%charge_frontmachine}}.addr'
            ])
            ->joinWith('chargeFrontmachine',false)
            ->where(['cs_id'=>$cs_id])
            ->asArray()
            ->one() or die('读取电站信息失败！');
        //将开放时间、电费费率、停车费反序列化
        $stationInfo['cs_opentime'] = unserialize($stationInfo['cs_opentime']);
        $stationInfo['cs_powerrate'] = unserialize($stationInfo['cs_powerrate']);
        $stationInfo['cs_parkingfee'] = unserialize($stationInfo['cs_parkingfee']);
        //电站照片路径
        $stationInfo['picPaths'] = explode(';',$stationInfo['cs_pic_path']);
        return $this->render('scanStationDetailsWin',[
            'config'=>$config,
            'stationInfo'=>$stationInfo
        ]);
    }

    /**
     * 获取某充电站拥有的所有电桩
     */
    public function actionGetPolesOfStation(){
        $returnArr = ['rows'=>[],'total'=>0];
        $stationId = intval(yii::$app->request->get('stationId')) or die("缺少参数（stationId）！");
        if($stationId) {
            $query = ChargeSpots::find()
                ->select([
                    '{{%charge_spots}}.*',
                    'station_name'=> '{{%charge_station}}.cs_name'
                ])
                ->joinWith('chargeStation',false,'LEFT JOIN')
                ->where(['station_id'=>$stationId,'is_del'=>0]);
            //查询条件
            $query->andFilterWhere(['like','charge_pattern',yii::$app->request->get('charge_pattern')]);
            $total = $query->count();
            $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
            $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
            //排序
            if(yii::$app->request->get('sort')){
                $field = yii::$app->request->get('sort');		//field
                $direction = yii::$app->request->get('order');  //asc or desc
                $orderStr = $field.' '.$direction;
            }else{
                $orderStr = 'charge_pattern ASC';
            }
            $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();

            //===【电桩状态的处理】===================================================================
            // 注意：这段代码在3处使用了，记得同步修改：charge模块ChargeSpotsController和ChargeStationController中。
            //---1.默认所有电桩真实状态都是【离线】-------------------------
            // 注意：电桩类型决定电枪数量，而有几个电枪就有几个状态。
            // 前置机上配置的电桩状态：0-充电，1-待机，2-故障，3-禁用，4-离线
            foreach($data as $k=>$item){
                $chargeType = $item['charge_type'];
                if($chargeType == 'DC' || $chargeType == 'AC'){
                    $data[$k]['status'] = '4';
                }elseif($chargeType == 'DC_DC' || $chargeType == 'AC_AC' || $chargeType == 'AC_DC'){
                    $data[$k]['status'] = '4,4';
                }
            }
            //---2.连接前置机数据库查询各电桩真实状态 begin -----------------
            // 将电桩逻辑地址按前置机id分组组装成数组备用
            $tmpArr = [];
            foreach ($data as $item) {
                if ($item['fm_id'] > 0 && $item['logic_addr']) {
                    $tmpArr[$item['fm_id']][] = $item['logic_addr'];
                }
            }
            foreach ($tmpArr as $fmId=>$logicAddr_arr) {
                $fmConnection = $this->connectFrontMachineDbByFmId($fmId);
                if (is_object($fmConnection)) {
                    $rows = (new \yii\db\Query())
                        ->select([
                            'DEV_ADDR',
                            'gun_status'=>"GROUP_CONCAT(charge_status.INNER_ID,'-',charge_status.STATUS)"
                        ])
                        ->from('charge_pole')
                        ->leftJoin('charge_status', 'charge_status.DEV_ID = charge_pole.DEV_ID')
                        ->where(['DEV_ADDR' => $logicAddr_arr])
                        ->groupBy('DEV_ADDR')
                        ->indexBy('DEV_ADDR')
                        ->all($fmConnection);
                    //print_r($rows);exit;
                    // 合并数据
                    if (!empty($rows)) {
                        foreach ($data as &$ControllerGLitem) {
                            if (isset($rows[$ControllerGLitem['logic_addr']])) {
                                $gunStatus_str = $rows[$ControllerGLitem['logic_addr']]['gun_status']; // 每一个枪号及对应状态的格式：“枪号-状态”
                                if($gunStatus_str){
                                    $gunStatus_arr = explode(',',$gunStatus_str);
                                    $gunStatus = [];
                                    foreach($gunStatus_arr as $item){
                                        $tmp = explode('-',$item);
                                        $gunStatus[$tmp[0]] = $tmp[1];
                                    }
                                    // 按键名降序排序并保持索引关系。使得前端就能按序以A-B顺序显示对应枪状态。
                                    krsort ($gunStatus);
                                    $ControllerGLitem['status'] = implode(',',array_values($gunStatus));
                                }
                            }
                        }
                    }
                }
            }
            //---连接前置机数据库查询各电桩真实状态 end -------------------------

            // 分类统计各电桩各状态分别有几个等等
            $statistics = [];
            if(!empty($data)){
                foreach($data as $item){
                    $pattern = $item['charge_pattern'];
                    $statistics[$pattern][] = $item['status'];
                }
            }
            foreach($statistics as $k=>$item){
                $statistics[$k] = array_count_values($item);
            }

            //print_r($statistics);exit;

            $returnArr['rows'] = $data;
            $returnArr['total'] = $total;
            $returnArr['statistics'] = $statistics;
            $returnArr['isFirstLoad'] = (yii::$app->request->get('charge_pattern') == null) ? 1 : 0; // 判断是否第一次加载电桩数据，前端用到。
        }
        echo json_encode($returnArr);
    }


    /**
     * 在百度地图上显示所有电站
     */
    public function actionShowOnMap(){
        $config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr']);
        $_config = [];
        foreach($config as $key=>$val){
            $_config[$key] = array_values($val)[0]['value'];
        }
        $columns = ['cs_id','cs_code','cs_name','cs_address','cs_lng','cs_lat','cs_type'];
        $query = ChargeStation::find()
            ->select($columns)
            ->joinWith('chargeFrontmachine',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['cs_is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['LIKE','cs_code',yii::$app->request->get('cs_code')]);
        $query->andFilterWhere(['LIKE','cs_name',yii::$app->request->get('cs_name')]);
        $query->andFilterWhere(['=','cs_type',yii::$app->request->get('cs_type')]);
        $query->andFilterWhere(['=','cs_status',yii::$app->request->get('cs_status')]);
        $query->andFilterWhere(['LIKE','cs_address',yii::$app->request->get('cs_address')]);
        $data = $query->asArray()->all();

        // 查出各电站拥有的电桩数量，并组合到电站数据里。
        $res = chargeSpots::find()
            ->select(['station_id','charger_num'=>'COUNT(id)'])
            ->where(['is_del'=>0])
            ->groupBy('station_id')
            ->indexBy('station_id')
            ->asArray()->all();
        foreach($data as &$controllerSOMItem){
            if(isset($res[$controllerSOMItem['cs_id']])){
                $controllerSOMItem['charger_num'] = $res[$controllerSOMItem['cs_id']]['charger_num'];
            }else{
                $controllerSOMItem['charger_num'] = 0;
            }
        }
        // 2016/9/21 页面内ajax请求
        $is_ajax  = yii::$app->request->get('is_ajax');
        if($is_ajax)
        {
        	return json_encode($data);
        }else{
        	return $this->render('showOnMapWin',[
        			'listData'=>$data,
        			'config'=>$_config
        			]);
        }
        
        
        
    }

    /**
     * 导出Excel
     */
    public function actionExportGridData()
    {
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'电站编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'电站名称','font-weight'=>true,'width'=>'25'],
                ['content'=>'电站类型','font-weight'=>true,'width'=>'10'],
                ['content'=>'电站状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'电站位置','font-weight'=>true,'width'=>'30'],
                ['content'=>'电桩数量','font-weight'=>true,'width'=>'10'],
                ['content'=>'是否开放','font-weight'=>true,'width'=>'10'],
                ['content'=>'运营商/客户','font-weight'=>true,'width'=>'25'],
                ['content'=>'投运日期','font-weight'=>true,'width'=>'10'],
                ['content'=>'备注','font-weight'=>true,'width'=>'30'],
                ['content'=>'所属前置机','font-weight'=>true,'width'=>'15'],
                ['content'=>'创建时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'创建人','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $query = ChargeStation::find()
            ->select([
                '{{%charge_station}}.cs_id',   //下面查“电桩数量”用
                '{{%charge_station}}.cs_code',
                '{{%charge_station}}.cs_name',
                '{{%charge_station}}.cs_type',
                '{{%charge_station}}.cs_status',
                '{{%charge_station}}.cs_address',
                'charger_num'=>'{{%charge_station}}.cs_address', //“电桩数量”占位
                '{{%charge_station}}.cs_is_open',
                '{{%charge_station}}.cs_building_user',
                '{{%charge_station}}.cs_commissioning_date',
                '{{%charge_station}}.cs_mark',
                '{{%charge_frontmachine}}.addr',
                '{{%charge_station}}.cs_create_time',
                '{{%admin}}.username'
            ])
            ->joinWith('chargeFrontmachine',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['cs_is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['LIKE','cs_code',yii::$app->request->get('cs_code')]);
        $query->andFilterWhere(['LIKE','cs_name',yii::$app->request->get('cs_name')]);
        $query->andFilterWhere(['=','cs_type',yii::$app->request->get('cs_type')]);
        $query->andFilterWhere(['=','cs_status',yii::$app->request->get('cs_status')]);
        $query->andFilterWhere(['LIKE','cs_address',yii::$app->request->get('cs_address')]);
        $data = $query->orderBy('cs_id DESC')->asArray()->all();

        // 查出各电站拥有的电桩数量，并组合到电站数据里。
        $res = chargeSpots::find()
            ->select(['station_id','charger_num'=>'COUNT(id)'])
            ->where(['is_del'=>0])
            ->groupBy('station_id')
            ->indexBy('station_id')
            ->asArray()->all();
        foreach($data as &$controllerGLItem){
            if(isset($res[$controllerGLItem['cs_id']])){
                $controllerGLItem['charger_num'] = $res[$controllerGLItem['cs_id']]['charger_num'];
            }else{
                $controllerGLItem['charger_num'] = 0;
            }
        }
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge_station',
            'subject'=>'charge_station',
            'description'=>'charge_station',
            'keywords'=>'charge_station',
            'category'=>'charge_station'
        ]);
        //---向excel添加表头------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据---------
        $configItems = ['cs_type','cs_status'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        foreach($data as $item){
            unset($item['cs_id']);
            $item['cs_is_open'] = $item['cs_is_open'] == 1 ? '是' : '否';
            $lineData = [];
            // 各combox配置项以txt代替val
            foreach($configItems as $conf) {
                if($item[$conf]) {
                    $v = $item[$conf];
                    $item[$conf] = $configs[$conf][$v]['text'];
                }
            }
            foreach($item as $k=>$v) {
                if(!is_array($v)){
                    $lineData[] = ['content'=>$v];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','充电站列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
	


}