<?php

class Form_Common extends QForm 
{

    /**
     * 创建 From_Post 表单对象
     */
    static function createForm($action,$config_file)
    {
        $form = self::_createFromConfig($action, $config_file);
        return $form;
    }

    /**
     * 从配置文件创建表单
     */
    static protected function _createFromConfig($action, $config_name) 
    {
        $form       = new Form_Common('form', $action);
        $filename   = rtrim(dirname(__FILE__), '/\\') . DS . $config_name . '.yaml.php';
        $form->loadFromConfig(Helper_YAML::loadCached($filename));

        return $form;
    }

}

?>
