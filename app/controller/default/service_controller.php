<?php

class Controller_Default_Service extends Controller_Account
{
    function actionIndex()
    {
        $rs = Order::find('user_id = ?',$this->_user_id)->order('created DESC')->getAll();

        $form = Form_Common::createForm('','order');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $post = $this->_context->post();
            $test = Order::find('username = ? ',$post['username'])->getOne();
            if($test->id())
            {
                $msg = 'VPN帐号已被使用;';
            }

            if($msg === null)
            {
                $plan = Plans::find('plan_id = ?',(int)$post['plan_id'])->getOne();
                $sku  = json_decode($plan->sku,true);
                if(!$sku)
                {
                    return $this->msg('错误，“销售属性不能为空”',url('default::service'));
                }

                $new = new Order($post);
                $new->user_id = $this->_user_id;
                $new->name    = $plan->name;
                $new->groupname    = $plan->groupname;
                $new->cost    = $plan->getCost($post['billingcycle']);
                
                
                $new->save();
                $perday = Q::ini('appini/cycle_invoice');
                if(!in_array($post['billingcycle'],array_keys($perday)))
                {
                    $post['billingcycle'] = 'annually';
                }

                if($new->id())
                {
                    $iv = new Invoice(array('order_id'=>$new->id(),'total_fee'=>$new->cost));
                    $iv->order_number = 'x'. date('YmdHis') . rand(111,999);
                    $iv->per_day      = $perday[$post['billingcycle']];
                    $iv->save();
                }
                
                return $this->msg('订购成功',url('default::service'));
            }

            $this->_view['_MSG'] = $msg;
        }
        
        if($this->_user_id == 1)
        {
            #Mail_Api::send('测试','内容','sike@xiao3.org','小桑');
        }

        $this->_view['_META_TITLE'] = '我的服务';
        $this->_view['form'] = $form;
        $this->_view['rs']   = $rs;
    }

    function actionDetail()
    {
        $id = (int) $this->_context->id;
        if(Q::ini('isAdmin'))
        {
            $order = Order::find('order_id = ?',$id)->getOne();
            
            if($this->_context->isPost())
            {
                $order->status = $this->_context->post('status','pending');
                $order->save();

                $radUser = Radcheck::find('username =?',$order->username)->getOne();
                $radUser->username = $order->username;
                $radUser->attribute = 'Cleartext-Password';
                $radUser->op = ':=';
                $radUser->value = $order->password;
                $radUser->save();

                $test = QDB::getConn()->getOne("SELECT `groupname` FROM `radusergroup` WHERE `username` = '{$order->username}'");
                // 如果不存在，则生成之。
                if(!$test)
                {
                    if($order->status == 'approve')
                    {
                        $radGroup_SQL = "INSERT INTO `radusergroup` (`username`, `groupname`, `priority`) VALUES ('{$order->username}', '{$order->groupname}', '1')";
                    }else
                    {
                        $radGroup_SQL = "INSERT INTO `radusergroup` (`username`, `groupname`, `priority`) VALUES ('{$order->username}', 'NOP', '1')";
                    }
                }else
                {
                    if($order->status == 'approve')
                    {
                        $radGroup_SQL = "UPDATE `radusergroup` SET `groupname` = '{$order->groupname}' WHERE `username` = '{$order->username}'";
                    }else
                    {
                        $radGroup_SQL = "UPDATE `radusergroup` SET `groupname` = 'NOP' WHERE `username` = '{$order->username}'";
                    }
                }

                QDB::getConn()->execute($radGroup_SQL);

                return $this->msg('状态以更新',url('default::service/detail',array('id'=>$id)));

            }

        }else
        {
            $order = Order::find('user_id = ? and order_id = ?',$this->_user_id,$id)->getOne();
        }
        
        $this->_view['order'] = $order;

        $username = $order->username;
        $sql     ="SELECT SUM( acctinputoctets + acctoutputoctets ) as total FROM radacct WHERE UserName =  '{$username}'";
        
        $traffic_total = QDB::getConn()->getOne($sql);
        $this->_view['traffic_total'] = $traffic_total;
        
        $page = (int) $this->_context->get('page',1);
        $orm = Radacct::find('username =?',$username)->order('radacctid desc');
        $orm->limitPage($page,12);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();
        
        $this->_view['username'] = $username;
        $this->_view['_UDI'] = 'default::service/index';
    }
    
    function actionChangePlans()
    {
        if($this->_context->isAjax())
        {
            $html_def = '<option value="monthly" selected="selected">月付(10元)</option>
            <option value="quarterly">季付(25元)</option>
            <option value="semi-annually">半年付(50元)</option>';

            $plan = Plans::find('plan_id = ?',(int)$this->_context->id)->getOne();
            $sku  = json_decode($plan->sku,true);
            $html = '';
            foreach($sku as $key=>$val)
            {
                $html .= '<option value="'. $key .'">'. $val['print'] .'('. $val['price'] .')元</option>';
            }
            return $html;
        }
    }

    function actionInvoice()
    {
        $page = (int) $this->_context->get('page',1);
        $orm = Invoice::find('[order.user_id] =?',$this->_user_id)->order('created desc');
        $orm->limitPage($page,12);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();
    }

}