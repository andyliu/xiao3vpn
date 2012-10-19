<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

<h2>Radius Check表</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <?php if($rsuc->count()):?>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" /></th>
        <th width="*">账户</th>
    </tr>
    <?php foreach($rsuc as $row): ?>
    <tr>
        <td><input type="checkbox" name="ids[]" class="checkall" value="<?=$row->id();?>" /></td>
        <td><?=$row->username();?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
        <th width="150">账户</th>
        <th width="100">属性</th>
        <th width="100">运算符</th>
        <th width="150">值</th>
        <th width="100">操作</th>
    </tr>
    <form action="<?=url('manage::radius/modify');?>" method="post">
    <tr>
        <td><input class="ipt" name="username" value="" /></td>
        <td><input class="ipt" name="attribute" value="" /></td>
        <td><input class="ipt" name="op" value=":=" /></td>
        <td><input class="ipt" name="value" value="" /></td>
        <td><input type="hidden" value="check" name="type" /><input type="submit" value="添加" class="btn" /></td>
    </tr>
    </form>
    <?php endif;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pguc,'pageuri'=>'pageuc')); ?>
<div style="clear:both;"></div>

<h2>Radius Reply表</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <?php if($rsur->count()):?>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" /></th>
        <th width="*">账户</th>
    </tr>
    <?php foreach($rsur as $row): ?>
    <tr>
        <td><input type="checkbox" name="ids[]" class="checkall" value="<?=$row->id();?>" /></td>
        <td><?=$row->username();?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
        <th width="150">账户</th>
        <th width="100">属性</th>
        <th width="100">运算符</th>
        <th width="150">值</th>
        <th width="100">操作</th>
    </tr>
    <form action="<?=url('manage::radius/modify');?>" method="post">
    <tr>
        <td><input class="ipt" name="username" value="" /></td>
        <td><input class="ipt" name="attribute" value="" /></td>
        <td><input class="ipt" name="op" value=":=" /></td>
        <td><input class="ipt" name="value" value="" /></td>
        <td><input type="hidden" value="replay" name="type" /><input type="submit" value="添加" class="btn" /></td>
    </tr>
    </form>
    <?php endif;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pgur,'pageuri'=>'pageur')); ?>
<div style="clear:both;"></div>

<h2>Radius User Group表</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <?php if($rsug->count()):?>
    <tr>
        <th width="*">账户</th>
        <th width="*">组</th>
    </tr>
    <?php foreach($rsug as $row): ?>
    <tr>
        <td><?=$row->username();?></td>
        <td><?=$row->groupname();?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
    <tr>
        <th width="*">账户</th>
        <th width="*">组</th>
        <th width="*">优先级</th>
        <th width="*">操作</th>
    </tr>
    <form action="<?=url('manage::radius/usergroup');?>" method="post">
    <tr>
        <td><input class="ipt" name="username" value="" /></td>
        <td><input class="ipt" name="groupname" value="" /></td>
        <td><input class="ipt" name="priority" value="1" /></td>
        <td><input type="submit" value="添加" class="btn" /></td>
    </tr>
    </form>
    <?php endif;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pgug,'pageuri'=>'pageug')); ?>
<div style="clear:both;"></div>


<h2 id="group">Radius Group Check表</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <?php if($rsgc->count()):?>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" /></th>
        <th width="*">组名</th>
    </tr>
    <?php foreach($rsgc as $row): ?>
    <tr>
        <td><input type="checkbox" name="ids[]" class="checkall" value="<?=$row->id();?>" /></td>
        <td><?=$row->groupname();?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
        <th width="150">组名</th>
        <th width="100">属性</th>
        <th width="100">运算符</th>
        <th width="150">值</th>
        <th width="100">操作</th>
    </tr>
    <form action="<?=url('manage::radius/modgroup');?>" method="post">
    <tr>
        <td><input class="ipt" name="groupname" value="" /></td>
        <td><input class="ipt" name="attribute" value="" /></td>
        <td><input class="ipt" name="op" value=":=" /></td>
        <td><input class="ipt" name="value" value="" /></td>
        <td><input type="hidden" value="check" name="type" /><input type="submit" value="添加" class="btn" /></td>
    </tr>
    </form>
    <?php endif;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pggc,'pageuri'=>'pagegc')); ?>
<div style="clear:both;"></div>

<h2>Radius Group Reply表</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <?php if($rsgr->count()):?>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" /></th>
        <th width="*">组名</th>
    </tr>
    <?php foreach($rsgr as $row): ?>
    <tr>
        <td><input type="checkbox" name="ids[]" class="checkall" value="<?=$row->id();?>" /></td>
        <td><?=$row->groupname();?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
        <th width="150">组名</th>
        <th width="100">属性</th>
        <th width="100">运算符</th>
        <th width="150">值</th>
        <th width="100">操作</th>
    </tr>
    <form action="<?=url('manage::radius/modgroup');?>" method="post">
    <tr>
        <td><input class="ipt" name="groupname" value="" /></td>
        <td><input class="ipt" name="attribute" value="" /></td>
        <td><input class="ipt" name="op" value=":=" /></td>
        <td><input class="ipt" name="value" value="" /></td>
        <td><input type="hidden" value="reply" name="type" /><input type="submit" value="添加" class="btn" /></td>
    </tr>
    </form>
    <?php endif;?>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pggr,'pageuri'=>'pagegr')); ?>
<div style="clear:both;"></div>

<?php $this->_endblock('contents'); ?>
