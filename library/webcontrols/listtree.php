<?php
// $Id: checkboxgroup.php 2014 2009-01-08 19:01:29Z dualface $

/**
 * 定义 Control_CheckboxGroup 类
 *
 * @link http://qeephp.com/
 * @copyright Copyright (c) 2006-2009 Qeeyuan Inc. {@link http://www.qeeyuan.com}
 * @license New BSD License {@link http://qeephp.com/license/}
 * @version $Id: checkboxgroup.php 2014 2009-01-08 19:01:29Z dualface $
 * @package webcontrols
 */

/**
 * 构造一个多选框组
 *
 * @author YuLei Liao <liaoyulei@qeeyuan.com>, bannelu <bannelu@gmail.com>
 * @version $Id: checkboxgroup.php 2014 2010-12-30 11:21:29Z bannelu $
 * @package webcontrols
 */
class Control_Listtree extends Control_Listtree_Abstract
{
	function render()
	{   

	    $type = $this->get('type') ? $this->get('type'):"checkbox";
	    $suffix = ($type == "checkbox") ? '[]':'';
	    
	    $ret = '';
	    if($type == 'radio')
	    {
	        $ret = '<li><input type="radio" id="' . $this->id() .'_0" name="' . $this->name() .'" value="0" checked="checked" >
	                <label id="' . $this->id() .'_1_label" name=' . $this->name() .'_label" for="' . $this->id() .'" caption="无" >无</label></li>';
	    }
	    
	    $ret .= $this->_make($type,  $suffix);

		return $ret;
	}
}

