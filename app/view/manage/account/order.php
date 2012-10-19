<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>


<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <th width="20"><input type="checkbox" id="checkall" /></th>
        <th width="*">产品名称</th>
        <th width="150">账户</th>
        <th width="100">计划</th>
        <th width="70">周期</th>
        <th width="70">金额</th>
        <th width="100">状态</th>
        <th width="140">下单时间</th>
    </tr>
    <?php if(isset($rs) && $rs):foreach($rs as $row): ?>
    <tr>
        <td><input type="checkbox" name="ids[]" class="checkall" value="<?=$row->id();?>" /></td>
        <td><?=$row->name();?></td>
        <td><?=$row->account();?></td>
        <td><?=$row->groupname();?></td>
        <td><?=$row->billingcycle();?></td>
        <td><?=$row->cost();?></td>
        <td><?=$row->invoice->status(false);?></td>
        <td><?=$row->created();?></td>
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
