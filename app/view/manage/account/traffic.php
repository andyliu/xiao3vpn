<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <th width="*">计量时间</th>
        <th width="150">账户</th>
        <th width="100">上行</th>
        <th width="100">下行</th>
        <th width="100">合计</th>
        <th width="100">在线时长</th>
    </tr>
    <?php foreach($rs as $log):?>
    <tr>
        <td><?=$log->acctstarttime;?></td>
        <td><?=$log->account();?></td>
        <td><?=_size($log->acctinputoctets);?></td>
        <td><?=_size($log->acctoutputoctets);?></td>
        <td><?=_size($log->acctinputoctets + $log->acctoutputoctets);?></td>
        <td><?=round(($log->acctsessiontime)/60,0);?>分钟</td>
    </tr>
    <?php endforeach;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pg,'pageuri'=>'page')); ?>
<div style="clear:both;"></div>

<?php $this->_endblock('contents'); ?>
