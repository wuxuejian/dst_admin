<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\View;

// 获取商户的ID
$bossInfo = Yii::$app->session->get('bBossInfo');
$bossId = $bossInfo['id'];
//$bossId = '';
?>
<script type="text/javascript">
  function ceshi(){
     var url = '<?php echo Url::to(['select-man/index','bossId'=>$bossId,'winId'=>'test_window']);?>';
  	 $('#test_window').window('open');
	 $('#test_window').window('refresh', url);
  }
</script>
&nbsp;<a href="javascript:void(0)" class="easyui-linkbutton" iconCls='icon-ok' onclick="ceshi()">测试选择部门</a>
<div>
	  <div id="test_window" class="easyui-window" title="选择人员" data-options="iconCls:'icon-add',modal:true,collapsible:false,minimizable:false,maximizable:false,closed:true,cache:true" style="width:800px;height:600px;">
  </div>
</div>