<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

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

</td>
    </tr>
<?php endforeach;?>

</table>
<input class="meta-button" value="提交" type="submit" /> | <a href="<?=url('default::default/signin');?>">登陆</a>
<?php echo $form->close();?>

<?php $this->_endblock('contents'); ?>
