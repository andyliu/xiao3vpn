<?php


abstract class Controller_Account extends Controller_Abstract
{
    protected function _before_execute()
    {
        parent::_before_execute();

        $this->_user = Account::find('user_id = ?',$this->_user_id)->getOne();
        if(!$this->_user->id())
        {
            return $this->msg('请先登陆',url('default::default/signin'));
        }
    }
}