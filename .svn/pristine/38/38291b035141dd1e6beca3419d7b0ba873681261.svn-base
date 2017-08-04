<?php
/**
 * 三电系统管理 控制器
 * 2016-04-19 16:23
 */
namespace backend\modules\car\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\Battery;
use backend\models\Motor;
use backend\models\MotorMonitor;
use backend\classes\UserLog;
use common\models\Excel;

class ThreeElectricSystemController extends BaseController
{
    /****************************************************
     * 一、“电池系统”
     ***************************************************/
    public function actionBattery(){
        $configItems = ['battery_type','battery_spec','connection_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('battery',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /*
     * “电池系统”--获取列表
     */
    public function actionBatteryGetList(){
        $query = Battery::find()
            ->select([
                '{{%battery}}.*',
                'creator'=> '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%battery}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%battery}}.`battery_model`',yii::$app->request->get('battery_model')]);
        $query->andFilterWhere(['=','{{%battery}}.`battery_type`',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery}}.`connection_type`',yii::$app->request->get('connection_type')]);
        $query->andFilterWhere(['like','{{%battery}}.`battery_maker`',yii::$app->request->get('battery_maker')]);
        $query->andFilterWhere(['=','{{%battery}}.`battery_spec`',yii::$app->request->get('battery_spec')]);
        $total = $query->count();
        //排序
        $sortField = yii::$app->request->get('sort','');
        $sortDirect = yii::$app->request->get('order','desc');
        if($sortField){
            switch($sortField){
                case 'creator':
                    $orderStr = '{{%admin}}.username';
                    break;
                default:
                    $orderStr = '{{%battery}}.' . $sortField;
            }
        }else{
            $orderStr = '{{%battery}}.create_time';
        }
        $orderStr .= ' ' . $sortDirect;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query
            ->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*
     * “电池系统”--新增
     */
    public function actionBatteryAdd(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查唯一性-begin
            $model = Battery::findOne(['battery_model'=>trim($formData['battery_model'])]);
            if(!$model){
                $model = new Battery();
            }else{
                //再判断是否标记为已删除，若是则重新启用它
                if(!$model->is_del){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '该电池型号已存在！';
                    return json_encode($returnArr);
                }
                $model->is_del = 0;
            }
            //检查唯一性-end
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->create_time = date('Y-m-d H:i:s');
                $model->creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增电池！';
                    // 添加日志
                    $logStr = "三电系统管理-电池系统-新增电池（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增电池失败！';
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
            //获取combo配置数据
            $configItems = ['battery_type','battery_spec','connection_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('batteryAddWin',[
                'config'=>$config
            ]);
        }
    }

    /*
     * “电池系统”--修改
     */
    public function actionBatteryEdit(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查唯一性-begin
            $oModel = Battery::findOne(['battery_model'=>trim($formData['battery_model'])]);
            if($oModel && ($oModel->id != $formData['id'])){
                //再判断是否标记为已删除，若是则本次真正删除它然后再去修改当前记录
                if(!$oModel->is_del){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '该电池型号已存在！';
                    return json_encode($returnArr);
                }
                Battery::deleteAll(['id'=>$oModel->id]);
            }
            //检查唯一性-end
            $model = Battery::findOne($formData['id']);
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改电池！';
                    // 添加日志
                    $logStr = "三电系统管理-电池系统-修改电池（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改电池失败！';
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
            //获取combo配置数据
            $configItems = ['battery_type','battery_spec','connection_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
            $batteryInfo = Battery::find()->where(['id'=>$id])->asArray()->one();
            return $this->render('batteryEditWin',[
                'config'=>$config,
                'recInfo'=>$batteryInfo
            ]);
        }
    }

    /**
     * “电池系统”--删除
     */
    public function actionBatteryRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
        $returnArr = [];
        if(Battery::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除电池成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除电池失败！';
        }
        return json_encode($returnArr);
    }

