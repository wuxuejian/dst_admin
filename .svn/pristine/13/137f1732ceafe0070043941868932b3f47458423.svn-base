<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\View;

/**
 * @name 部门、角色选择视图
 * @author tanbenjiang
 * @date 2015-8-22
 */
// 根据是否有bossId显示不同的视图风格
if(isset($bossId) && $bossId!=0){
	$show = true;
}else{
	$show = false;
}
?>

<link rel="stylesheet" type="text/css" href="css/company/selectman.css">
<script type="text/javascript" src="js/company/selectman.js"></script>
<script type="text/javascript">
	selectMan.init();
</script>

<!--搜索框开始-->
	<form name="select_man_search_form" id="select_man_search_form" action="<?php echo Url::to(['select-man/index'])?>" method="POST">
	<input type="hidden" id="mod" name="mod" value="1">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tbody><tr>
				<td><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody><tr>
							<td width="3%" background="images/company/choose_man03.gif"></td>
							<td width="94%" background="images/company/choose_man03.gif"><table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tbody><tr>
										<td height="30" align="center" style="padding-top: 30px;">
                         <input id="glo_checkall" name="glo_checkall" type="checkbox" onclick="selectMan.checkall(this);"><label for="glo_checkall" style="font-size: 14px;">全选中</label>&nbsp;
                         <input id="glo_expall" name="glo_expall" type="checkbox" onclick="selectMan.expandall(this);"><label for="glo_expall" style="font-size: 14px;">全展开</label>&nbsp;
						 <img src="images/company/choose_man06.jpg" alt="选择确定" style="cursor:pointer; margin-top:5px;" onclick="selectMan.doSelected()">
                                        </td>
										<td style="padding-top: 30px;">
                                            <input type="text" name="keyword" id="select_man_search_form_keyword" value="" style="width:150px;">&nbsp;
                                            <img src="images/company/choose_man05.jpg" alt="搜索" style="cursor:pointer;" onclick="selectMan.doSearch('<?php echo Url::to(['select-man/index','bossId'=>$bossId])?>','<?php echo $winId;?>');">
                                            <!--<img src="images/company/choose_man08.jpg" alt="清空" style="cursor:pointer;" onclick="selectMan.doClean()">-->
                                        </td>
									</tr>
								</tbody></table></td>
							<td width="3%"><img src="images/company/choose_right.gif" width="29" height="72"></td>
						</tr>
					</tbody></table></td>
			</tr>
		</tbody></table>
	</form>
<!--搜索框结束/-->
	
	
	
<!--tabs标签开始-->	
<div class="tab_nav">
		<ul>
			<li id="depart" class="active">组织架构</li>
			<!--<li id="usergroup"><a href="javascript:;">个人用户组</a></li>
			<li id="group"><a href="javascript:;">系统分组</a></li>-->
			<li id="role">角色分组</li>
		</ul>
