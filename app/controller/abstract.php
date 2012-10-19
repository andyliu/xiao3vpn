<?php

/**
 * 应用程序的公共控制器基础类
 *
 * 可以在这个类中添加方法来完成应用程序控制器共享的功能。
 */
abstract class Controller_Abstract extends QController_Abstract
{
    /**
     * 控制器动作要渲染的数据
     *
     * @var array
     */
    protected $_view = array();

    /**
     * 控制器要使用的视图类
     *
     * @var string
     */
    protected $_view_class = 'QView_Render_PHP';

    /**
     * 控制器要使用的视图
     *
     * @var string
     */
    protected $_viewname = null;

    protected $_user,$_user_id;

    /**
     * 控制器所属的应用程序
     *
     * @var CommunityApp
     */
    protected $_app;

    /**
     * 构造函数
     */
    function __construct($app)
    {
        parent::__construct();
        $this->_app = $app;
    }

    /**
     * 执行指定的动作
     *
     * @return mixed
     */
    function execute($action_name, array $args = array())
    {
        $action_method = "action{$action_name}";

        // 执行指定的动作方法
        $this->_before_execute();

        #IFDEF DBEUG
        QLog::log('EXECUTE ACTION: '. get_class($this) . '::' . $action_method . '()', QLog::DEBUG);
        #ENDIF

        $this->_view['_MSG'] = $this->_app->getFlashMessage();

        Q::replaceIni('OrderAvailable',true);
        Q::replaceIni('isAdmin',false);
        #dump(Q::ini('appini/managers'));
        if($this->_user)
        {
            if(in_array($this->_user->user_mail,Q::ini('appini/managers')))
            {
                Q::replaceIni('isAdmin',true);
            }
        }

        $this->_view['_UDI'] = QContext::instance()->requestUDI(false);

        $response = call_user_func_array(array($this, $action_method), $args);
        $this->_after_execute($response);

        if (is_null($response) && is_array($this->_view))
        {
            // 如果动作没有返回值，并且 $this->view 不为 null，
            // 则假定动作要通过 $this->view 输出数据
            $config = array('view_dir' => $this->_getViewDir());
            $response = new $this->_view_class($config);
            $response->setViewname($this->_getViewName())->assign($this->_view);
            #dump($response);
            $this->_before_render($response);
        }
        elseif ($response instanceof $this->_view_class)
        {
            $response->assign($this->_view);
            $this->_before_render($response);
        }
        #dump($response);

        return $response;
    }

    /**
     * 指定的控制器动作未定义时调用
     *
     * @param string $action_name
     */
    function _on_action_not_defined($action_name)
    {
    }

    /**
     * 执行控制器动作之前调用
     */
    protected function _before_execute()
    {
        $this->_user_id             = $this->_app->currentUser();
        $this->_view['_USER_ID']    = $this->_user_id;

        $this->_view['_META_TITLE']       = Q::ini('appini/meta/title');
        $this->_view['_META_KEYWORDS']    = Q::ini('appini/meta/keywords');
        $this->_view['_META_DESCRIPTION'] = Q::ini('appini/meta/description');
    }

    /**
     * 执行控制器动作之后调用
     *
     * @param mixed $response
     */
    protected function _after_execute(& $response)
    {
        
    }

    function msg($tip = null,$url = null,$delay = null)
    {
        
        if($tip)
            $this->_app->setFlashMessage($tip);
        if($url)
            return $this->_redirect($url,$delay);
    }

    /**
     * 渲染之前调用
     *
     * @param QView_Render_PHP
     */
    protected function _before_render($response)
    {
    }

    /**
     * 准备视图目录
     *
     * @return array
     */
    protected function _getViewDir()
    {
        
        $dir = Q::ini('app_config/APP_DIR') . "/view";
        if($this->_context->module_name)
        {
             $dir .= '/' . $this->_context->module_name;
        }
        #dump($dir);
        return $dir;
    }

    /**
     * 确定要使用的视图
     *
     * @return string
     */
    protected function _getViewName()
    {
        if ($this->_viewname === false)
        {
            return false;
        }
        $viewname = empty($this->_viewname) ? $this->_context->controller_name . '/' . $this->_context->action_name : $this->_viewname;

        return strtolower($viewname);
    }

}

