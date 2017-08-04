<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/9/24
 * Time: 14:05
 */

?>
<script type="text/javascript" src="js/pinyin.js"> </script>
<form method="post" id="form_fronted_company_user" name="form_fronted_company_user" action="">
    <input type="hidden" name="itemid" value="<?=$model->itemid?>"/>
    <table width="500" align="center" style="font-size:12px">
        <tr>
            <td>账户名：</td>
            <td><input name="worker_name" class="easyui-textbox" value="<?=$model->worker_name?>" data-options="required:true"/></td>
            <td align="right">所属部门：</td>
            <td>
                <input id="form_fronted_company_user_part_id" name="form_fronted_company_user_part_id" value="">
            </td>
         </tr>
        <tr>
            <td align="right">姓名：</td>
            <td><input name="user_name" class="easyui-textbox" id="user_name" value="<?=$model->user_name?>"
                    data-options="events:{
                blur:function(){
                var str=translate_pinyin(this.value);
                $('#letter').textbox('setValue',str)
                    }
                }"
                    /></td>
            <td align="right">拼音：</td>
            <td><input name="letter" id="letter" class="easyui-textbox" value="<?=$model->letter?>"/></td>
        </tr>
        <tr>
            <td  align="right">负责人：</td>
            <td>
                <select id="principal" class="easyui-combobox" name="principal" style="width:50px;"panelHeight="50">
                    <option value="0" <?if($model->principal==0)echo 'selected';?>>否</option>
                    <option value="1" <?if($model->principal==1)echo 'selected';?>>是</option>
                </select>
            </td>
            <td align="right">性别：</td>
            <td align="left"><input  type="radio" name="sex" value="1">男<input  type="radio" name="sex" value="0">女 </td>

        </tr>

        <? if(!$model->itemid){?>
            <tr>
                <td align="right">密码：</td>
                <td><input name="password" id="password" class="easyui-textbox" type="password" data-options="required:true" validType="minLength['6']"/>
                </td>
                <td align="right">确认密码：</td>
                <td><input name="password2" class="easyui-textbox" type="password"   required="required" validType="equals['#password']" />
                </td></tr>
        <?}?>

        <tr>
            <td align="right">手机号：</td>
            <td><input name="mobile" class="easyui-textbox" value="<?=$model->mobile?>" validType="mobile"/></td>
            <td align="right">邮箱：</td>
            <td><input name="email" class="easyui-textbox"  data-options="validType:'email'" value="<?=$model->email?>"/></td>
        </tr>
        <tr>
            <td align="right">电话：</td>
            <td><input name="telephone" class="easyui-textbox" value="<?=$model->telephone?>" validType="phone"/></td>
            <td align="right">qq：</td>
            <td><input name="qq" class="easyui-textbox" value="<?=$model->qq?>" validType="qq"/></td>
        </tr>
    </table>

</form>
<script>
    $(function(){
        var sex = '<?=$model->sex?>';
        if (sex=="1"){
            $("input:radio[name=sex][value=1]").attr("checked", "true");
        }else{
            $("input:radio[name=sex][value=0]").attr("checked", "true");
        }
    })
    User.init();
    var depart = '<?=$model->part_id?>';
    if(depart){

        $('#form_fronted_company_user_part_id').combobox('select', depart);


    }


</script>