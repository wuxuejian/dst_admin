<form id="cardChargeCardIndex_editWin_form" method="post" style="padding:5px;">
    <input type="hidden" name="cc_id" />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">串口号</div>
            <div class="ulforform-resizeable-input">
                <select
                    class="easyui-combobox"
                    name="port"
                    style="width:180px;"
                >
                    <option value="1">COM1</option>
                    <option value="2">COM2</option>
                    <option value="3">COM3</option>
                    <option value="4">COM4</option>
                    <option value="5">COM5</option>
                    <option value="6">COM6</option>
                    <option value="7">COM7</option>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">电卡编号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="cc_code"
                    style="width:180px;"
                    required="true"
                    missingMessage="请输入电卡编号！"
                    readonly=true
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">电卡类型</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="cc_type" style="width:180px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['cc_type'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">电卡状态</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="cc_status" style="width:180px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['cc_status'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">制卡日期</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datebox"
                    style="width:180px;"
                    name="cc_start_date"
                    required="true"
                    validType="date"
                    value="<?php echo date('Y-m-d'); ?>"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">有效日期</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datebox"
                    style="width:180px;"
                    name="cc_end_date"
                    required="true"
                    validType="date"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">所属会员</div>
            <div class="ulforform-resizeable-input">
                <input
                    id="cardChargeCardIndex_edit_chooseVip"
                    name="cc_holder_id"
                    style="width:180px"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="cc_mark"
                    style="width:482px;height:86px;"
                    data-options="multiline:true"
                    validType="length[200]"
                />
            </div>
        </li>
    </ul>
</form>
<div style="padding:10px;text-align:right;">
    <button
        onclick="cardChargeCardIndex_editWin.rewrite()"
        class="easyui-linkbutton"
        iconCls='icon-reload'
    >重写卡</button>
</div>
<script>
    var cardChargeCardIndex_editWin = {
        "initData": <?= json_encode($initData); ?>, 
        "init": function(){
            // 初始化电卡开卡会员combobox
            $('#cardChargeCardIndex_edit_chooseVip').combogrid({
                panelWidth: 500,
                panelHeight: 210,
                required: true,
                missingMessage: '请从下拉列表里选择会员！',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var textbox = _combogrid.combogrid('textbox');
                    var text = textbox.val();
                    var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                    if(text && rows.length < 1 && value == text){
                        $.messager.show(
                            {
                                title: '无效值',
                                msg:'【' + text + '】不是有效值！请重新检索并选择一个会员！'
                            }
                        );
                        _combogrid.combogrid('clear');
                    }
                },
                delay: 800,
                mode:'remote',
                idField: 'vip_id',
                textField: 'vip_code',
                url: "<?= yii::$app->urlManager->createUrl(['card/charge-card/get-vip']); ?>",
                method: 'get',
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                columns: [[
                    {field:'vip_id',title:'ID',width:20,align:'center'},
                    {field:'vip_code',title:'会员编号',width:140,align:'center'},
                    {field:'vip_mobile',title:'会员手机',width:90,align:'center'},
                    {field:'vip_name',title:'会员名称',width:140,halign:'center'}
                ]]
            });
            //装载原始数据
            $('#cardChargeCardIndex_editWin_form').form('load',cardChargeCardIndex_editWin.initData.ChargeCardInfo);
            // 查旧客户以赋值显示text,因为combogrid远程查询第一页不一定存在该客户而显示为id
            var vip = {vipId: cardChargeCardIndex_editWin.initData.ChargeCardInfo.cc_holder_id};
            $('#cardChargeCardIndex_edit_chooseVip').combogrid('grid').datagrid('load',vip);
        },
        rewrite: function(){
            $.messager.confirm('操作确定','您确定要对改卡进行重发卡操作，本操作只能写入卡数据，无法写入卡内余额，如需要添加余额可通过调剂操作完成！',function(r){
                if(r){
                    var easyuiForm = $('#cardChargeCardIndex_editWin_form');
                    var port = easyuiForm.find('select[comboname=port]').textbox('getValue');
                    var cardNo = cardChargeCardIndex_editWin.initData
                        .ChargeCardInfo.cc_code;
                    var writeResult = KLChargeCard.fk(port,cardNo);
                    if(writeResult.status){
                        $.messager.alert('操作成功','重写卡成功！','info');
                    }else{
                        $.messager.alert('操作失败','重写卡失败['+writeResult.info+']！','error');
                    }
                }
            });
        }
    }
    // 执行初始化函数
    cardChargeCardIndex_editWin.init();
</script>