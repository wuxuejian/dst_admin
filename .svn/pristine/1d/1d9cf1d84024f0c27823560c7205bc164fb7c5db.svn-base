<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">

<style>
*{border:0; margin:0;}
body{background: #ddd}
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
			<div class="title">车辆 <?php echo $result['plate_number']?> 工单信息</div>
			<div class="number">工单号：<span><?php echo $result['order_no']?></span></div>
			
			<div class="se_title">维修登记信息</div>
			<div class="twoline">
				<div><span class='min-title'>故障来源：</span><span class='content'><?php echo $result['type']?></span></div>
				<div><span class='min-title'>故障车辆：</span><span class='content'><?php echo $result['plate_number']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>故障发生时间：</span><span class='content'><?php echo !empty($result['fault_start_time']) ? date('Y-m-d H:i',$result['fault_start_time']) :''?></span></div>
				<div><span class='min-title'>故障反馈时间：</span><span class='content'><?php echo !empty($result['feedback_time']) ? date('Y-m-d H:i',$result['feedback_time']) :''?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>故障反馈人：</span><span class='content'><?php echo $result['feedback_name']?></span></div>
				<div><span class='min-title'>联系电话：</span><span class='content'><?php echo $result['tel']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>本方受理人：</span><span class='content'><?php echo $result['accept_name']?></span></div>
				<div><span class='min-title'>记录时间：</span><span class='content'><?php echo date('Y-m-d H:i',$result['time']) ?></span></div>
			</div>
			<div class="oneline"><span class='min-title'>故障地点：</span><span class='content'><?php echo $result['fault_address'];?></span></div>
			<div class="oneline"><span class='min-title'>现场勘查故障描述：</span><span class='content'><?php echo $result['scene_desc']?></span></div>
			<div class="oneline"><span class='min-title'>现场处理结果：</span><span class='content'><?php echo $result['scene_result']?></span></div>
			<div class="twoline">
				<div><span class='min-title'>维修方式：</span><span class='content'><?php

				//echo $result['maintain_way']==1 ? '进场维修':'现场维修';
				switch ($result['maintain_way']){
					case 1:
						echo '进厂维修';
						break;
					case 2:
						echo '现场维修';
						break;
					case 3:
						echo '自修';
						break;
				}
				
				?></span></div>
				<div><span class='min-title'>维修厂站：</span><span class='content'><?php echo $result['maintain_scene'];?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>维修方联系人：</span><span class='content'><?php echo $result['maintain_name'];?></span></div>
				<div><span class='min-title'>联系电话：</span><span class='content'><?php echo $result['maintain_tel']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>维修技工：</span><span class='content'><?php echo $result['maintain_worker'];?></span></div>
				<div><span class='min-title'>联系方式：</span><span class='content'><?php echo $result['maintain_worker_tel']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>进场维修单号：</span><span class='content'><?php echo $result['maintain_no']?></span></div>
				<div><span class='min-title'>预计完成时间：</span><span class='content'><?php  echo !empty($result['expect_time']) ? date('Y-m-d H:i',$result['expect_time']):''; ?></span></div>
			</div>
			
			
			<div class="se_title">维修结果</div>
			<div class="oneline"><span class='min-title'>故障处理结果：</span><span class='content'><?php echo $result['fault_result']?></span></div>
			<div class="oneline"><span class='min-title'>故障引发原因：</span><span class='content'><?php echo $result['fault_why']?></span></div>
			<div class="oneline"><span class='min-title'>故障维修方法：</span><span class='content'><?php echo $result['maintain_method']?></span></div>
			<div class="twoline">
				<div><span class='min-title'>维修出厂日期：</span><span class='content'><?php echo !empty($result['leave_factory_time'])? date('Y-m-d H:i',$result['leave_factory_time']) :'';?></span></div>
				<div><span class='min-title'>出厂接车人员：</span><span class='content'><?php echo $result['jieche_name']?></span></div>
			</div>
			<div class="twoline">
				<div><span class='min-title'>替换车归还时间：</span><span class='content'><?php echo !empty($result['return_replace_time']) ? date('Y-m-d H:i',$result['return_replace_time']):'';?></span></div>
				<!--  <div><span class='min-title'>还车方式：</span><span class='content'></span></div>-->
			</div>
			
			<div class="se_title">故障原因</div>
			<?php foreach ($maintain_faults as $maintain_fault):?>
				<div class="oneline"><span class='min-title'>故障分类：</span><span class='content'><?php echo $maintain_fault->big_category;?></span></div>
				<div class="oneline"><span class='min-title'>故障名称：</span><span class='content'><?php echo $maintain_fault->category;?></span></div>
				<div class="oneline"><span class='min-title'>故障编码：</span><span class='content'><?php echo $maintain_fault->total_code;?></span></div>
			<?php endforeach;?>
			
		</div>
		
	</div>
</body>
</html>