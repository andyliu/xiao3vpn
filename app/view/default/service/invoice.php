<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <th width="*">产品名称</th>
    <th width="200">账单编号</th>
    <th width="150">生成日期</th>
    <th width="150">付款日期</th>
    <th width="100">金额</th>
    <th width="100">状态</th>
</tr>
<?php foreach($rs as $row):?>
<tr>
    <td>#<?=$row->order->name();?></td>
    <td><?=$row->order_number;?></td>
    <td><?=$row->created();?></td>
    <td><?=$row->trade_time();?></td>
    <td><?=$row->fee();?></td>
    <td><?=$row->status();?></td>
</tr>
<?php endforeach;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pg)); ?>
<div style="clear:both;"></div>
<?php $this->_endblock('contents'); ?>
