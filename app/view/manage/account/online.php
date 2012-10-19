<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

<h2>目前有 <?=$rs->count();?> 个终端在线</h2>
<form action="" method="post">
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <th width="20"><input type="checkbox" id="checkall" /></th>
    <th width="*">计量时间</th>
    <th width="150">账户</th>
    <th width="100">上行</th>
    <th width="100">下行</th>
    <th width="100">合计</th>
    <th width="100">在线时长</th>
</tr>
<?php foreach($rs as $log):?>
<tr>
    <td><input type="checkbox" name="ipaddress[]" class="checkall" value="<?=$log->framedipaddress;?>"/></td>
    <td><?=$log->acctstarttime;?></td>
    <td><?=$log->account();?></td>
    <td><?=_size($log->acctinputoctets);?></td>
    <td><?=_size($log->acctoutputoctets);?></td>
    <td><?=_size($log->acctinputoctets + $log->acctoutputoctets);?></td>
    <td><?=round(($log->acctsessiontime)/60,0);?>分钟</td>
</tr>
<?php endforeach;?>
</table>
</form>
<?php $this->_endblock('contents'); ?>
