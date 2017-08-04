<br />
<ul class="ulforform-resizeable">
    <?php foreach($realtimeData as $val){ ?>
    <li class="ulforform-resizeable-group" style="width:322px;">
        <div class="ulforform-resizeable-title"><?php echo $val['name']; ?></div>
        <div class="ulforform-resizeable-input" style="width:220px;">
            <input
                class="easyui-textbox"
                style="width:160px;"
                disabled="true"
                value="<?php echo $val['value']; ?>"
            />
            <?php echo $val['unit']; ?>
        </div>
    </li>
    <?php } ?>
</ul>