    /**
     * “电池系统”--导出
     */
    public function actionBatteryExport(){
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'battery',
            'subject'=>'battery',
            'description'=>'battery',
            'keywords'=>'battery',
            'category'=>'battery'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'电池型号','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池系统额定电压(V)','font-weight'=>true,'width'=>'20'],
                ['content'=>'电池系统额定容量(Ah)','font-weight'=>true,'width'=>'20'],
                ['content'=>'电池系统额定电能(kWh)','font-weight'=>true,'width'=>'20'],
                ['content'=>'电池系统电池串联数量','font-weight'=>true,'width'=>'20'],
                ['content'=>'单体电池额定电压(V)','font-weight'=>true,'width'=>'20'],
                ['content'=>'单体电池额定容量(Ah)','font-weight'=>true,'width'=>'20'],
                ['content'=>'电池模块容量(kWh)','font-weight'=>true,'width'=>'20'],
                ['content'=>'电池模块数量','font-weight'=>true,'width'=>'15'],
                ['content'=>'充电接口类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池规格','font-weight'=>true,'width'=>'15'],
                ['content'=>'生产厂家','font-weight'=>true,'width'=>'15'],
                ['content'=>'创建时间','font-weight'=>true,'width'=>'25'],
                ['content'=>'创建人','font-weight'=>true,'width'=>'15']
            ]
        ];
        //---向excel添加表头--------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //查询数据，查询字段与导出的excel表头对应
        $query = Battery::find()
            ->select([
                '{{%battery}}.battery_model',
                '{{%battery}}.battery_type',
                '{{%battery}}.system_voltage',
                '{{%battery}}.system_capacity',
                '{{%battery}}.system_power',
                '{{%battery}}.system_nums',
                '{{%battery}}.single_voltage',
                '{{%battery}}.single_capacity',
                '{{%battery}}.module_capacity',
                '{{%battery}}.module_nums',
                '{{%battery}}.connection_type',
                '{{%battery}}.battery_spec',
                '{{%battery}}.battery_maker',
                '{{%battery}}.create_time',
                '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%battery}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%battery}}.`battery_model`',yii::$app->request->get('battery_model')]);
        $query->andFilterWhere(['=','{{%battery}}.`battery_type`',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery}}.`connection_type`',yii::$app->request->get('connection_type')]);
        $query->andFilterWhere(['like','{{%battery}}.`battery_maker`',yii::$app->request->get('battery_maker')]);
        $query->andFilterWhere(['=','{{%battery}}.`battery_spec`',yii::$app->request->get('battery_spec')]);
        $data = $query->orderBy('{{%battery}}.create_time DESC')->asArray()->all();
        //print_r($data);exit;
        if($data){
            $configItems = ['battery_type','connection_type','battery_spec'];
            $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            //---向excel添加具体数据---------------------------
            foreach($data as $item){
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                    }
                }
                foreach($item as $k=>$v) {
                    $lineData[] = ['content'=>$v];
                }
                $excel->addLineToExcel($lineData);
            }
            unset($data);
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','电池系统列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    /****************************************************
     * 二、“电机系统”
     ***************************************************/
    public function actionMotor(){
        $configItems = ['encoder','cooling_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('motor',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /*
     * “电机系统”--获取列表
     */
    public function actionMotorGetList(){
        $query = Motor::find()
            ->select([
                '{{%motor}}.*',
                'creator'=> '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%motor}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%motor}}.`motor_model`',yii::$app->request->get('motor_model')]);
        $query->andFilterWhere(['=','{{%motor}}.`encoder`',yii::$app->request->get('encoder')]);
        $query->andFilterWhere(['=','{{%motor}}.`cooling_type`',yii::$app->request->get('cooling_type')]);
        $query->andFilterWhere(['like','{{%motor}}.`motor_maker`',yii::$app->request->get('motor_maker')]);
        $total = $query->count();
        //排序
        $sortField = yii::$app->request->get('sort','');
        $sortDirect = yii::$app->request->get('order','desc');
        if($sortField){
            switch($sortField){
                case 'creator':
                    $orderStr = '{{%admin}}.username';
                    break;
                default:
                    $orderStr = '{{%motor}}.' . $sortField;
            }
        }else{
            $orderStr = '{{%motor}}.create_time';
        }
        $orderStr .= ' ' . $sortDirect;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query
            ->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*
     * “电机系统”--新增
     */
    public function actionMotorAdd(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查唯一性-begin
            $model = Motor::findOne(['motor_model'=>trim($formData['motor_model'])]);
            if(!$model){
                $model = new Motor();
            }else{
                //再判断是否标记为已删除，若是则重新启用它
                if(!$model->is_del){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '该电机型号已存在！';
                    return json_encode($returnArr);
                }
                $model->is_del = 0;
            }
            //检查唯一性-end
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->create_time = date('Y-m-d H:i:s');
                $model->creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增电机！';
                    // 添加日志
                    $logStr = "三电系统管理-电机系统-新增电机（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增电机失败！';
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
            //获取combo配置数据
            $configItems = ['encoder','cooling_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('motorAddWin',[
                'config'=>$config
            ]);
        }
    }

    /*
     * “电机系统”--修改
     */
    public function actionMotorEdit(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查唯一性-begin
            $oModel = Motor::findOne(['motor_model'=>trim($formData['motor_model'])]);
            if($oModel && ($oModel->id != $formData['id'])){
                //再判断是否标记为已删除，若是则本次真正删除它然后再去修改当前记录
                if(!$oModel->is_del){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '该电机型号已存在！';
                    return json_encode($returnArr);
                }
                Motor::deleteAll(['id'=>$oModel->id]);
            }
            //检查唯一性-end
            $model = Motor::findOne($formData['id']);
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改电机！';
                    // 添加日志
                    $logStr = "三电系统管理-电机系统-修改电机（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改电机失败！';
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
            //获取combo配置数据
            $configItems = ['encoder','cooling_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
            $batteryInfo = Motor::find()->where(['id'=>$id])->asArray()->one();
            return $this->render('motorEditWin',[
                'config'=>$config,
                'recInfo'=>$batteryInfo
            ]);
        }
    }

    /**
     * “电机系统”--删除
     */
    public function actionMotorRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
        $returnArr = [];
        if(Motor::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除电机成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除电机失败！';
        }
        return json_encode($returnArr);
    }

    /**
     * “电机系统”--导出
     */
    public function actionMotorExport(){
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'motor',
            'subject'=>'motor',
            'description'=>'motor',
            'keywords'=>'motor',
            'category'=>'motor'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'电机型号','font-weight'=>true,'width'=>'15'],
                ['content'=>'编码器','font-weight'=>true,'width'=>'15'],
                ['content'=>'额定功率(kW)','font-weight'=>true,'width'=>'15'],
                ['content'=>'额定转速(rpm)','font-weight'=>true,'width'=>'15'],
                ['content'=>'额定频率(Hz)','font-weight'=>true,'width'=>'15'],
                ['content'=>'额定电流(A)','font-weight'=>true,'width'=>'15'],
                ['content'=>'额定转矩(Nm)','font-weight'=>true,'width'=>'15'],
                ['content'=>'额定电压(V)','font-weight'=>true,'width'=>'15'],
                ['content'=>'峰值功率(kW)','font-weight'=>true,'width'=>'15'],
                ['content'=>'峰值转速(rpm)','font-weight'=>true,'width'=>'15'],
                ['content'=>'峰值频率(Hz)','font-weight'=>true,'width'=>'15'],
                ['content'=>'峰值电流(A)','font-weight'=>true,'width'=>'15'],
                ['content'=>'峰值电流(A)','font-weight'=>true,'width'=>'15'],
                ['content'=>'峰值转矩(Nm)','font-weight'=>true,'width'=>'15'],
                ['content'=>'极对数','font-weight'=>true,'width'=>'15'],
                ['content'=>'冷却方式','font-weight'=>true,'width'=>'15'],
                ['content'=>'生产厂家','font-weight'=>true,'width'=>'15'],
                ['content'=>'创建时间','font-weight'=>true,'width'=>'25'],
                ['content'=>'创建人','font-weight'=>true,'width'=>'15']
            ]
        ];
        //---向excel添加表头--------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //查询数据，查询字段与导出的excel表头对应
        $query = Motor::find()
            ->select([
                '{{%motor}}.motor_model',
                '{{%motor}}.encoder',
                '{{%motor}}.rated_power',
                '{{%motor}}.rated_speed',
                '{{%motor}}.rated_frequency',
                '{{%motor}}.rated_current',
                '{{%motor}}.rated_torque',
                '{{%motor}}.rated_voltage',
                '{{%motor}}.peak_power',
                '{{%motor}}.peak_speed',
                '{{%motor}}.peak_frequency',
                '{{%motor}}.peak_current',
                '{{%motor}}.peak_torque',
                '{{%motor}}.polar_logarithm',
                '{{%motor}}.cooling_type',
                '{{%motor}}.motor_maker',
                '{{%motor}}.create_time',
                '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%motor}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%motor}}.`motor_model`',yii::$app->request->get('motor_model')]);
        $query->andFilterWhere(['=','{{%motor}}.`encoder`',yii::$app->request->get('encoder')]);
        $query->andFilterWhere(['=','{{%motor}}.`cooling_type`',yii::$app->request->get('cooling_type')]);
        $query->andFilterWhere(['like','{{%motor}}.`motor_maker`',yii::$app->request->get('motor_maker')]);
        $data = $query->orderBy('{{%motor}}.create_time DESC')->asArray()->all();
        //print_r($data);exit;
        if($data){
            $configItems = ['encoder','cooling_type'];
            $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            //---向excel添加具体数据---------------------------
            foreach($data as $item){
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                    }
                }
                foreach($item as $k=>$v) {
                    $lineData[] = ['content'=>$v];
                }
                $excel->addLineToExcel($lineData);
            }
            unset($data);
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','电机系统列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    /****************************************************
     * 三、“电机控制器”
     ***************************************************/
    public function actionMotorMonitor(){
        $configItems = ['apply_motor_type','cooling_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('motorMonitor',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /*
     * “电机控制器”--获取列表
     */
    public function actionMotorMonitorGetList(){
        $query = MotorMonitor::find()
            ->select([
                '{{%motor_monitor}}.*',
                'input_voltage_range'=>"CONCAT(input_voltage_range_s,'--',input_voltage_range_e)",
                'output_frequency_range'=>"CONCAT(output_frequency_range_s,'--',output_frequency_range_e)",
                'creator'=> '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%motor_monitor}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%motor_monitor}}.`motor_monitor_model`',yii::$app->request->get('motor_monitor_model')]);
        $query->andFilterWhere(['=','{{%motor_monitor}}.`apply_motor_type`',yii::$app->request->get('apply_motor_type')]);
        $query->andFilterWhere(['=','{{%motor_monitor}}.`cooling_type`',yii::$app->request->get('cooling_type')]);
        $query->andFilterWhere(['like','{{%motor_monitor}}.`motor_monitor_maker`',yii::$app->request->get('motor_monitor_maker')]);
        $total = $query->count();
        //排序
        $sortField = yii::$app->request->get('sort','');
        $sortDirect = yii::$app->request->get('order','desc');
        if($sortField){
            switch($sortField){
                case 'creator':
                    $orderStr = '{{%admin}}.username';
                    break;
                default:
                    $orderStr = '{{%motor_monitor}}.' . $sortField;
            }
        }else{
            $orderStr = '{{%motor_monitor}}.create_time';
        }
        $orderStr .= ' ' . $sortDirect;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query
            ->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*
     * “电机控制器”--新增
     */
    public function actionMotorMonitorAdd(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查唯一性-begin
            $model = MotorMonitor::findOne(['motor_monitor_model'=>trim($formData['motor_monitor_model'])]);
            if(!$model){
                $model = new MotorMonitor();
            }else{
                //再判断是否标记为已删除，若是则重新启用它
                if(!$model->is_del){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '该电机控制器型号已存在！';
                    return json_encode($returnArr);
                }
                $model->is_del = 0;
            }
            //检查唯一性-end
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->create_time = date('Y-m-d H:i:s');
                $model->creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增电控！';
                    // 添加日志
                    $logStr = "三电系统管理-电机控制器-新增电控（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增电控失败！';
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
            //获取combo配置数据
            $configItems = ['apply_motor_type','cooling_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('motorMonitorAddWin',[
                'config'=>$config
            ]);
        }
    }

    /*
     * “电机控制器”--修改
     */
    public function actionMotorMonitorEdit(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查唯一性-begin
            $oModel = MotorMonitor::findOne(['motor_monitor_model'=>trim($formData['motor_monitor_model'])]);
            if($oModel && ($oModel->id != $formData['id'])){
                //再判断是否标记为已删除，若是则本次真正删除它然后再去修改当前记录
                if(!$oModel->is_del){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '该电机控制器型号已存在！';
                    return json_encode($returnArr);
                }
                MotorMonitor::deleteAll(['id'=>$oModel->id]);
            }
            //检查唯一性-end
            $model = MotorMonitor::findOne($formData['id']);
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改电控！';
                    // 添加日志
                    $logStr = "三电系统管理-电机控制器-修改电控（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改电控失败！';
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
            //获取combo配置数据
            $configItems = ['apply_motor_type','cooling_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
            $batteryInfo = MotorMonitor::find()->where(['id'=>$id])->asArray()->one();
            return $this->render('motorMonitorEditWin',[
                'config'=>$config,
                'recInfo'=>$batteryInfo
            ]);
        }
    }

    /**
     * “电机控制器”--删除
     */
    public function actionMotorMonitorRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
        $returnArr = [];
        if(MotorMonitor::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除电控成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除电控失败！';
        }
        return json_encode($returnArr);
    }

    /**
     * “电机控制器”--导出
     */
    public function actionMotorMonitorExport(){
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'motor',
            'subject'=>'motor',
            'description'=>'motor',
            'keywords'=>'motor',
            'category'=>'motor'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'电机控制器型号','font-weight'=>true,'width'=>'15'],
                ['content'=>'适用电机','font-weight'=>true,'width'=>'15'],
                ['content'=>'输入电压范围(VDC)','font-weight'=>true,'width'=>'20'],
                ['content'=>'额定输入电压(VDC)','font-weight'=>true,'width'=>'20'],
                ['content'=>'额定容量(kVA)','font-weight'=>true,'width'=>'15'],
                ['content'=>'峰值容量(kVA)','font-weight'=>true,'width'=>'15'],
                ['content'=>'额定输入电流(A)','font-weight'=>true,'width'=>'20'],
                ['content'=>'额定输出电流(A)','font-weight'=>true,'width'=>'20'],
                ['content'=>'峰值输出电流(A)','font-weight'=>true,'width'=>'20'],
                ['content'=>'峰值电流持续时间(min)','font-weight'=>true,'width'=>'30'],
                ['content'=>'输出频率范围(Hz)','font-weight'=>true,'width'=>'20'],
                ['content'=>'控制器最大效率(%)','font-weight'=>true,'width'=>'20'],
                ['content'=>'防护等级','font-weight'=>true,'width'=>'15'],
                ['content'=>'工作环境温度(℃)','font-weight'=>true,'width'=>'20'],
                ['content'=>'冷却方式','font-weight'=>true,'width'=>'15'],
                ['content'=>'生产厂家','font-weight'=>true,'width'=>'15'],
                ['content'=>'创建时间','font-weight'=>true,'width'=>'25'],
                ['content'=>'创建人','font-weight'=>true,'width'=>'15']
            ]
        ];
        //---向excel添加表头--------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //查询数据，查询字段与导出的excel表头对应
        $query = MotorMonitor::find()
            ->select([
                '{{%motor_monitor}}.motor_monitor_model',
                '{{%motor_monitor}}.apply_motor_type',
                'input_voltage_range'=>"CONCAT(input_voltage_range_s,'--',input_voltage_range_e)",
                '{{%motor_monitor}}.rated_input_voltage',
                '{{%motor_monitor}}.rated_capacity',
                '{{%motor_monitor}}.peak_capacity',
                '{{%motor_monitor}}.rated_input_current',
                '{{%motor_monitor}}.rated_output_current',
                '{{%motor_monitor}}.peak_output_current',
                '{{%motor_monitor}}.peak_current_duration',
                'output_frequency_range'=>"CONCAT(output_frequency_range_s,'--',output_frequency_range_e)",
                '{{%motor_monitor}}.max_effciency',
                '{{%motor_monitor}}.protection_level',
                '{{%motor_monitor}}.working_temp',
                '{{%motor_monitor}}.cooling_type',
                '{{%motor_monitor}}.motor_monitor_maker',
                '{{%motor_monitor}}.create_time',
                'creator'=> '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%motor_monitor}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%motor_monitor}}.`motor_monitor_model`',yii::$app->request->get('motor_monitor_model')]);
        $query->andFilterWhere(['=','{{%motor_monitor}}.`apply_motor_type`',yii::$app->request->get('apply_motor_type')]);
        $query->andFilterWhere(['=','{{%motor_monitor}}.`cooling_type`',yii::$app->request->get('cooling_type')]);
        $query->andFilterWhere(['like','{{%motor_monitor}}.`motor_monitor_maker`',yii::$app->request->get('motor_monitor_maker')]);
        $data = $query->orderBy('{{%motor_monitor}}.create_time DESC')->asArray()->all();
        //print_r($data);exit;
        if($data){
            $configItems = ['apply_motor_type','cooling_type'];
            $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            //---向excel添加具体数据---------------------------
            foreach($data as $item){
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                    }
                }
                foreach($item as $k=>$v) {
                    $lineData[] = ['content'=>$v];
                }
                $excel->addLineToExcel($lineData);
            }
            unset($data);
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','电机控制器列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




}