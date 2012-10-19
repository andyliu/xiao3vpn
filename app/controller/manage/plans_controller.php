<?php

class Controller_Manage_Plans extends Controller_Manage
{

    function actionIndex()
    {
        $page = (int) $this->_context->get('page',1);
        $orm = Plans::find()->order('plan_id DESC');
        $orm->limitPage($page,20);
        $this->_view['rs'] = $orm->getAll();
        $this->_view['pg'] = $orm->getPag();

        $form = Form_Common::createForm('','manage/plans');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $post = $this->_context->post();
            $dsp = array_combine($post['config']['val'],$post['config']['dsp']);
            $tmp = array();
            foreach($dsp as $val=>$dsp)
            {
                $tmp[] = array('price'=>$val,'print'=>$dsp);
            }
            $sku = array_combine($post['config']['key'],$tmp);
            
            $rs = new Plans();
            $rs->name           = $post['name'];
            $rs->groupname      = $post['groupname'];
            $rs->desc           = $post['desc'];
            $rs->sku            =  (phpversion() >= '5.4') ? json_encode($sku,JSON_UNESCAPED_UNICODE):json_encode($sku);

            $rs->save();

            return $this->msg($tip = '添加成功',url('manage::plans/index'));

        }

        $groupname_list = Radgroupcheck::find()->group('groupname')->getAll()->toHashMap('groupname','groupname');
        $form->element('groupname')->items   = $groupname_list;
    
        $this->_view['form']   = $form;
    }

    function actionModify()
    {
        $id = (int) $this->_context->get('id');
        $rs = Plans::find('plan_id = ?',$id)->getOne();
        $form = Form_Common::createForm('','manage/plans');
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            $post = $this->_context->post();
            $dsp = array_combine($post['config']['val'],$post['config']['dsp']);
            $tmp = array();
            foreach($dsp as $val=>$dsp)
            {
                $tmp[] = array('price'=>$val,'print'=>$dsp);
            }
            $sku = array_combine($post['config']['key'],$tmp);
            
            $rs->name           = $post['name'];
            $rs->desc           = $post['desc'];
            $rs->groupname      = $post['groupname'];
            $rs->sku            =  (phpversion() >= '5.4') ? json_encode($sku,JSON_UNESCAPED_UNICODE):json_encode($sku);

            $rs->save();

            return $this->msg($tip = '修改成功',url('manage::plans/modify',array('id'=>$id)));
        }

        $groupname_list = Radgroupcheck::find()->group('groupname')->getAll()->toHashMap('groupname','groupname');
        $form->element('groupname')->items   = $groupname_list;

        $form->import($rs->toArray());
        
        $this->_view['_UDI']   = 'manage::plans/index';
        $this->_view['form']   = $form;
        $this->_view['rs']     = $rs;

    }
}
