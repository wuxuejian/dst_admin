<?php
namespace backend\classes;
use Yii;
/**
 * @name 时间过滤查询类
 * @author chengwk
 * @date 2015-10-23
 * 
 */
class TimeSearch{
	
	/**
	 * 功能: 按限制时间过滤查询
	 * 参数: $query: 查询
	 *		 $search_timeduan: 时间段,表示今天,昨天,本周等
	 *		 $start_time,$end_time: 明确指定的开始-截至查询日期
	 *		 $searchField: 数据表中那个要求作时间限制的字段
	 */
	public static function timeSeach($query,$search_timeduan,$start_time,$end_time,$searchField){
		if($searchField){
			if($start_time || $end_time || $search_timeduan){
				//指定的开始时间和截至时间较时间段要优先考虑
				if($start_time || $end_time){ 
					if($start_time && $end_time){
						$start_time .= ' 00:00:00'; //注意:传递的这两个都只是日期Y-m-d格式，要拼接好再用
						$end_time   .= ' 23:59:59';
						$query->andFilterWhere(['>=',$searchField,strtotime($start_time)]);
						$query->andFilterWhere(['<=',$searchField,strtotime($end_time)]);
					}elseif($start_time && !$end_time){
						$start_time .= ' 00:00:00';
						$query->andFilterWhere(['>=',$searchField,strtotime($start_time)]);
					}else{
						$end_time   .= ' 23:59:59';
						$query->andFilterWhere(['<=',$searchField,strtotime($end_time)]);
					}
				}else{
					$timeBucket = self::getTimeBucket($search_timeduan); 
					$query->andFilterWhere(['>=',$searchField,strtotime($timeBucket['timeFrom'])]);
					$query->andFilterWhere(['<=',$searchField,strtotime($timeBucket['timeTo'])]);
				}
			}
		}
		return $query;
	} 
	
	/**
	 * 功能: 获取开始到结束的时间段
	 * 参数: 今天,昨天,本周,上一周,本月,上一月,年本度,上一年 
	 */
	public static function getTimeBucket($time){		
		$arr = [];
		switch($time){
			case 'today':
				$arr['timeFrom'] = date('Y-m-d H:i:s',mktime(0,0,0,date("m"),date("d"),date("Y")));
				$arr['timeTo']   = date('Y-m-d H:i:s',mktime(23,59,59,date("m"),date("d"),date("Y")));	
				break;
			case 'yesterday':
				$arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
				$arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-1,date("Y")));
				break;
			case 'thisWeek':
				$arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y")));
				$arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
				break;
			case 'lastWeek':
				$arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-date("w")+1-7,date("Y")));
				$arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));
				break;
			case 'thisMonth':
				$arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),1,date("Y")));
				$arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
				break;
			case 'lastMonth':
				$arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m")-1,1,date("Y")));
				$arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y")));
				break;
			case 'thisYear':
				$arr['timeFrom'] = date('Y-m-d H:i:s', mktime(0,0,0,1,1,date('Y')));
				$arr['timeTo']   = date('Y-m-d H:i:s', mktime(23,59,59,12,31,date('Y')));
				break;
			case 'lastYear':
				$arr['timeFrom'] = date('Y-m-d H:i:s', mktime(0,0,0,1,1,date('Y')-1));
				$arr['timeTo']   = date('Y-m-d H:i:s', mktime(23,59,59,12,31,date('Y')-1));	
				break;	
		}
		return $arr;
	}
	
}
?>