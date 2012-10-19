<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

<h2>User Group属性值</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <th width="*">账户</th>
        <th width="*">组</th>
        <th width="*">优先级</th>
        <th width="*">操作</th>
    </tr>
    <?php if($rs->count()):foreach($rs as $row): ?>
    <form action="" method="post">
    <tr>
        <td><input class="ipt" name="username" value="<?=$row->username;?>" /></td>
        <td><input class="ipt" name="groupname" value="<?=$row->groupname;?>" /></td>
        <td><input class="ipt" name="priority" value="<?=$row->priority;?>" /></td>
        <td><input type="submit" value="提交" class="btn" /> | <a href="<?=url('manage::radius/usergroup',array('deleted'=>'yes','username'=>$row->username,'groupname'=>$row->groupname));?>">删除</a></td>
    </tr>
    </form>
    <?php endforeach;?>
    <?php else:?>
    <tr>
        <td colspan="6" >无内容</td>
    </tr>
    <?php endif;?>
    <form action="" method="post">
    <tr>
        <td><input class="ipt" name="username" value="" /></td>
        <td><input class="ipt" name="groupname" value="" /></td>
        <td><input class="ipt" name="priority" value="1" /></td>
        <td><input type="submit" value="添加" class="btn" /></td>
    </tr>
    </form>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pg,'pageuri'=>'page')); ?>
<div style="clear:both;"></div>

<?php $this->_endblock('contents'); ?>
