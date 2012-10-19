<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

<h2>新增计划</h2>
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
        <?php foreach(Q::ini('appini/cycle_template') as $key=>$val) { ?>
        <input name="config[key][]" type="hidden" class="ipt" value="<?=$key;?>" />
        <input name="config[dsp][]" class="ipt" style="min-width:186px;width:186px;" value="<?=$val['print'];?>" />
        <input name="config[val][]" class="ipt" style="min-width:50px;width:50px;" value="<?=$val['price'];?>" /> 元<br />
        <?php }?>
        </td>
    </tr>
</table>

<input class="meta-button" value="添加" type="submit" />
<?php echo $form->close();?>

<h2>计划列表</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <th width="20"><input type="checkbox" id="checkall" /></th>
        <th width="200">产品名称</th>
        <th width="150">Radius组</th>
        <th width="*">计划内容</th>
    </tr>
    <?php if($rs->count()):foreach($rs as $row): ?>
    <tr>
        <td><input type="checkbox" name="ids[]" class="checkall" value="<?=$row->id();?>" /></td>
        <td><?=$row->name(true);?></td>
        <td><?=$row->groupname();?></td>
        <td><?=$row->desc();?></td>
    </tr>
    <?php endforeach;?>
    <?php else:?>
    <tr>
        <td colspan="4" >无内容</td>
    </tr>
    <?php endif;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pg)); ?>
<div style="clear:both;"></div>
<?php $this->_endblock('contents'); ?>
