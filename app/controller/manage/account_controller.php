<?php

class Controller_Manage_Account extends Controller_Manage
{

    function actionIndex()
    {
        $page = (int) $this->_context->get('page',1);
        $orm = Account::find()->order('user_id DESC');
        $orm->limitPage($page,20);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();
        #return $this->msg($tip = null,url('default::default'),2);
    }

    function actionTraffic()
    {
        $page = (int) $this->_context->get('page',1);
        $orm = Radacct::find()->order('radacctid desc');
        $orm->limitPage($page,12);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();
    }

    function actionOnline()
    {
        $rs = Radacct::find()->where('acctstoptime IS NULL')->getAll();
        $this->_view['rs'] = $rs;

    }

    function actionOrder()
    {
        $page = (int) $this->_context->get('page',1);
        $orm = Order::find()->order('order_id DESC');
        $orm->limitPage($page,20);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();
    }

    function actionInvoice()
    {
        $page = (int) $this->_context->get('page',1);
        $orm = Invoice::find()->order('created desc');
        $orm->limitPage($page,12);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();
    }

    function actionModify()
    {
        $id = (int) $this->_context->get('id');
        $rs = Account::find('user_id = ?',$id)->getOne();
        if(!$rs->id())
        {
            return $this->msg($tip = '参数错误',url('manage::account'));
        }
        $form = Form_Common::createForm('','manage/profile');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $post = $form->values();
            $user_mail = $post['user_mail'];
            $user_pass = $post['user_pass'];
            $is_locked = $post['is_locked'] ? '1':'0';
            #dump($post);

            if($user_pass)
            {
                $user_pass = sha1(md5('sike' . $post['user_pass'] . Q::ini('appini/secret_key')));
                $rs->user_pass;
            }
            
            $rs->user_mail = $user_mail;
            $rs->is_locked = $is_locked;

            $rs->save();

            return $this->msg($tip = '修改成功',url('manage::account/modify',array('id'=>$id)));

        }

        $form->import($rs->toArray());
        $form->element('user_pass')->value   = '';
        $form->element('is_locked')->checked = $rs->is_locked;
        #dump($form->element('is_locked'));
    
        $this->_view['form']   = $form;
        $this->_view['rs']     = $rs;

        $order = Order::find('user_id = ?',$id)->order('created DESC')->getAll();

        $this->_view['order']  = $order;

        $this->_view['_UDI'] = 'manage::account/index';
    }
}

