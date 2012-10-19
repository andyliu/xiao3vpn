<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

<h2><?=$tg;?> 属性值</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <th width="150">组名</th>
        <th width="100">属性</th>
        <th width="100">运算符</th>
        <th width="150">值</th>
        <th width="100">操作</th>
    </tr>
    <?php if($rs->count()):foreach($rs as $row): ?>
    <form action="" method="post">
    <tr>
        <td><input class="ipt" name="username" value="<?=$row->username;?>" /></td>
        <td><input class="ipt" name="attribute" value="<?=$row->attribute;?>" /></td>
        <td><input class="ipt" name="op" value="<?=$row->op;?>" /></td>
        <td><input class="ipt" name="value" value="<?=$row->value;?>" /></td>
        <td><input type="hidden" value="<?=$row->id();?>" name="id" /><input type="hidden" value="<?=$type;?>" name="type" /><input type="submit" value="提交" class="btn" /> | <a href="<?=url('manage::radius/modify',array('type'=>$type,'deleteid'=>$row->id()));?>">删除</a></td>
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
        <td><input class="ipt" name="attribute" value="" /></td>
        <td><input class="ipt" name="op" value=":=" /></td>
        <td><input class="ipt" name="value" value="" /></td>
        <td><input type="hidden" value="<?=$type;?>" name="type" /><input type="submit" value="添加" class="btn" /></td>
    </tr>
    </form>
</table>
<?php $this->_control('pagination', 'my-pagination', array('pagination' => $pg,'pageuri'=>'page')); ?>
<div style="clear:both;"></div>

<?php $this->_endblock('contents'); ?>
