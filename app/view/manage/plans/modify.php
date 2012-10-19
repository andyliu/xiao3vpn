<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

<h2>修改计划</h2>
<?php echo $form->start();?>
<table class="dataform" border="0" cellspacing="0" cellpadding="0" width="100%">
<?php
foreach ($form->elements() as $e):
    $id = $e->id;
?>    
    <tr>
        <td width="80"><?php if ($e->_label): ?><label for="<?php echo $id; ?>"><?php echo h($e->_label); ?></label><?php endif; ?></td>
        <td><?php echo Q::control($e->_ui, $id, $e->attrs()); ?>
        <?php if (!$e->isValid()): ?><span class="error"><?php echo nl2br(h(implode("，", $e->errorMsg()))); ?></span><?php endif; ?>
        <?php if($id == 'groupname'):?><span><a class="link" href="<?=url('manage::radius/index');?>#group">设置</a></span><?php endif; ?>
        </td>
    </tr>
<?php endforeach;?>
    <tr>
        <td width="80"><label for="config">计划配置：</label></td>
        <td>
        <?php $sku = json_decode($rs->sku,true);foreach($sku as $key=>$val) { ?>
        <input name="config[key][]" type="hidden" class="ipt" value="<?=$key;?>" />
        <input name="config[dsp][]" class="ipt" style="min-width:186px;width:186px;" value="<?=$val['print'];?>" />
        <input name="config[val][]" class="ipt" style="min-width:50px;width:50px;" value="<?=$val['price'];?>" /> 元<br />
        <?php }?>
        </td>
    </tr>
</table>

<input class="meta-button" value="修改" type="submit" />
<?php echo $form->close();?>
<?php $this->_endblock('contents'); ?>
