<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php $this->_block('title'); ?><?=$_META_TITLE;?><?php $this->_endblock('title'); ?></title>
    <meta name="keywords" content="<?php $this->_block('keywords'); ?><?=$_META_KEYWORDS;?><?php $this->_endblock('keywords'); ?>" />
    <meta name="description" content="<?php $this->_block('description'); ?><?=$_META_DESCRIPTION;?><?php $this->_endblock('description'); ?>" />
    <link rel="stylesheet" href="<?=$_BASE_DIR;?>css/style.css" />
    <script src="<?=$_BASE_DIR;?>js/jquery-1.8.2.min.js"></script>
</head>
<body>
<div class="wapper">
    <div class="nav">
    <ul>
        <li<?php ic($_UDI,'manage::default/index',' class="current"');?>><a href="<?=url('manage::default/index');?>">管理中心</a></li>
        <li<?php ic($_UDI,'manage::account/index',' class="current"');?>><a href="<?=url('manage::account');?>">账户中心</a></li>
        <li<?php ic($_UDI,'manage::plans/index',' class="current"');?>><a href="<?=url('manage::plans');?>">销售计划</a></li>
        <li<?php ic($_UDI,'manage::account/invoice',' class="current"');?>><a href="<?=url('manage::account/invoice');?>">账单中心</a></li>
        <li<?php ic($_UDI,'manage::account/traffic',' class="current"');?>><a href="<?=url('manage::account/traffic');?>">流量日志</a></li>
        <li<?php ic($_UDI,'manage::account/online',' class="current"');?>><a href="<?=url('manage::account/online');?>">在线用户</a></li>
        <li<?php ic($_UDI,'manage::radius/index',' class="current"');?>><a href="<?=url('manage::radius/index');?>">Radius</a></li>

        <li style="float: right;"><a href="<?=url('default::default/index');?>">前台首页</a></li>

    </ul>
    <div style="clear:both;"></div>
    </div>
    <div class="main">
        <?php echo $_MSG ? '<div class="global_msg">'.$_MSG.'</div>':'';?>
        <?php $this->_block('contents'); ?><?php $this->_endblock(); ?>
    </div>
     <div class="copyright">Power by <a href="http://www.xiao3.org" target="_xiao3">小三加速</a></div>
</div>
<script type="text/javascript">
<!--
    $(function()
    {
        $('#checkall').change(function()
        {
            if($(this).attr('checked'))
                $('.checkall').attr('checked',true);
            else
                $('.checkall').attr('checked',false);
        });
    })
//-->
</script>
</body>
</html>
