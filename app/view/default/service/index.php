<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>
<script src="<?=$_BASE_DIR;?>js/jquery-1.8.2.min.js"></script>
<h2><?php if(Q::ini('isDemo')):?><span style="color:red;">这只是一个演示帐号，付款后并不开通登陆权限，如果你使用过程产生了交易，我将予以退还。</span><?php else:?>我的产品<?php endif;?></h2>
<table class="datalist" border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-bottom:20px;">
    <tr>
        <th width="*">产品名称</th>
        <th width="70">产品价格</th>
        <th width="100">付款周期</th>
        <th width="120">过期时间</th>
        <th width="90" class="tdc">状态</th>
        <th width="180">帐号/密码</th>
    </tr>
    <?php if($rs->count()):?>
    <?php foreach($rs as $row): ?>
    <tr>
        <td>#<?=$row->name();?></td>
        <td><?=$row->cost();?></td>
        <td><?=$row->billingcycle;?></td>
        <td><?=$row->expired();?></td>
        <td class="tdc"><?=$row->status('paylink');?></td>
        <td><?=$row->username;?>/<?=$row->password;?></td>
    </tr>
    <?php endforeach;?>
    <?php else:?>
    <tr>
        <td colspan="6" >无内容</td>
    </tr>
    <?php endif;?>
</table>

<?php if(Q::ini('OrderAvailable')):?>
<h2>订购产品</h2>
<?php echo $form->start();?>
<table class="dataform" border="0" cellspacing="0" cellpadding="0" width="100%">
    <?php
    foreach ($form->elements() as $e):
        $id = $e->id;
    ?>    
        <tr>
            <td width="40"><?php if ($e->_label): ?><label for="<?php echo $id; ?>"><?php echo h($e->_label); ?></label><?php endif; ?></td>
            <td><?php echo Q::control($e->_ui, $id, $e->attrs()); ?>
            <?php if (!$e->isValid()): ?><span class="error"><?php echo nl2br(h(implode("，", $e->errorMsg()))); ?></span><?php endif; ?>

    </td>
        </tr>
    <?php endforeach;?>
    <?php if(Q::ini('isDemo')):?>
    <tr>
        <td width="40"><label for="subject">购买：</label></td>
        <td><select name="billingcycle" class="slt">
            <option value="monthly" selected="selected">月付(0.01元--仅供演示)</option>
            <option value="quarterly">季付(0.01元--仅供演示)</option>
            <option value="semi-annually">半年付(0.01元--仅供演示)</option>
        </select>
        </td>
    </tr>
    <?php else:?>
    <?php $rs = Plans::find()->order('plan_id DESC')->getAll();?>
    <tr>
        <td width="40"><label for="subject">计划：</label></td>
        <td><select id="plan_id" name="plan_id" class="slt">
            <?php foreach($rs as $plan):?>
            <option value="<?=$plan->id();?>"><?=$plan->name();?></option>
            <?php endforeach;?>
        </select>
        </td>
    </tr>
    <tr>
        <td width="40"><label for="subject">周期：</label></td>
        <td><select id="billingcycle" name="billingcycle" class="slt">
            <option value="0" selected="selected">loading...</option>
        </select>
        </td>
    </tr>
    <?php endif;?>
</table>
<input class="meta-button" value="确认订单" type="submit" />（如需在线自动开通帐号，请在付款时选择“即时到帐”服务。）

<script type="text/javascript">
<!--
    $(function()
    {
        changePlans();
        $('#plan_id').change(function()
        {
            changePlans();
        });
    })

    function changePlans()
    {
        $.post('<?=url('default::service/changePlans')?>',{id:$('#plan_id').val()},function(rest)
        {
            $('#billingcycle').html(rest);
        });
    }


//-->
</script>
<?php echo $form->close();?>

<?php endif;?>

<?php $this->_endblock('contents'); ?>
