<div style="padding:10px;">
    <form id="easyui-form-car-stock-add">
    	<input type="hidden" name="car_type" value="2">
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">车牌号</div>
                <div class="ulforform-resizeable-input">
                    <input
                        id="easyui-form-car-stock-add-carCombogrid"
                        name="car_id"
                        style="width:180px;"
                        />
                </div>
            </li>
        </ul>
    </form>
</div>

<div id="easyui-dialog-car-stock-add-uploadimage"></div>
<script type="text/javascript">
    var CarFaultRegister = {
        init: function(){
            //初始化-车辆combogrid
            $('#easyui-form-car-stock-add-carCombogrid').combogrid({
                panelWidth: 450,
                panelHeight: 200,
                required: true,
                missingMessage: '请输入车牌号/车架号检索后从下拉列表里选择一项！',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var text = _combogrid.combogrid('textbox').val();
                    var row = _combogrid.combogrid('grid').datagrid('getSelected');
                    if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                        if(text && value == text){
                            $.messager.show(
                                {
                                    title: '无效值',
                                    msg:'【' + text + '】不是有效值！请重新输入车牌号/车架号检索后，从下拉列表里选择一项！'
                                }
                            );
                            _combogrid.combogrid('clear');
                        }
                    }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                        if(!row.plate_number){
                            _combogrid.combogrid('setText', row.vehicle_dentification_number);
                            //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                        }
                    }
                },
                delay: 800,
                mode:'remote',
                idField: 'id',
                textField: 'plate_number',
                url: '<?= yii::$app->urlManager->createUrl(['car/stock/get-cars-by-add']); ?>',
                method: 'get',
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                rownumbers: true,
                columns: [[
                    {field:'id',title:'车辆ID',width:40,align:'center',hidden:true},
                    {field:'plate_number',title:'车牌号',width:100,align:'center'},
                    {field:'vehicle_dentification_number',title:'车架号',width:150,align:'center'}
                ]]
            });
        }
    };
    CarFaultRegister.init();
</script>