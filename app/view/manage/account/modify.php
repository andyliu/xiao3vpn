<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>
<h2>客户资料</h2>
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

</td>
    </tr>
<?php endforeach;?>

</table>
<input class="meta-button" value="修改" type="submit" />
<?php echo $form->close();?>

<h2>客户产品</h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-bottom:20px;">
    <tr>
        <th width="*">产品名称</th>
        <th width="70">产品价格</th>
        <th width="100">付款周期</th>
        <th width="120">过期时间</th>
        <th width="100" class="tdc">状态</th>
        <th width="180">帐号/密码</th>
    </tr>
    <?php if($order->count()):?>
    <?php foreach($order as $row): ?>
    <tr>
        <td>#<?=$row->name();?></td>
        <td><?=$row->cost();?></td>
        <td><?=$row->billingcycle;?></td>
        <td><?=$row->expired();?></td>
        <td class="tdc"><?=$row->status();?></td>
        <td><?=$row->username;?>/<?=$row->password;?></td>
    </tr>
    <?php endforeach;?>
    <?php else:?>
    <tr>
        <td colspan="6" >无内容</td>
    </tr>
    <?php endif;?>
</table>


<?php $this->_endblock('contents'); ?>
