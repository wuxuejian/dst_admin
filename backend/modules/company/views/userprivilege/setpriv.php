<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/9/17
 * Time: 10:43
 */
$user_id= Yii::$app->request->get('user_id');
?>
<style type="text/css">
    #ths{
        background: #E3F5F9;
        height: 30px;
        line-height: 30px;
        font-size: 14px;
        font-weight: 600;
    }
    .prtr td{
        height: 22px;
        line-height: 22px;
        border: solid #E4E4E4 1px;
    }
</style>
<div id="win_privilege_setpri">
    <div id="tabs_privilege" class="easyui-tabs" style="height:auto">
        <?

        foreach($datas['menulist'] as $k => $v){// id  text
            $mid = $datas['menu'][$k]['mark'];
            $mtext = $datas['menu'][$k]['text'];
            $mbuttons = $datas['menu'][$k]['buttons'];
            $check = $datas['menu'][$k]['checked'];
            $fId="f".$k;
            ?>
            <div title="<?=$v['text']?>" module="<?=$k?>"  id="<?=$k?>"  style="padding:20px;">
                <from action="<?= \yii\helpers\Url::to(['/company/user-privilege/save-pri']) ?>" method="post" name="<?=$fId?>" id="<?=$fId?>">
                    <input type='hidden' name="user_id" id="user_id" value="<?=$user_id?>">
                    <input type='hidden' name="module_id<?=$fId?>" id="module_id<?=$fId?>" value="<?=$v['id']?>">
                    <table style="width:100%;height:auto;border-collapse:collapse;" class="prtr">
                        <tr id="ths"><td width="20px"></td><td width="250px">菜单</td><td>操作功能</td></tr>
                        <tr>

                            <td ><input type="checkbox" name="check"  id="<?=$mid?>"  value="<?=$mid?>" <?=$check?> onclick="Userpri.selectAll('<?=$k?>',this)"> </td>
                            <td ><?=$mtext?></td>
                            <td ><?=$mbuttons?></td>
                        </tr>
                        <?
                        if(is_array($datas['menu'][$k]['child']) && !empty($datas['menu'][$k]['child'])){
                            foreach($datas['menu'][$k]['child'] as $t => $m ){
                                ?>
                                <tr>

                                    <td ><input name="check1" type="checkbox" id="<?=$m['mark']?>"  value="<?=$m['mark']?>" <?=$m['checked']?>  onclick="Userpri.selected(this)"  > </td>
                                    <td >&nbsp;&nbsp;<?=$m['text']?></td>
                                    <td ><?=$m['buttons']?></td>
                                </tr>
                                <?
                                if(is_array($m['child']) && !empty($m['child'])){
                                    foreach($m['child'] as $p => $n){
                                        ?>
                                        <tr>

                                            <td ></td>
                                            <td >&nbsp;&nbsp;<input name="check1" title="<?=$n['mark']?>"  type="checkbox" id="<?=$m['mark']?>" value="<?=$n['mark']?>" <?=$n['checked']?>  onclick="Userpri.selected(this,1)"  >
                                                <?=$n['text']?></td>
                                            <td ><?=$n['buttons']?></td>
                                        </tr>
                                        <?
                                        if(is_array($n['child']) && !empty($n['child'])){
                                            foreach($n['child'] as $x => $z){
                                                ?>
                                                <tr>

                                                    <td ></td>
                                                    <td >&nbsp;&nbsp;&nbsp;&nbsp;<input name="check1" type="checkbox" title="<?=$n['mark']?>" id="<?=$m['mark']?>" value="<?=$z['mark']?>" <?=$z['checked']?>  onclick="Userpri.selected(this,2)"  >
                                                        <?=$z['text']?></td>
                                                    <td ><?=$z['buttons']?></td>
                                                </tr>
                                            <?
                                            }
                                        }

                                    }
                                }
                            }
                        }
                        ?>
                        <tr><td colspan="4" align="center">
                                <!-- <input class="easyui-linkbutton" value ="保存"  name="save" onclick="Userpri.savePri('<?/*=$fId*/?>')" >-->
                                <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-save'"  onclick="Userpri.savePri('<?=$fId?>')">保存</a>

                            </td></tr></table>
                </from>
            </div>
        <?}?>

    </div>

</div>        
