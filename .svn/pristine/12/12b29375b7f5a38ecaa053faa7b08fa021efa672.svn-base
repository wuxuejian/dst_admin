<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">

<style>
*{border:0; margin:0;}
.print-contain{background: #ddd}
.print-box{background: #fff; width: 595px; height: auto; margin:0 auto; margin-bottom:20px;box-sizing: border-box; padding: 15px 30px;position: relative; color: #555; font-size: 12px;}
.print-box .title,.print-box .se_title{font-size: 14px; color: #000; text-align: center; font-weight: bold;}
.print-box .number{position: absolute; top:20px; right: 30px;}
.print-box .se_title{text-align: left; padding:20px 0; border-bottom: 1px solid #ddd;}
.print-box .oneline,.print-box .twoline{padding: 10px 0 5px;}
.print-box .twoline:before,.print-box .twoline:after{display: table;content: " ";}
.print-box .twoline:after{clear: both;}
.print-box .twoline div{width: 50%; float: left;}
.print-box .oneline .min-title,.print-box .twoline .min-title{width: 8em; display:inline-block; vertical-align: top; line-height: 1.4;}
.print-box .oneline .content{width: 34em; display: inline-block;line-height: 1.4;}
.print-box .twoline .content{width: 12em; display: inline-block;line-height: 1.4;}		
</style>
</head>
<body>
	<div class='print-contain'>
		<div class='print-box'>
			<div class="title">车辆 <?php echo $result['car_no']?> 工单信息</div>
			<div class="number">工单号：<span><?php echo $result['order_no']?></span></div>
			<div class="se_title">接单信息</div>
			<div class="twoline">
				<div><span class='min-title'>工单类型：</span><span class='content'><?php echo $result['type'];?></span></div>
				<div><span class='min-title'>工单来源：</span><span class='content'><?php echo $result['source']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>报修人姓名：</span><span class='content'><?php echo $result['repair_name']?></span></div>
				<div><span class='min-title'>来电号码：</span><span class='content'><?php echo $result['tel']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>来电时间：</span><span class='content'><?php echo !empty($result['tel_time']) ? date('Y-m-d H:i',$result['tel_time']) :'';?></span></div>
				<div><span class='min-title'>紧急程度：</span><span class='content'><?php echo $result['urgency']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>车牌号：</span><span class='content'><?php echo $result['car_no']?></span></div>
				<div><span class='min-title'>客户公司名称：</span><span class='content'><?php echo $result['customer_name']?></span></div>
			</div>
			<div class="oneline"><span class='min-title'>故障发生时间：</span><span class='content'><?php echo !empty($result['fault_start_time']) ? date('Y-m-d H:i',$result['fault_start_time']):'';?></span></div>
			<div class="oneline"><span class='min-title'>故障地点：</span><span class='content'><?php echo $result['address']?>,<?php echo $result['bearing']?></span></div>
			<div class="oneline"><span class='min-title'>工单内容简述：</span><span class='content'><?php echo $result['desc']?></span></div>
			<div class="oneline"><span class='min-title'>来电内容记录：</span><span class='content'><?php echo $result['tel_content']?></span></div>
			<div class="oneline"><span class='min-title'>所需服务：</span><span class='content'><?php echo $result['need_serve']?></span></div>
			
			
			
			<div class="se_title">派单信息</div>
			<div class="twoline">
				<div><span class='min-title'>派单对象：</span><span class='content'><?php echo $result['assign_name'];?></span></div>
				<div><span class='min-title'>确认时间：</span><span class='content'><?php echo !empty($result['confirm_time']) ? date('Y-m-d H:i',$result['confirm_time']):'';?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>已听取录音：</span><span class='content'><?php echo !empty($result['is_voice']) ? '是':'否'?></span></div>
				<div><span class='min-title'>已电话回访：</span><span class='content'><?php echo !empty($result['is_visit']) ? '是':'否'?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>需要出外勤：</span><span class='content'><?php echo !empty($result['is_attendance']) ? '是':'否' ?></span></div>
				<div><span class='min-title'>携带设备：</span><span class='content'><?php echo $result['carry']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>需申请用车：</span><span class='content'><?php echo !empty($result['is_use_car']) ? '是':'否' ?></span></div>
				<div><span class='min-title'>外勤用车车牌号：</span><span class='content'><?php echo $result['use_car_no']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>电话回访时间：</span><span class='content'><?php echo !empty($result['visit_time']) ? date('Y-m-d H:i',$result['visit_time']):'';?></span></div>
			</div>
			<div class="se_title">外勤服务信息</div>
			<div class="oneline"><span class='min-title'>抵达现场时间：</span><span class='content'><?php echo !empty($result['arrive_time']) ? date('Y-m-d H:i',$result['arrive_time']):'';?></span></div>
			<div class="oneline"><span class='min-title'>现场故障描述：</span><span class='content'><?php echo $result['scene_desc']?></span></div>
			<div class="oneline"><span class='min-title'>现场处理结果：</span><span class='content'><?php echo $result['scene_result']?></span></div>
			<div class="twoline">
				<div><span class='min-title'>维修方联系人：</span><span class='content'><?php echo $result['maintain_name']?></span></div>
				<div><span class='min-title'>联系电话：</span><span class='content'><?php echo $result['maintain_tel']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>进厂维修单号：</span><span class='content'><?php echo $result['maintain_no']?></span></div>
				<div><span class='min-title'>预计完结时间：</span><span class='content'><?php echo !empty($result['expect_time']) ? date('Y-m-d H:i',$result['expect_time']):'';?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>是否进厂维修：</span><span class='content'><?php echo !empty($result['is_go_scene']) ? '是':'否';?></span></div>
				<div><span class='min-title'>维修场站：</span><span class='content'><?php echo $result['maintain_scene']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>是否替换车辆：</span><span class='content'><?php echo !empty($result['replace_car']) ? '是':'否';?></span></div>
				<div><span class='min-title'>替换车：</span><span class='content'><?php  echo $result['replace_car']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>替换开始时间：</span><span class='content'><?php echo !empty($result['replace_start_time']) ? date('Y-m-d H:i',$result['replace_start_time']):'';?></span></div>
				<div><span class='min-title'>预计归还时间：</span><span class='content'><?php echo !empty($result['replace_end_time']) ? date('Y-m-d H:i',$result['replace_end_time']):'';?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>外勤过路费：</span><span class='content'><?php echo $result['field_tolls']?></span></div>
				<div><span class='min-title'>外勤停车费：</span><span class='content'><?php echo $result['parking']?></span></div>
			</div>
		</div>
	</div>
</body>
</html>