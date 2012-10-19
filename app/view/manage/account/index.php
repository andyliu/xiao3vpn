<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>


<form id="dataList" action="<?=url('manage::account/delete');?>" method="post" >
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <th width="20"><input type="checkbox" id="checkall" /></th>
        <th width="*">账号</th>
    </tr>
    <?php if(isset($rs) && $rs):foreach($rs as $row): ?>
    <tr>
        <td><input type="checkbox" name="ids[]" class="checkall" value="<?=$row->id();?>" /></td>
        <td><a href="<?=url('manage::account/modify',array('id'=>$row->id()));?>" /><?=$row->user_mail;?></a></td>
    </tr>
    <?php endforeach;?>
    <?php else:?>
    <tr>
        <td colspan="4" >无内容</td>
    </tr>
    <?php endif;?>
</table>
</form>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pg)); ?>
<div style="clear:both;"></div>
<?php $this->_endblock('contents'); ?>
