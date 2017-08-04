<form id="SystemMenuIndex_editWin_form" method="post" style="padding:5px;">
    <input type="hidden" name="id"  />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">父菜单</div>
            <div class="ulforform-resizeable-input">
                <input name="pid" id="SystemMenuIndex_editWin_form_pid" style="width:100%;" />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">菜单名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="name"
                    style="width:100%;"
                    required="true"
                    validType="length[50]"
                    missingMessage="请填写菜单名称！"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">菜单图标</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="icon_class"
                    style="width:100%;"
                    validType="length[100]"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">排序号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="list_order"
                    style="width:100%;"
                    validType="int"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">菜单MCA</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="mca"
                    style="width:510px;"
                    validType="length[255]"
                    prompt="填写格式：模块/控制器/方法"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">外链URL</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="target_url"
                    style="width:510px;"
                    validType="length[255]"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">默认展开</div>
            <div class="ulforform-resizeable-input">
                <select
                    class="easyui-combobox"
                    name="opend"
                    style="width:100%;"
                    data-options="{panelHeight:'auto',required:true,editable: false}" >
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="note"
                    style="width:510px;height:86px;"
                    data-options="multiline:true"
                    validType="length[255]"
                    />
            </div>
        </li>
    </ul>
</form>
<script>
    var SystemMenuIndex_editWin  = {
        init: function(){
            var curMenu = <?= json_encode($menu); ?>;
            var ids = []; //保存当前菜单及各级子菜单的id
            $('#SystemMenuIndex_editWin_form_pid').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['system/combotree/get-menus']); ?>&isShowRoot=1",
                editable: false,
                required: true,
                panelHeight:'auto',
                //panelWidth:300,
                lines:false,
                formatter: function(record){ //格式化，将当前菜单及各级子菜单置灰以表示不能选为父菜单
                    if(record.id == curMenu.id) {
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
                onSelect:function(record){ //监听选择，限制当前菜单及各级子菜单不能选为父菜单
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
                        $('#SystemMenuIndex_editWin_form_pid').combotree('clear');
                    }
                },
                onLoadSuccess: function(data){ //展开到当前菜单位置
                    var t = $('#SystemMenuIndex_editWin_form_pid').combotree('tree');
                    var parentNode = t.tree('getSelected');
                    var childrenNodes = t.tree('getChildren',parentNode.target);
                    if(childrenNodes.length){
                        for(var i=0; i<childrenNodes.length; i++){
                            if(childrenNodes[i].id == curMenu.id){
                                t.tree('collapseAll').tree('expandTo',childrenNodes[i].target);
                            }
                        }
                    }else{
                        t.tree('collapseAll').tree('expandTo',parentNode.target);
                    }
                }
            });

            //表单赋值
            $('#SystemMenuIndex_editWin_form').form('load',curMenu);
        }
    };
    SystemMenuIndex_editWin.init();
</script>