</div>
<!--tabs标签结束/-->
	
	
	
	<!-- 组织架构人员-->
	<div id="depart_con">
	  <div id="userDiv">
	  		  <!--商户开始-->
	          <?php foreach($bossInfo as $bossKey=>$bossValue){?>
	  			<div class="sub1">
					<input type="checkbox" class="dep_1" onclick="selectMan.sela('bos_<?php echo $bossValue['id']?>',this)">
					<label onclick="selectMan.shitem('bos_<?php echo $bossValue['id']?>')"><?php echo $bossValue['company_name']?></label>
				</div>
				
				<!--部门开始-->
				<div id="bos_<?php echo $bossValue['id']?>" level="1" <?php if(!$show){ echo "style='display:none'";}?>>
					<?php 
						foreach($department as $depKey=>$depValue){
							if($depValue['boss_id']==$bossValue['id']){
					?>
					<div class="sub2">
						<input type="checkbox" class="dep_<?php echo $depValue['id'];?>" onclick="selectMan.sela('dep_<?php echo  $depValue['id'];?>',this)"><label onclick="selectMan.shitem('dep_<?php  echo $depValue['id'];?>')"><?php echo $depValue['department']?></label>
					</div><!--sub2/-->
					
				
					<div id="dep_<?php echo $depValue['id']?>" level="2" style="display:none">
						<div class="memberlist memberlist2">
							<?php foreach($depValue['produceUser'] as $userKey=>$userValue){?>
							<!--部门下的人员开始-->
							<li><input type="checkbox" name="user" id="<?php echo $userValue['itemid']?>" value="<?php echo $userValue['worker_name']?>" title="<?php echo $userValue['user_name']?>"><label for="u_<?php echo $userValue['itemid']?>"><?php echo $userValue['user_name']?><!--(<span class="blue">职位名称</span>)--></label></li>
							<?php } ?>
						</div><!--memberlist/-->
						<div class="clear"></div>
					</div><!--dep/-->
				<?php 
						   } // if($depValue['boss_id'])
				 		} // foreach($department)
				?>
				</div>
				<!--部门结束/-->
				
			  <?php }?><!--// foreach($bossInfo)-->	
			  <!--商户结束/-->	
		</div><!--userDiv/-->
	  </div>
	  <!--组织架构人员结束/-->				   	



	<!--个人分组成员-->
	<!--<div id="usergroup_con" style="display:none;">
	</div>-->	
	<!--个人分组成员-->
	
    <!--系统分组-->
	<!--<div id="group_con" style="display:none;">
		<div class="sub1"><input type="checkbox" class="group_1" onclick="sela(&#39;group_1&#39;,this)"><label onclick="shitem(&#39;group_1&#39;)">一般用户组</label></div>
		<div class="memberlist memberlist1" id="group_1" level="2">
			<li><input type="checkbox" name="user" id="group_u_114" value="csry15" title="测试人员15"><label for="group_u_114">测试人员15</label></li>
			<li><input type="checkbox" name="user" id="group_u_1" value="admin" title="超级管理员"><label for="group_u_1">超级管理员<span style="color:#F00;">[在线]</span></label></li>
		</div>
    	<div class="clear"></div>
		<div class="sub1"><input type="checkbox" class="group_3" onclick="sela(&#39;group_3&#39;,this)"><label onclick="shitem(&#39;group_3&#39;)">新闻发布组</label></div>
		<div class="memberlist memberlist1" id="group_3" level="2">
			<li><input type="checkbox" name="user" id="group_u_3" value="wangyan" title="王雁"><label for="group_u_3">王雁</label></li>
			<li><input type="checkbox" name="user" id="group_u_7" value="liuxiaoqing" title="柳晓庆"><label for="group_u_7">柳晓庆</label></li>
		</div>
		<div class="clear"></div>
    </div>-->
	
	
	
<!-- 角色分类人员 -->
<div id="role_con" style="display:none;">
	<?php foreach($bossInfo as $bossKey=>$bossValue){?>   
	    <div class="sub1">
			<input type="checkbox" onclick="selectMan.sela('bos_role_<?php echo $bossValue['id']?>',this)">
			<label onclick="selectMan.shitem('bos_role_<?php echo $bossValue['id']?>')"><?php echo $bossValue['company_name']?></label>
		</div>
	
	    <div id="bos_role_<?php echo $bossValue['id']?>" level="1" <?php if(!$show){ echo "style='display:none'";}?>>
			<?php 
				foreach($role as $roleKey=>$roleValue){
					if($roleValue['boss_id']==$bossValue['id']){
			?>
			<div class="sub2">
				<input type="checkbox" class="role_<?php echo $roleValue['id']?>" onclick="selectMan.sela('role_<?php echo $roleValue['id']?>',this)"><label onclick="selectMan.shitem('role_<?php echo $roleValue['id']?>')"><?php echo $roleValue['name']?></label>
			</div>
		
			<div class="memberlist memberlist2" id="role_<?php echo $roleValue['id']?>" level="2" style="display:none">
				<?php foreach($roleValue['busUser'] as $userKey=>$userValue){?>
					<li><input type="checkbox" name="user" id="role_u_<?php echo $userValue['itemid']?>" value="<?php echo $userValue['worker_name']?>" title="<?php echo $userValue['user_name']?>"><label for="role_u_<?php echo $userValue['itemid']?>"><?php echo $userValue['user_name']?></label></li>
				<?php }?>
			</div>
			<div class="clear"></div>
		<?php 
				} // if
			} // foreach($role)
		?>
	   </div><!--bos/-->
	<?php }?>
</div>
   <!-- 角色分类人员结束/-->
 