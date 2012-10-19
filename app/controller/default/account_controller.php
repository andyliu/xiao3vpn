<?php

class Controller_Default_Account extends Controller_Account
{

    function actionIndex()
    {

        $account = QDB::getConn()->getOne($sql ="SELECT count(`groupname`) as total FROM radusergroup WHERE groupname =  'NOR'");
        
        $this->_view['account'] = $account;
        if((15 - $account < 1))
        {
            $this->_view['_MSG'] = '目前产品已经卖光了，请填写您有效的邮箱，以便接收到货通知。';
        }

        $rs = $this->_user;
        $form = Form_Common::createForm('','profile');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $post = $form->values();

            $user_mail = $post['user_mail'];
            $user_pass = $post['user_pass'];
            
            $msg = null;
            if($user_pass)
            {
                if($user_pass != $post['user_pass_checked'])
                {
                    $msg = '两次密码输入不一致;';
                }
            }
            if($msg === null)
            {
                if(!Q::ini('isDemo'))
                {
                    $rs->user_pass  = sha1(md5('sike' . $user_pass . Q::ini('appini/secret_key')));
                    $rs->save();

                    return $this->msg('修改成功。',url('default::account/index'));
                }
                $msg = '演示用户禁止修改密码';
            }
            
            $this->_view['_MSG'] = $msg;
        }

        #$form->add(QForm::ELEMENT, $prop, array('_ui' => 'dropdownlist','_label'=>$label_text . '：', 'class' => 'slt toggle_input'));
        #$elem = $form->element($prop);

        $this->_view['_META_TITLE'] = '我的账户';

        $form->element('user_mail')->value    = $rs->user_mail;
        $form->element('user_mail')->disabled = 'disabled';
        

        $this->_view['form'] = $form;

    }

    function actionTraffic()
    {
        $sql     ="SELECT SUM( acctinputoctets + acctoutputoctets ) as total FROM radacct WHERE UserName =  '{$this->_user_name}'";
        
        $traffic_total = QDB::getConn()->getOne($sql);
        $this->_view['traffic_total'] = $traffic_total;
        
        $page = (int) $this->_context->get('page',1);
        $orm = Radacct::find('username =?',$this->_user_name)->order('radacctid desc');
        $orm->limitPage($page,12);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();
    }

    function actionSignout()
    {
        $this->_app->cleanCurrentUser();
        return $this->msg('您已经退出系统',url('default::default/index'));
    }

}

