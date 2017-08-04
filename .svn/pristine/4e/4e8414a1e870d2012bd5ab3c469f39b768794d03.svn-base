<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-extract-site-add" class="easyui-form" method="post">
        <table cellpadding="5">
            <tr>
                <td>提车地点：</td>
                <td>
                    <input class="easyui-textbox"  name="name" required="true"   style="width:210px" missingMessage="请填写提车地点" />

                </td>
            </tr>
            <tr>
                <td>所属运营公司：</td>
                <td>
                    <select class="easyui-combobox"  name="operating_company_id" required="true"   missingMessage="请选择运营公司">
	                   <option value=""></option>
	                   <?php foreach ($operating_company as $val):?>
	                   <option value="<?php echo $val['id']?>"><?php echo $val['name'];?></option>
	                   <?php endforeach;?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>