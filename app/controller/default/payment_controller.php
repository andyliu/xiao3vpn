<?php

class Controller_Default_Payment extends Controller_Abstract
{
    function actionAlipay()
    {
        $order_number = $this->_context->order_number;
        $rs = Invoice::find('order_number =?',$order_number);
        $rs = $rs->getOne();
        if($rs->trade_status == 'WAIT_BUYER_PAY')
        {
            $total_fee    = $rs->total_fee;
            $subject      = $rs->order->name;
            $out_trade_no = $rs->order_number;
            $body         = '订购' . Q::ini('appini/meta/title') . '提供的稳定加速服务';

            if(Q::ini('isAdmin') || Q::ini('isDemo'))
            {
                $total_fee = 0.01;
            }

            $logistics_fee		= "0.00";				//物流费用，即运费。
            $logistics_type		= "EXPRESS";			//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
            $logistics_payment	= "SELLER_PAY";			//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

            $quantity			= "1";					//商品数量，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品。

 
            $receive_name		= $this->_user_name;
            $receive_address	= "http://www.xiao3.org/service";
            $receive_zip		= "";
            $receive_phone		= "";
            $receive_mobile		= $this->_user->user_mail;

            $show_url			= 'http://www.xiao3.org/service';


            $root_dir = Q::ini('app_config/ROOT_DIR');

            require_once($root_dir .Q::ini('appini/alipay_dir') ."lib/alipay_service.class.php");

            $parameter = array(
                    "service"			=> "trade_create_by_buyer",
                    "payment_type"		=> "1",
                    
                    "partner"		=> trim(Q::ini('appini/payment/alipay/partner')),
                    "_input_charset"=> trim(strtolower(Q::ini('appini/payment/alipay/input_charset'))),
                    "seller_email"	=> trim(Q::ini('appini/payment/alipay/seller_email')),
                    "return_url"	=> trim(Q::ini('appini/payment/alipay/return_url')),
                    "notify_url"	=> trim(Q::ini('appini/payment/alipay/notify_url')),

                    "out_trade_no"	=> $out_trade_no,
                    "subject"		=> $subject,
                    "body"			=> $body,
                    "price" 		=> $total_fee,
                    "quantity"		=> $quantity,
                    
                    "logistics_fee"		=> $logistics_fee,
                    "logistics_type"	=> $logistics_type,
                    "logistics_payment"	=> $logistics_payment,
                    
                    "receive_name"		=> $receive_name,
                    "receive_address"	=> $receive_address,
                    "receive_zip"		=> $receive_zip,
                    "receive_phone"		=> $receive_phone,
                    "receive_mobile"	=> $receive_mobile,
                    
                    "show_url"		=> $show_url
            );
            #dump($parameter);exit;
            $alipayService = new AlipayService(Q::ini('appini/payment/alipay'));
            $html_text = $alipayService->trade_create_by_buyer($parameter);
            return $html_text;
        }

        return '已过期';
    }
    
