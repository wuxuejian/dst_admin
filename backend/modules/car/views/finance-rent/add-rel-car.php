<form id="easyui-form-finance-rent-add-rel-car" class="easyui-form" method="post">
     <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <table cellpadding="5">
        	<tr>
                <!-- <td>*请输入车牌号，每个车牌号一行</td>  -->
                <td>
                   <!-- <textarea rows="23.5" cols="49.5"  placeholder="">
                        
                    </textarea> -->
                    <div id="note" class="note">
                        <font color="#777">请输入车牌号，每个车牌号一行</font>
                    </div>
                    <textarea  name="plate_number" rows="22.5" cols="49.6" class="textarea" onfocus="document.getElementById('note').style.display='none'" onblur="if(value=='')document.getElementById('note').style.display='block'"></textarea>
                    
                </td> 
            </tr> 
        </table> 
    </form>
<script>
</script>