<div style="color:red;text-align:center;line-height:24px;">注意：无法导出跨月份数据，请按月导！</div>
<form id="easyui_form_carmonitor_export_index" method="post" style="padding:5px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">车辆</div>
            <div class="ulforform-resizeable-input">
                <input
                    id="carmonitor_export_index_combogrid_car_vin"
                    name="car_vin"
                    style="width:100%"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">开始时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datebox"
                    name="start_date"
                    value="<?= date('Y-m-d'); ?>"
                    required="true"
                    validType="date"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">结束时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datebox"
                    name="end_date"
                    validType="date"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">导出列</div>
            <div class="ulforform-resizeable-input">
                <div style="padding-left:58px;">
                    <a
                        href="javascript:void(0)"
                        onclick="CarmonitorExportIndex.select(0)">全选</a>
                    /<a
                        href="javascript:void(0)"
                        onclick="CarmonitorExportIndex.select(1)">反选</a>
                    /<a
                        href="javascript:void(0)"
                        onclick="CarmonitorExportIndex.select(2)">全不选</a>
                </div>
                <ul style="overflow:hidden;list-style:none;">
                <?php
                foreach($attributes as $key=>$val){
                ?>
                <li style="width:120px;float:left;padding:10px;line-height:22px;"><label><input type="checkbox" name="attribute[]" value="<?= $key ?>" /><?= $val ?></label></li>
                <?php
                }
                ?>
                </ul>
            </div>
        </li>
    </ul>
</form>
<script>
    var CarmonitorExportIndex = {
        params: {
            url: {
                "combogridCar": "<?= yii::$app->urlManager->createUrl(['system/combogrid/car-list']); ?>"
            }
        },
        init: function(){
            // 初始化电卡开卡会员combobox
            $('#carmonitor_export_index_combogrid_car_vin').combogrid({
                panelWidth: 500,
                panelHeight: 210,
                required: true,
                missingMessage: '请选择或输入车架号！',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var textbox = _combogrid.combogrid('textbox');
                    var text = textbox.val();
                    var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                    if(text && rows.length < 1 && value == text){
                        $.messager.show({
                            title: '无效值',
                            msg:'【' + text + '】不是有效值！'
                        });
                        _combogrid.combogrid('clear');
                    }
                },
                delay: 800,
                mode:'remote',
                idField: 'vehicle_dentification_number',
                textField: 'vehicle_dentification_number',
                url: this.params.url.combogridCar,
                method: 'get',
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                columns: [[
                    {field:'plate_number',title:'车牌号',width:80,align:'center'},
                    {field:'vehicle_dentification_number',title:'车架号',width:100,align:'center'}
                ]]
            });
        },
        select: function(type){
            var easyuiForm = $('#easyui_form_carmonitor_export_index');
            easyuiForm.find('input[name="attribute[]"]').each(function(){
                switch(type){
                    case 0:
                        //全选
                        this.checked = true;
                        break;
                    case 1:
                        //反选
                        if(this.checked){
                            this.checked = false;
                        }else{
                            this.checked = true;  
                        }
                        break;
                    case 2:
                        //全不选
                        this.checked = false;
                        break;
                }
            });
        }
    };
    // 执行初始化函数
    CarmonitorExportIndex.init();
</script>