<div id="easyui-panel-car-overview-index">  
    <form id="easyui-from-car-overview-index" class="easyui-from" style="padding-top:40px;text-align:center;">
        <input
            class="easyui-textbox"
            name="vin_or_platenumber"
            required="true"
            style="width:600px;height:30px;"
            prompt="请输入车牌号或车架号..."
        />
        <a href="javascript:void(0)" onclick="CarOverviewIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search',height:'30px'">搜&nbsp;索</a>
    </form>
</div>
<script>
    var CarOverviewIndex = {
        init: function(){
            $('#easyui-panel-car-overview-index').panel({   
                "title": '全局搜索',
                "fit": true,
                "border": false,
                "iconCls": 'icon-search'
            });
            var easyuiForm = $('#easyui-from-car-overview-index');
        },
        search: function(){
            var easyuiForm = $('#easyui-from-car-overview-index');
            if(!easyuiForm.form('validate')){
                return false;
            }
            $('#easyui-panel-car-overview-index').panel('refresh',"<?= yii::$app->urlManager->createUrl(['car/overview/search']); ?>&"+easyuiForm.serialize());
        }
    };
    CarOverviewIndex.init();
</script>