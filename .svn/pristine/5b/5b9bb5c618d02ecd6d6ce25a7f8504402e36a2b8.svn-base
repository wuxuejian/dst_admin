<div style="padding:10px 20px">
    <form id="operatingBasicIndex_editWin_form">
        <input type="hidden" name="id" />
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">父级运营公司</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        id="operatingBasicIndex_editWin_form_pid"
                        name="pid"
                        style="width:180px;"
                        />
                </div>
            </li>
			<li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">所属大区</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="area"
                        style="width:180px;"
                        required="true"
                        validType="length[255]"
                    /> 
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">运营公司名称</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="name"
                        style="width:180px;"
                        required="true"
                        validType="length[255]"
                    /> 
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">运营公司地址</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='addr'
                        style="width:490px;"
                        validType="length[255]"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">备注</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='note'
                        data-options="multiline:true"
                        style="height:60px;width:482px;"
                        validType="length[255]"
                    />
                </div>
            </li>
        </ul>
    </form>
</div>
<script>
    var operatingBasicIndex_editWin  = {
        init: function(){
            var curRec = <?= json_encode($recordInfo); ?>;
            var ids = []; //保存当前运营公司及各级子公司的id
            var easyuiForm = $('#operatingBasicIndex_editWin_form');
            easyuiForm.find('input[name=pid]').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>&isShowRoot=1",
                editable: false,
                required: true,
                panelHeight:'auto',
                panelWidth:300,
                lines:false,
                formatter: function(record){ //格式化，将当前运营公司及各级子公司置灰以表示不能选为父公司
                    if(record.id == curRec.id) {
                        ids.push(record.id);
                        function getAllSubIds(record){
                            var children = record.children;
                            if(children.length){
                                for(var i=0; i<children.length; i++){
                                    var rec = children[i];
                                    ids.push(rec.id);
                                    getAllSubIds(rec);
                                }
                            }
                        }
                        getAllSubIds(record);
                    }
                    $flag = false;
                    if(ids.length){
                        for(var i=0;i<ids.length; i++){
                            if(ids[i] == record.id){
                                $flag = true;
                                break;
                            }
                        }
                    }
                    if($flag){
                        return '<span style="color:#DFDFDF;cursor:not-allowed;">'+record.text+'</span>';
                    }else{
                        return record.text;
                    }
                },
                onSelect:function(record){ //监听选择，限制当前运营公司及各级子公司不能选为父公司
                    $flag = false;
                    if(ids.length){
                        for(var i=0;i<ids.length; i++){
                            if(ids[i] == record.id){
                                $flag = true;
                                break;
                            }
                        }
                    }
                    if($flag){
                        $('#operatingBasicIndex_editWin_form_pid').combotree('clear');
                    }
                },
                onLoadSuccess: function(data){ //展开到当前菜单位置
                    var t = $('#operatingBasicIndex_editWin_form_pid').combotree('tree');
                    var parentNode = t.tree('getSelected');
                    var childrenNodes = t.tree('getChildren',parentNode.target);
                    if(childrenNodes.length){
                        for(var i=0; i<childrenNodes.length; i++){
                            if(childrenNodes[i].id == curRec.id){
                                t.tree('expandTo',childrenNodes[i].target);
                            }
                        }
                    }else{
                        t.tree('expandTo',parentNode.target);
                    }
                }
            });
			
			easyuiForm.find('input[name=area]').combobox({
                valueField:'value',
                textField:'text',
                editable: false,
                panelHeight:'auto',
                data: [{"value": '',"text": ''},{"value": 1,"text": '华南大区'},{"value": 2,"text": '华北大区'},{"value": 3,"text": '华东大区'},{"value": 4,"text": '华中大区'},{"value": 5,"text": '西南大区'}],
                onSelect: function(){
                    
                }
            });
            //表单赋值
            $('#operatingBasicIndex_editWin_form').form('load',curRec);
        }
    };
    operatingBasicIndex_editWin.init();
</script>