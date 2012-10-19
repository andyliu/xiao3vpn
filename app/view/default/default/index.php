<?php $this->_extends('_layouts/default_layout'); ?>

<?php $this->_block('contents'); ?>

    <p>本站提供的虚拟专用网络服务使用L2TP、PPTP方式，无须安装任何软件就可以方便的在Windows、Linux、Mac、Android、iPhone、iPad等平台上使用。</p>

    <p>本站所使用服务器来自电信和网通直连的ChinaCache合作机房，稳定，速度快。并且，为保证服务质量，严格限制用户数目。</p>

    <p>每个用户每月收费10元，流量为20GB，我们承诺每台服务器只卖15份，关于流量限制是为了防止滥用，如果是用于一般上网管理VPS或玩游戏，20GB的流量是绰绰有余的。</p>
    
    <p>优惠中：季付￥25元，半年付￥50元。</p>

    <?php if(!$_USER_ID):?>
    <p>
        <a class="meta-button" href="<?=url('default::default/signup');?>"><?= ($account_left > 0) ? '马上订购':'立即注册';?></a>
        (库存：<?=$account_left;?>，<?= ($account_left > 5) ? '货源充足请放心购买。': (($account_left < 1) ? '目前无法购买。': '货源紧张请抓紧购买。');?>)
    </p>
    <?php else:?>
    <p><a target="_blank" href="http://sighttp.qq.com/authd?IDKEY=1261470d838b9c2b84d032db4845ac798daaf1207ea77e26"><img border="0"  src="http://wpa.qq.com/imgd?IDKEY=1261470d838b9c2b84d032db4845ac798daaf1207ea77e26&pic=41 &r=0.20345261809416115" alt="请联系我" title="请联系我"></a></p>
    <?php endif;?>

<?php $this->_endblock('contents'); ?>
