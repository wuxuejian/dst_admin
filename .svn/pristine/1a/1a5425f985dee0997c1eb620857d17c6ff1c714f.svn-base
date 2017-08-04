<div style="padding:10px;">
    <form id="easyui-form-car-stock-replace">
    	<input type="hidden" name="id" value="<?=$id; ?>" />
        <table cellpadding="8" cellspacing="0">
        	<tr>
                <td><div style="width:85px;text-align:right;">替换车辆</div></td>
                <td>
                    <input disabled="disabled" style="width:180px;" value="<?=$car['plate_number']?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">被替换车辆</div></td>
                <td>
                    <input
                        id="easyui-form-car-stock-replace-carCombogrid"
                        name="replace_car_id"
                        style="width:180px;"
                        />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">替换开始时间</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="replace_start_time"
                        validType="date"
                        required="true"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">预计归还时间</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="replace_end_time"
                        validType="date"
                        required="true"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">替换原因</div></td>
                <td colspan="3">
                    <textarea style="width:480px;height:100px;" class="easyui-areabox" name="replace_desc"></textarea>
                </td>
            </tr>
        </ul>
    </form>
</div>

<div id="easyui-dialog-car-stock-replace-uploadimage"></div>
<script type="text/javascript">
    var CarFaultRegister = {
        init: function(){
            //初始化-车辆combogrid
            $('#easyui-form-car-stock-replace-carCombogrid').combogrid({
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
                url: '<?= yii::$app->urlManager->createUrl(['car/stock/get-cars-by-replace']); ?>',
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