<?php
/**
 * 车辆实时数据上报控制器
 * 消息id    0x02
 * 应答消息  是
 * @author   wangmin
 * @time     2015/10/29 17:07
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
class CarDataSubmit
{
    protected $data;//客户端发来的数据
    protected $saveData = [];//将被保存到数据库中的数据
    protected $alertData = [];//报警数据
    public function init($message,$client_id)
    {
        //查询车辆id
//         $db = Db::instance('db');
//         $car = $db->select('id,car_vin')->from('cs_tcp_car')
//                 ->where('car_vin = :car_vin')->bindValues(['car_vin'=>$message['carVIN']])
//                 ->row();
        //var_dump($message);
        //var_dump($car);
        
//         if(!$car){
//             //车辆没有注册
//             $send_data = [
//                 'commandSingle'=>$message['commandSingle'],
//                 'commandAnswer'=>0x02,//
//                 'carVIN'=>$message['carVIN'],
//                 'data'=>'',
//             ];
//             Gateway::sendToClient($client_id,$send_data);
//             return false;
//         }
        $this->data = $message['data'];
        
//         echo "\n".bin2hex($this->data)."\n";
//         exit;
        
        //解析数据采集时间
        $subData = substr($this->data,0,6);
        $this->data = substr($this->data,6);
        $formatArr = [
            'year'=>'C1',
            'month'=>'C1',
            'day'=>'C1',
            'hour'=>'C1',
            'minute'=>'C1',
            'second'=>'C1',
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $collectionDatetime = unpack($format,$subData);
        $this->saveData['collection_datetime'] = strtotime("20{$collectionDatetime['year']}-{$collectionDatetime['month']}-{$collectionDatetime['day']} {$collectionDatetime['hour']}:{$collectionDatetime['minute']}:{$collectionDatetime['second']}");
        $this->saveData['data_source'] = '航天无线';
        $this->saveData['update_datetime'] = time();
        $this->saveData['car_vin'] = $message['carVIN'];
        $this->saveData['data_hex'] = $message['data_hex'];
        
        //防止重复解析
        $car_data_is_analysis = false;
        $moter_data_is_analysis = false;
        $battery_data_is_analysis = false;
        $engine_data_is_analysis = false;
        $position_data_is_analysis = false;
        $pole_value_data_is_analysis = false;
        $alert_data_is_analysis = false;
        $is_analysis_ok = false;	//是否解析完所有数据
        
        while(strlen($this->data) > 0){
        	if($is_analysis_ok){
        		break;
        	}
            //解析小包头查看消息属于哪个类型
            $messageType = unpack('CmessageType',$this->data);
            $this->data = substr($this->data,1);//舍去已经解析的消息类型
            echo $messageType['messageType']."";
            switch ($messageType['messageType']) {
                case 0x01:
            		//整车数据
            		if(!$car_data_is_analysis){
            			$this->carData();
            		}else{
            			$is_analysis_ok = true;
            		}
            		$car_data_is_analysis = true;
            		break;
            	case 0x02:
            		//驱动电机数据
            		if(!$moter_data_is_analysis){
            			$this->moterData();
            		}else{
            			$is_analysis_ok = true;
            		}
            		$moter_data_is_analysis = true;
            		break;
            	case 0x03:
            		//燃料电池数据
            		if(!$battery_data_is_analysis){
            			$this->batteryData();
            		}else{
            			$is_analysis_ok = true;
            		}
            		$moter_cata_is_analysis = true;
            		break;
            	case 0x04:
            		//发动机数据
            		if(!$engine_data_is_analysis){
            			$this->engineData();
            		}else{
            			$is_analysis_ok = true;
            		}
            		$engine_data_is_analysis = true;
            		break;
            	case 0x05:
            		//车辆位置数据
            		if(!$position_data_is_analysis){
            			$this->positionData();
            		}else{
            			$is_analysis_ok = true;
            		}
            		$position_data_is_analysis = true;
            		break;
            	case 0x06:
            		//极值数据
            		if(!$pole_value_data_is_analysis){
            			$this->poleValueData();
            		}else{
            			$is_analysis_ok = true;
            		}
            		$pole_value_data_is_analysis = true;
            		break;
            	case 0x07:
            		//报警数据
            		if(!$alert_data_is_analysis){
            			$this->alertData();
            		}else{
            			$is_analysis_ok = true;
            		}
            		$alert_data_is_analysis = true;
            		break;
            	default:
            		break;
            }
        }
        //记录日志
        $this->saveData['gear'] = json_decode($this->saveData['gear']); 
        if(filesize('./log.text') < 50000){
			file_put_contents('./log.text',date('Y-m-d H:i:s')." | ".json_encode($this->saveData)."\n",FILE_APPEND);
		}else{
			file_put_contents('./log.text',date('Y-m-d H:i:s')." | ".json_encode($this->saveData)."\n");
		}
//         	            return false;
        print_r($this->saveData);
        print_r($this->alertData);
        //发送消息
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,//
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        Gateway::sendToClient($client_id,$send_data);
        return true;
    }

    /**
     * 整车数据
     */
    public function carData()
    {
        //总长20
        $formatArr = [
        	'car_status'=>'C1',//车辆状态
            'charge_status'=>'C1',//充电状态
            'run_mode'=>'C1',//运行模式
            'speed'=>'n1',//车速
            'total_driving_mileage'=>'N1',//累计里程
            'battery_package_total_voltage'=>'n1',//总电压
            'battery_package_current'=>'n1',//总电流
            'battery_package_soc'=>'C1',//SOC
            'dc_dc'=>'C1',//DC-DC状态
            'gear'=>'C1',//档位
            'battery_package_resistance_value'=>'n1',//绝缘电阻
//             'y1'=>'n1',//预留2字节没解析（长度已经被计算在内）
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $carData = unpack($format,$this->data);
        $this->data = substr($this->data,20);
        
        //对数据进行处理
        $this->saveData['speed'] = $carData['speed'] / 10;
        $this->saveData['total_driving_mileage'] = $carData['total_driving_mileage'] / 10;
        //--档位处理开始
        $gearBinInfo = str_pad(decbin($carData['gear']),8,'0',STR_PAD_LEFT);
        $carData['gear'] = [];
        switch (substr($gearBinInfo,4)) {
            case '0000':
                $carData['gear'][] = '空档';
                break;
            case '1101':
                $carData['gear'][] = '倒档';
                break;
			case '1110':
				$carData['gear'][] = '自动D档';
				break;
            case '1111':
                $carData['gear'][] = '停车P档';
                break;
            default:
                $carData['gear'][] = bindec(substr($gearBinInfo,4)).'档';
                break;
        }
        $carData['gear'][] = substr($gearBinInfo,3,1) == 1 ? '制动有效' : '制动无效' ;
        $carData['gear'][] = substr($gearBinInfo,2,1) == 1 ? '驱动有效' : '驱动无效' ;
        $this->saveData['gear'] = json_encode($carData['gear']);
        //--档位处理结束
        //车辆状态处理开始
        if($carData['charge_status']==0x01  || $carData['charge_status']==0x02 || $carData['charge_status']==0x04){
        	$this->saveData['car_current_status'] = 2;//充电
        }else if($carData['car_status']==0x01){
        	if($this->saveData['speed'] > 0){
        		$this->saveData['car_current_status'] = 1;//行驶
        	}else {
        		$this->saveData['car_current_status'] = 0;//停止
        	}
        }else{
        	$this->saveData['car_current_status'] = 0;//停止
        }
        //车辆状态处理结束
        $this->saveData['battery_package_total_voltage'] = $carData['battery_package_total_voltage']/10;
        $this->saveData['battery_package_current'] = $carData['battery_package_current']/10-1000;
        $this->saveData['battery_package_soc'] = $carData['battery_package_soc'];
    }
    
    /**
     * 驱动电机数据
     */
    public function moterData(){
    	$unpackData = unpack('C1moter_num',$this->data);//电机个数
    	$this->data = substr($this->data,1);
    	$moter_num = $unpackData['moter_num'];
    	
    	$formatArr = [
	    	'moter_serial_num'=>'C1',//驱动电机序号
	    	'moter_status'=>'C1',//电机状态
	    	'moter_controller_temperature'=>'C1',//电机控制器温度
	    	'moter_speed'=>'n1',//电机转速
	    	'moter_torque'=>'n1',//电机转矩
	    	'moter_temperature'=>'C1',//电机温度
	    	'moter_voltage'=>'n1',//电机控制器输入电压
	    	'moter_current'=>'n1',//电机控制器直流母线电流
    	];
    	$format = '';
    	foreach($formatArr as $key=>$val){
    		$format .= $val.$key.'/';
    	}
    	$format = rtrim($format,'/');
    	
    	$moterData = [];
    	for($i = 0;$i < $moter_num;$i++){
            //分别解析各个电机数据
            $unpackData = unpack($format,$this->data);
            $moterData[] = $unpackData;
            $this->data = substr($this->data,12);
        }
        
        $this->saveData['moter_voltage'] = $moterData[0]['moter_voltage'] / 10;
        $this->saveData['moter_current'] = $moterData[0]['moter_current'] / 10 - 1000;
        $this->saveData['moter_controller_temperature'] = $moterData[0]['moter_controller_temperature'] - 40;
        $this->saveData['moter_speed'] = $moterData[0]['moter_speed'] - 20000;
        $this->saveData['moter_temperature'] = $moterData[0]['moter_temperature'] - 40;
    }
    
    public function batteryData(){
    	//只占位，不解析
    	$this->data = substr($this->data,6);
    	//探针总数
    	$unpackData = unpack('n1bougie_num',$this->data);//电机个数
    	$this->data = substr($this->data,2);
    	$bougie_num = $unpackData['bougie_num'];
    	$this->data = substr($this->data,$bougie_num+10);
    }
    
   	public function engineData(){
   		//只占位，不解析
   		$this->data = substr($this->data,5);
   	}
    
    /**
     * 车辆位置数据
     */
    public function positionData()
    {
    	$formatArr = [
    	'status'=>'C1',//定位状态
    	'longitude_value'=>'N1',//经度值
    	'latitude_value'=>'N1',//纬度值
    	];
    	$format = '';
    	foreach($formatArr as $key=>$val){
    		$format .= $val.$key.'/';
    	}
    	$format = rtrim($format,'/');
    	$positionData = unpack($format,$this->data);
    	$this->data = substr($this->data,9);
    	//数据处理
    	$statusBinInfo = str_pad(decbin($positionData['status']),8,'0',STR_PAD_LEFT);
    	$this->saveData['position_effective'] = $statusBinInfo[7] ? 1 : 0;//无效
    	$this->saveData['latitude_type'] = $statusBinInfo[6] ? 1 : 0;//1南纬
    	$this->saveData['longitude_type'] = $statusBinInfo[5] ? 1 : 0;//1西经
    	$this->saveData['longitude_value'] = $positionData['longitude_value'] / 1000000;
    	$this->saveData['latitude_value'] = $positionData['latitude_value'] / 1000000;
    }
    
    /**
     * 极值数据
     */
    public function poleValueData()
    {
    	//总长度28字节
    	$formatArr = [
	    	'battery_package_hv_serial_num'=>'C1',//最高电压动力蓄电池所在电池包序号
	    	'battery_single_hv_serial_num'=>'C1',//最高电压单体蓄电池序号
	    	'battery_single_hv_value'=>'n1',//电池单体电压最高值
	    	'battery_package_lv_serial_num'=>'C1',//最低电压动力蓄电池所在电池包序号
	    	'battery_single_lv_serial_num'=>'C1',//最低电压单体蓄电池序号
	    	'battery_single_lv_value'=>'n1',//电池单体电压最低值
	    	'battery_package_ht_serial_num'=>'C1',//最高温度动力蓄电池所在电池包序号
	    	'battery_single_ht_serial_num'=>'C1',//最高温度探针序号
	    	'battery_single_ht_value'=>'C1',//最高温度值
	    	'battery_package_lt_serial_num'=>'C1',//最低温度动力蓄电池所在电池包序号
	    	'battery_single_lt_serial_num'=>'C1',//最低温度探针序号
	    	'battery_single_lt_value'=>'C1',//最低温度值
    	];
    	$format = '';
    	foreach($formatArr as $key=>$val){
    		$format .= $val.$key.'/';
    	}
    	$format = rtrim($format,'/');
    	$unpackData = unpack($format,$this->data);
    	$this->data = substr($this->data,14);
    	//数据处理
    	$this->saveData = array_merge($this->saveData,$unpackData);
    	$this->saveData['battery_single_hv_value'] /= 1000;
    	$this->saveData['battery_single_lv_value'] /= 1000;
    	$this->saveData['battery_single_ht_value'] -= 40;
    	$this->saveData['battery_single_lt_value'] -= 40;
    }
    
    /**
     * 报警数据
     */
    public function alertData()
    {
    	$unpackData = unpack('C1level',$this->data);	//报警等级
    	$this->alertData[] = ['level'=>$unpackData['level']];
    	$this->data = substr($this->data,1);
    	
    	
    	$unpackData = unpack('N1alertSingle',$this->data);
    	$this->data = substr($this->data,4);
    	$alertSingleBinInfo = str_pad(decbin($unpackData['alertSingle']),32,'0',STR_PAD_LEFT);
    	
    	if($alertSingleBinInfo[31] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'温度差异报警'];
    	}
    	if($alertSingleBinInfo[30] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'电池高温报警'];
    	}
    	if($alertSingleBinInfo[29] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'车载储能装置类型过压报警'];
    	}
    	if($alertSingleBinInfo[28] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'车载储能装置类型欠压报警'];
    	}
    	if($alertSingleBinInfo[27] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'SOC低报警'];
    	}
    	if($alertSingleBinInfo[26] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'单体蓄电池过压报警'];
    	}
    	if($alertSingleBinInfo[25] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'单体蓄电池欠压报警'];
    	}
    	if($alertSingleBinInfo[24] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'SOC过高报警'];
    	}
    	if($alertSingleBinInfo[23] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'SOC跳变报警'];
    	}
    	if($alertSingleBinInfo[22] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'可充电储能系统不匹配报警'];
    	}
    	if($alertSingleBinInfo[21] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'电池单体一致性差报警'];
    	}
    	if($alertSingleBinInfo[20] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'绝缘报警'];
    	}
    	if($alertSingleBinInfo[19] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'DC-DC温度报警'];
    	}
    	if($alertSingleBinInfo[18] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'制动系统报警'];
    	}
    	if($alertSingleBinInfo[17] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'DC-DC状态报警'];
    	}
    	if($alertSingleBinInfo[16] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'驱动电机控制器温度报警'];
    	}
    	if($alertSingleBinInfo[15] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'高压互锁状态报警'];
    	}
    	if($alertSingleBinInfo[14] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'驱动电机温度报警'];
    	}
    	if($alertSingleBinInfo[13] != 0){
    		$this->alertData[] = ['alert_type'=>'通用','content'=>'车载储能装置类型过充'];
    	}
    	//可充电储能装置故障总数
    	$unpackData = unpack('C1batteryFaultNum',$this->data);
    	$this->data = substr($this->data,1);
    	$batteryFaultNum = $unpackData['batteryFaultNum'];
    	for($i = 0;$i < $batteryFaultNum;$i++){
    		$unpackData = unpack('N1faultCode', $this->data);
    		$this->data = substr($this->data,4);
    		$this->alertData[] = ['alert_type'=>'可充电储能装置故障','content'=>$unpackData['faultCode']];
    	}
    	//驱动电机故障
    	$unpackData = unpack('C1driveFaultNum',$this->data);
    	$this->data = substr($this->data,1);
    	$motorFaultNum = $unpackData['driveFaultNum'];
    	for($i = 0;$i < $motorFaultNum;$i++){
    		$unpackData = unpack('N1faultCode', $this->data);
    		$this->data = substr($this->data,4);
    		$this->alertData[] = ['alert_type'=>'驱动电机故障','content'=>$unpackData['faultCode']];
    	}
    	//发动机故障
    	$unpackData = unpack('C1motorFaultNum',$this->data);
    	$this->data = substr($this->data,1);
    	$motorFaultNum = $unpackData['motorFaultNum'];
    	for($i = 0;$i < $motorFaultNum;$i++){
    		$unpackData = unpack('N1faultCode', $this->data);
    		$this->data = substr($this->data,4);
    		$this->alertData[] = ['alert_type'=>'驱动电机故障','content'=>$unpackData['faultCode']];
    	}
    	//其他故障
    	$unpackData = unpack('C1otherFaultNum',$this->data);
    	$this->data = substr($this->data,1);
    	$otherFaultNum = $unpackData['otherFaultNum'];
    	for($i = 0;$i < $otherFaultNum;$i++){
    		$unpackData = unpack('N1faultCode', $this->data);
    		$this->data = substr($this->data,4);
    		$this->alertData[] = ['alert_type'=>'其他故障','content'=>$unpackData['faultCode']];
    	}
    }
}