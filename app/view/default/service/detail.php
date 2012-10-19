<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>
 
<h2><?=$username;?><?php if(Q::ini('isAdmin')):?><form method="post" style="float:right;">
        <select name="status" onchange="this.form.submit();">
            <option value="unpaid"<?php if($order->status == 'unpaid'):?> selected="selected"<?php endif;?>>未付款</option>
            <option value="pending"<?php if($order->status == 'pending'):?> selected="selected"<?php endif;?>>处理中</option>
            <option value="approve"<?php if($order->status == 'approve'):?> selected="selected"<?php endif;?>>已生效</option>
            <option value="canceled"<?php if($order->status == 'canceled'):?> selected="selected"<?php endif;?>>已取消</option>
            <option value="expired"<?php if($order->status == 'expired'):?> selected="selected"<?php endif;?>>已过期</option>
        </select>
        <input type="hidden" value="<?=$order->order_id;?>" name="id" />
</form><?php endif;?></h2>
<p class="global_tip">您已使用：<?php echo _size($traffic_total);?></p>

<h2>流量日志</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <th width="*">计量时间</th>
    <th width="100">上行</th>
    <th width="100">下行</th>
    <th width="100">合计</th>
    <th width="100">在线时长</th>
</tr>
<?php foreach($rs as $log):?>
<tr>
    <td><?=$log->acctstarttime;?></td>
    <td><?=_size($log->acctinputoctets);?></td>
    <td><?=_size($log->acctoutputoctets);?></td>
    <td><?=_size($log->acctinputoctets + $log->acctoutputoctets);?></td>
    <td><?=round(($log->acctsessiontime)/60,0);?>分钟</td>
</tr>
<?php endforeach;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pg)); ?>
<div style="clear:both;"></div>

<?php $this->_endblock('contents'); ?>
