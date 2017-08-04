<form
    id="easyui-form-car-office-car-register-return"
    class="easyui-form"
    
    style="padding:10px;" method="post"
>
<input type="hidden" name="car_id" value="<?=$id; ?>" />
        <table cellpadding="8" cellspacing="0">
        
            <tr>
                <td><div style="width:85px;text-align:right;">还车时间</div></td>
                <td>
                    <!-- <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="return_time"
                        required="true"
                        missingMessage="请选择还车日期！"
                        validType="date"
                    /> -->
                    <input class="easyui-datetimebox" name="return_time" 
                        data-options="required:true,showSeconds:false" value="" style="width:150px" missingMessage="请选择还车日期！">
                </td> 
            </tr>

            <tr>
                <td><div style="width:85px;text-align:right;">还车时总里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="return_distance"
                        style="width:160px;"
                        validType="length[50]"
                    />
                </td> 
                <td><div style="width:85px;text-align:right;">剩余续航里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="remain_distance_return"
                        style="width:160px;"
                        validType="length[50]"
                    />
                </td>  
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">备注</div></td>
                <td>
                    <text
                        class="easyui-textbox"
                        name="note_return"
                        style="width:300px;"
                        validType="length[100]"
                    />
                </td> 
            </tr>


        </table>


    </form>
<!-- </div> -->
<!-- <iframe id="iframe-count-driver-uploadimage" name="iframe-count-driver-uploadimage" style="display:none;"></iframe> -->
<!-- <div id="easyui-dialog-count-driver-uploadimage"></div> -->

<div id="easyui-form-car-office-car-register-return-uploadimage"></div>
<script type="text/javascript">
    // CarOfficeCarRegisterIndex = {
       // init: function(){
            //初始化-车辆combogrid
            $('#easyui-form-car-office-car-register-return-index').combogrid({
                panelWidth: 350,
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
                url: '<?= yii::$app->urlManager->createUrl(['car/office-car-register/get-cars-by-add']); ?>',
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
                    //{field:'vehicle_dentification_number',title:'车架号',width:150,align:'center'}
                ]]
                /*onSelect: function(){
                 temp_car_id = $('#easyui-form-car-fault-register-carCombogrid').combogrid('grid').datagrid('getSelected').id;
                 $('#easyui-form-car-fault-register-claimId').combogrid({
                      queryParams: {
                        id:temp_car_id
                     }
                 });
                 console.log(temp_car_id);
              },*/
            });
        //}
   // };
   //CarOfficeCarRegisterIndex.init();



  
   $('#dep_id').select(function() {
   alert ('123');
});
</script>