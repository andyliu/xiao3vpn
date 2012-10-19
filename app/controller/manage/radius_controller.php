<?php

class Controller_Manage_Radius extends Controller_Manage
{

    function actionIndex()
    {
        $msg = $this->_app->getFlashMessage();

        $this->_view['_MSG'] = $msg ? $msg:'由于 Free Radius 配置五花八门，这里仅仅相当于一个小型phpMyAdmin，“删除”功能使用后是无法恢复的！';

        #check用户
        $pageuc = (int) $this->_context->get('pageuc',1);
        $orm = Radcheck::find();
        $orm->limitPage($pageuc,20);
        $this->_view['rsuc'] = $orm->getAll();
        $this->_view['pguc'] = $orm->getPag();
        
        #reply用户
        $pageur = (int) $this->_context->get('pageur',1);
        $orm = Radreply::find();
        $orm->limitPage($pageur,20);
        $this->_view['rsur'] = $orm->getAll();
        $this->_view['pgur'] = $orm->getPag();
        
        #reply user group
        $pageug = (int) $this->_context->get('pageug',1);
        $orm = Radusergroup::find();
        $orm->limitPage($pageug,20);
        $this->_view['rsug'] = $orm->getAll();
        $this->_view['pgug'] = $orm->getPag();

        #check组
        $pagegc = (int) $this->_context->get('pagegc',1);
        $orm = Radgroupcheck::find();
        $orm->limitPage($pagegc,20);
        $this->_view['rsgc'] = $orm->getAll();
        $this->_view['pggc'] = $orm->getPag();
        
        #reply组
        $pagegr = (int) $this->_context->get('pagegr',1);
        $orm = Radgroupreply::find();
        $orm->limitPage($pagegr,20);
        $this->_view['rsgr'] = $orm->getAll();
        $this->_view['pggr'] = $orm->getPag();

    }

    function actionModify()
    {
        $type = $this->_context->type;
        $name = $this->_context->get('name');
        $page = (int) $this->_context->get('page',1);

        if(($deleteid = $this->_context->deleteid))
        {
            if($type == 'check')
            {
                Radcheck::meta()->deleteWhere('id = ?',$deleteid);
            }else
            {
                Radreply::meta()->deleteWhere('id = ?',$deleteid);
            }
            return $this->msg($tip = '删除成功',url('manage::radius/index'));
        }

        if($this->_context->isPost())
        {
            $post = $this->_context->post();
            $orm = $type == 'check' ? Radcheck::find():Radreply::find();
            if(isset($post['id']))
            {
                $orm->where('id = ?',(int) $post['id']);
                $orm = $orm->getOne();
                unset($post['type'],$post['id']);
                $orm->changeProps($post);
                $orm->save();
                return $this->msg($tip = '修改成功',url('manage::radius/modify',array('type'=>$type,'name'=>$post['username'])));
            }else
            {
                $orm->where('id = ?',0);
                $orm = $orm->getOne();
                unset($post['type']);
                $orm->changeProps($post);
                $orm->save();
                return $this->msg($tip = '添加成功',url('manage::radius/modify',array('type'=>$type,'name'=>$post['username'])));
            }
        }
    
        $rs   = $type == 'check' ? Radcheck::find():Radreply::find();
        $rs->where('username = ?',$name);
        $rs->limitPage($page,20);
        $this->_view['rs'] = $rs->getAll();
        $this->_view['pg'] = $rs->getPag();
        $this->_view['tg']   = $type == 'check' ? 'Radius Check':'Radius Reply';
        $this->_view['type'] = $type == 'check' ? 'check':'reply';
        $this->_view['_UDI'] = 'manage::radius/index';
    }

    function actionUsergroup()
    {
        $page = (int) $this->_context->get('page',1);
        $username  = $this->_context->username;
        $groupname = $this->_context->groupname;
        
        $rs = Radusergroup::find();
        if($username)  $rs->where('username = ?',$username);
        if($groupname) $rs->where('groupname = ?',$groupname);

        if(($deleted = $this->_context->deleted))
        {
            Radusergroup::meta()->deleteWhere('username = ? AND groupname = ?',$username,$groupname);
            return $this->msg($tip = '删除成功',url('manage::radius/usergroup'));
        }

        if($this->_context->isPost())
        {
            $post = $this->_context->post();
            $orm = Radusergroup::find('username = ?',$post['username'])->getOne();
            $orm->changeProps($post);
            $orm->save();
            #dump($orm);

            return $this->msg($tip = '操作成功',url('manage::radius/usergroup'));
        }
        $this->_view['_UDI'] = 'manage::radius/index';
        $rs->limitPage($page,20);
        $this->_view['rs'] = $rs->getAll();
        $this->_view['pg'] = $rs->getPag();
    }

    function actionModgroup()
    {
        $type = $this->_context->type;
        $name = $this->_context->get('name');
        $page = (int) $this->_context->get('page',1);
        
        if(($deleteid = $this->_context->deleteid))
        {
            if($type == 'check')
            {
                Radgroupcheck::meta()->deleteWhere('id = ?',$deleteid);
            }else
            {
                Radgroupreply::meta()->deleteWhere('id = ?',$deleteid);
            }
            return $this->msg($tip = '删除成功',url('manage::radius/index'));
        }

        if($this->_context->isPost())
        {
            $post = $this->_context->post();
            $orm = $type == 'check' ? Radgroupcheck::find():Radgroupreply::find();
            if(isset($post['id']))
            {
                $orm->where('id = ?',(int) $post['id']);
                $orm = $orm->getOne();
                unset($post['type'],$post['id']);
                $orm->changeProps($post);
                $orm->save();
                return $this->msg($tip = '修改成功',url('manage::radius/modgroup',array('type'=>$type,'name'=>$post['groupname'])));
            }else
            {
                $orm->where('id = ?',0);
                $orm = $orm->getOne();
                unset($post['type']);
                $orm->changeProps($post);
                $orm->save();
                return $this->msg($tip = '添加成功',url('manage::radius/modgroup',array('type'=>$type,'name'=>$post['groupname'])));
            }
        }
    
        $rs   = $type == 'check' ? Radgroupcheck::find():Radgroupreply::find();
        $rs->where('groupname = ?',$name);
        $rs->limitPage($page,20);
        $this->_view['rs'] = $rs->getAll();
        $this->_view['pg'] = $rs->getPag();
        $this->_view['tg']   = $type == 'check' ? 'Radgroup Check':'Radgroup Reply';
        $this->_view['type'] = $type == 'check' ? 'check':'reply';
        $this->_view['_UDI'] = 'manage::radius/index';
    }

}