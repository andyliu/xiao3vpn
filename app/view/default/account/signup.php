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
        
        <?php if ($id == 'services' && $e->isValid()): ?><span>同意本站<a class="link" href="<?=url('default::cms/page',array('slug'=>'tos'));?>">《服务条款》</a></span><?php endif; ?>

</td>
    </tr>
<?php endforeach;?>

</table>
<input class="meta-button" value="注册帐号" type="submit" />
<?php echo $form->close();?>

<?php $this->_endblock('contents'); ?>
