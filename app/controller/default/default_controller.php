<?php

class Controller_Default_Default extends Controller_Abstract
{

    function actionIndex()
    {
        #return $this->msg($tip = null,url('default::default'),2);
        
        $msg = $this->_app->getFlashMessage();

        $this->_view['_MSG'] = $msg ? $msg:'我们为广大站长提供稳定高速的加速服务，承诺每台服务器最多15位用户，我们对利用该服务进行非法活动、下载(包含但不限于BT/PT)等行为，立即删号处理。';

        $account = QDB::getConn()->getOne($sql ="SELECT count(`groupname`) as total FROM radusergroup WHERE groupname !=  'NOP'");
        $this->_view['account_left'] = (15 - $account);
    }
    function actionActivate()
    {
        
        if(($verify = $this->_context->verify))
        {
            $rs = Account::find('verify_hash = ? ',$verify)->getOne();
            if($rs->id())
            {
                $rs->is_locked   = '0';
                $rs->verify_hash = 0;
                $rs->save();

                $this->_app->changeCurrentUser($rs->id());

                return $this->msg('登陆成功',url('default::account'));
            }
        }
    }

    function actionResetpwd()
    {
        
        if(($verify = $this->_context->verify))
        {
            $rs = Account::find('verify_hash = ? ',$verify)->getOne();
            if($rs->id())
            {
                $rs->verify_hash = 0;
                $rs->save();

                $this->_app->changeCurrentUser($rs->id());

                return $this->msg('请修改您的帐号密码',url('default::account'));
            }
        }

        $form = Form_Common::createForm('','resetpwd');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $post = $form->values();
            $user_mail = $post['user_mail'];
            
            $verify_hash = md5('sike' . rand(1000,9999) . time() );

            $rs = Account::find('user_mail = ? ',$user_mail);
            #echo $rs;
            $rs = $rs->getOne();
            if($rs->id())
            {
                $send = Mail_Api::send('['.Q::ini('appini/meta/title').']密码重置邮件','请点击连接重置您帐号的密码：'. Q::ini('appini/meta/url') .'/resetpwd?verify=' . $verify_hash,$user_mail,'客户');
                if($send === true)
                {
                    $rs->verify_hash = $verify_hash;
                    $rs->save();
                    return $this->msg('已经将重置密码连接发送到您的邮箱，请注意查收。',url('default::default/resetpwd'));
                }
                return $this->msg('系统错误，请稍后重试。',url('default::default/resetpwd'));
            }

            return $this->msg('该邮箱帐号未注册。',url('default::default/resetpwd'));
        }
        #dump(md5('xiao3.org'));
        $this->_view['_META_TITLE'] = '找回密码';
        $this->_view['form'] = $form;
    }

    function actionSignin()
    {
        $form = Form_Common::createForm('','signin');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $post = $form->values();
            $user_mail = $post['user_mail'];
            $user_pass = sha1(md5('sike' . $post['user_pass'] . Q::ini('appini/secret_key')));

            $rs = Account::find('user_mail = ? AND user_pass = ? AND is_locked = ?',$user_mail,$user_pass,'0');
            #echo $rs;
            $rs = $rs->getOne();
            if($rs->id())
            {
                $this->_app->changeCurrentUser($rs->id());
                return $this->msg('登陆成功',url('default::service'));
            }

            return $this->msg('登陆失败，请确认您的帐号有效性。',url('default::default/signin'));
        }
        #dump(md5('xiao3.org'));
        $this->_view['_META_TITLE'] = '登陆系统';
        $this->_view['form'] = $form;

        $this->_viewname = 'account/signin';
    }


    function actionSignup()
    {
        $form = Form_Common::createForm('','signup');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $msg = null;
            $post = $form->values();

            $user_mail = $post['user_mail'];
            $user_pass = $post['user_pass'];
            $user_ip   = $_SERVER['REMOTE_ADDR'];

            if($user_pass != $post['user_pass_checked'])
            {
                $msg = '两次密码输入不一致;';
            }
            
            $user_pass = sha1(md5('sike' . $user_pass . Q::ini('appini/secret_key')));
            #dump($user_pass);exit;

            $test = Account::find('user_mail = ? ',$user_mail)->getOne();
            if($test->id())
            {
                $msg = '邮箱帐号已被使用;';
            }
            if($msg === null)
            {
                $rs = new Account(array('user_mail'=>$user_mail,'user_pass'=>$user_pass,'user_ip'=>$user_ip));
                if(Q::ini('appini/email_activate'))
                {
                    $rs->is_locked = '1';
                    $verify_hash = md5('sike' . rand(1000,9999) . time() );
                    $rs->verify_hash = $verify_hash;
                    $rs->save();
                    $send = Mail_Api::send('['.Q::ini('appini/meta/title').']帐号激活邮件','请点击连接激活您的帐号：'. Q::ini('appini/meta/url') .'/activate?verify=' . $verify_hash,$user_mail,'客户');
                    if($send === true)
                    {
                        return $this->msg('注册成功,请登陆您的邮箱获取激活连接。',url('default::default/signin'));
                    }
                }
                $rs->is_locked = '0';
                $rs->save();
                $this->_app->changeCurrentUser($rs->id());

                return $this->msg('注册成功',url('default::service'));
            }

            $this->_view['_MSG'] = $msg;
            #dump($post);
            $form->element('services')->checked = $post['services'];
        }

        $this->_view['_META_TITLE'] = '注册帐号';
        $this->_view['form'] = $form;

        $this->_viewname = 'account/signup';
    }
}

