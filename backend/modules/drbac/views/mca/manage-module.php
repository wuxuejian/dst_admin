<div class="easyui-layout" data-options="fit: true">  
    <div 
        data-options="region:'west',title:'控制器管理',split:true,border: false"
        style="width:50%;"
        href="<?php echo yii::$app->urlManager->createUrl(['drbac/mca/manage-controller','id'=>$id]) ?>"
    ></div>  
    <div data-options="region:'center',title:'方法管理',border: false">
        <div 
            id="easyui-panel-manage-module-action"
            class="easyui-panel"
            title=""
            fit=true
            border=false
            data-options="collapsible:false,minimizable:false,maximizable:false"
        >  
        </div> 
    </div>  
</div>