    function actionVerify()
    {
        unset($_GET['action']);
        unset($_GET['module']);
        unset($_GET['controller']);

        $root_dir = Q::ini('app_config/ROOT_DIR');

        require_once($root_dir . Q::ini('appini/alipay_dir') ."lib/alipay_notify.class.php");

        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify(Q::ini('appini/payment/alipay'));
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) 
        {
            $out_trade_no	= $_GET['out_trade_no'];	//获取订单号
            $trade_no		= $_GET['trade_no'];		//获取支付宝交易号
            $total_fee		= $_GET['price'];			//获取总价格

            $rs = Invoice::find('order_number = ?',$_GET['out_trade_no'])->getOne();
            if($rs->id())
            {
                #$rs->total_fee   = $total_fee;
                $rs->trade_time  = time();
                $rs->trade_no    = $trade_no;
                $rs->trade_status = $_GET['trade_status'];
                $rs->buyer_email  = $_GET['buyer_email'];
                $rs->trade_ip     = $_SERVER['REMOTE_ADDR'];
                $rs->save();

            }
            $msg = "付款成功！如果您选择的是即时到帐服务那么您的帐号已经生效。";
            return $this->msg($msg,url('default::service/index'));

        }else 
        {
             #dump($_GET);
             return $this->msg('您好，为了保障数据有效性，我们采用异步通知收款，如果您的帐号未生效，请联系我们。',url('default::service/index'));
        }
    }

    function actionNotify()
    {
        $root_dir = Q::ini('app_config/ROOT_DIR');
        require_once($root_dir . Q::ini('appini/alipay_dir') ."lib/alipay_notify.class.php");
        
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify(Q::ini('appini/payment/alipay'));
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            $out_trade_no	= $_POST['out_trade_no'];	    //获取订单号
            $trade_no		= $_POST['trade_no'];	    	//获取支付宝交易号
            $total			= $_POST['price'];				//获取总价格
            

            $rs = Invoice::find('order_number = ? AND trade_status != ?',$_POST['out_trade_no'],'TRADE_FINISHED')->getOne();
            if($rs->id())
            {
                if(!empty($total))
                $rs->total_fee    = $total;

                $rs->trade_no     = $trade_no;
                $rs->trade_time   = time();
                $rs->trade_status = $_POST['trade_status'];
                $rs->buyer_email  = isset($_POST['buyer_email']) ? $_POST['buyer_email']:'0';
                $rs->due_time     = ($rs->per_day * 24 * 3600) + $rs->trade_time;
                $rs->save();

                $order = Order::find('order_id = ?',$rs->order_id)->getOne();
                $order->status   = 'pending';
                $order->save();
            }

            if(Q::ini('appini/email_notify'))
            {
                Mail_Api::send('['. Q::ini('appini/meta/title') .']'.$order->name.'的账单状态['. strip_tags($rs->status(false)) .']','当前状态：' . $rs->status(false). ' 详情请登陆系统查看：'. Q::ini('appini/meta/url') .'/service/invoice',$order->account->user_mail,'客户');
            }

            if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
            //该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
            
                //判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                    
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
            //该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
            
                //判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                    
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
            //该判断表示卖家已经发了货，但买家还没有做确认收货的操作
            
                //判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                    
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if($_POST['trade_status'] == 'TRADE_FINISHED') {
            //该判断表示买家已经确认收货，这笔交易完成
            
                //判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                
                if($rs->id())
                {
                    $order = Order::find('order_id = ?',$rs->order_id)->getOne();
                    
                    $username  = $order->username;
                    $password  = $order->password;
                    $groupname = $order->groupname;

                    if($order->user_id != '10')
                    {
                        $radUser = Radcheck::find('username =?',$username)->getOne();
                        $radUser->username = $username;
                        $radUser->attribute = 'Cleartext-Password';
                        $radUser->op = ':=';
                        $radUser->value = $password;
                        $radUser->save();
                    
                        /*
                        $test = QDB::getConn()->getOne("SELECT `groupname` FROM `radusergroup` WHERE `username` = '{$username}'");
                        if(!$test)
                        {
                            $radGroup_SQL = "INSERT INTO `radusergroup` (`username`, `groupname`, `priority`) VALUES ('{$username}', '{$groupname}', '1')";
                        }else
                        {
                            $radGroup_SQL = "UPDATE `radusergroup` SET `groupname` = '{$groupname}' WHERE `username` = '{$order->username}'";
                        }

                        QDB::getConn()->execute($radGroup_SQL);
                        */
                        
                        $radGroup = Radusergroup::find('username =?',$order->username)->getOne();
                        $radGroup->username  = $order->username;
                        $radGroup->groupname = $order->groupname;
                        $radGroup->priority = '1';
                        $radGroup->save();

                    }
                    
                    $order->status = 'approve';
                    $order->save();
                }

                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else {
                //其他状态判断
                echo "success";

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

}