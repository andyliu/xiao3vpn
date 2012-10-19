<?php

class Controller_Manage_Default extends Controller_Manage
{

    function actionIndex()
    {
        //新进订单
        $order = Order::find()->order('order_id DESC')->get(5);
        
        //新进帐号
        #$users = Account::find()->order('user_id DESC')->get(5);

        $this->_view['order'] = $order;
        #$this->_view['users'] = $users;

        $sql     ="SELECT SUM( acctinputoctets + acctoutputoctets ) as total FROM radacct WHERE 1";
        $traffic_total = QDB::getConn()->getOne($sql);
        $this->_view['traffic_total'] = $traffic_total;
        
        /*
        $ttt = Invoice::find('trade_status = ?','TRADE_FINISHED')->getAll();
        foreach($ttt as $t)
        {
            $t->due_time = ($t->per_day * 24 * 3600) + $t->trade_time;
            $t->save();
        }
        */


        $nowt = time();
        $dayt = 24 * 3600;
        $tips = ($nowt - (25 * $dayt));

        $sql = "SELECT i.`order_id`,i.`total_fee`,i.`per_day`,i.`due_time`,i.`order_number` FROM `vpn_invoice` i, `vpn_order` o WHERE i.`order_id` = o.`order_id` AND o.`status` IN ('expired','approve') AND i.`is_expired` = '0' AND i.`trade_status` = 'TRADE_FINISHED' AND i.`due_time` < " . $tips;

        $niv = QDB::getConn()->getAll($sql);

        // 这里的 due_time 其实不用设置，因为这系统账单不会过期。。。当用户付款后，自动更新下期账单时间。
        foreach($niv as $new)
        {
            $old = $new['order_number'];
            $due_time             = $nowt > $new['due_time'] ? $nowt:$new['due_time'];
            $new['due_time']      = $due_time + ($new['per_day'] * $dayt);
            $new['order_number']  = 'x'. date('YmdHis') . rand(111,999);
            
            $test = QDB::getConn()->getOne("SELECT `order_number` FROM `vpn_invoice` WHERE `order_id` = {$new['order_id']} AND `trade_status` LIKE 'WAIT_BUYER_PAY'");
            // 如果不存在未支付的账单，则生成之。
            if(!$test)
            {
                $new_sql = "INSERT INTO `vpn_invoice` (`order_id`, `order_number`, `buyer_email`, `total_fee`, `trade_time`, `trade_status`, `trade_no`, `trade_ip`, `per_day`, `due_time`, `created`, `updated`) VALUES ({$new['order_id']}, '{$new['order_number']}', '0', {$new['total_fee']}, 0, 'WAIT_BUYER_PAY', '0', '0', {$new['per_day']}, {$new['due_time']}, {$nowt}, 0);";
                #QDB::getConn()->execute($new_sql);
                
                //设置上一账单失效。
                $ext_sql = "UPDATE `vpn_invoice` SET `is_expired` = '1' WHERE `order_number` = '{$old}'";
                QDB::getConn()->execute($ext_sql);
                #dump($ext_sql);
            }
        }
        #exit;
        // 过期用户
        $exts = ($nowt - (30 * $dayt));
        $sql = "SELECT o.`username`,o.`order_id` FROM `vpn_invoice` i, `vpn_order` o WHERE i.`order_id` = o.`order_id` AND i.`is_expired` = '0' AND i.`trade_status` = 'TRADE_FINISHED' AND o.`status` != 'expired' AND i.`due_time` < " . $exts;
        $ext = QDB::getConn()->getAll($sql);
        foreach($ext as $tmp)
        {
            $old_sql = "UPDATE `vpn_order` SET `status` = 'expired' WHERE `order_id` = {$tmp['order_id']}";
            $nop_sql = "UPDATE `radusergroup` SET `groupname` = 'NOP' WHERE `username` = '{$tmp['username']}'";

            QDB::getConn()->execute($old_sql);
            QDB::getConn()->execute($nop_sql);
        }

    }
}

