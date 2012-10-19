<?php
// $Id: checkboxgroup_abstract.php 2646 2009-08-11 06:12:31Z jerry $

/**
 * 定义 Control_CheckboxGroup_Abstract 类
 *
 * @link http://qeephp.com/
 * @copyright Copyright (c) 2006-2009 Qeeyuan Inc. {@link http://www.qeeyuan.com}
 * @license New BSD License {@link http://qeephp.com/license/}
 * @version $Id: checkboxgroup_abstract.php 2646 2009-08-11 06:12:31Z jerry $
 * @package webcontrols
 */

/**
 * Control_CheckboxGroup_Abstract 是群组多选框的基础类
 *
 * @author YuLei Liao <liaoyulei@qeeyuan.com>
 * @version $Id: checkboxgroup.php 2014 2010-12-30 11:21:29Z bannelu $
 * @package webcontrols
 */
abstract class Control_Listtree_Abstract extends QUI_Control_Abstract
{
    protected $out = '';

	protected function _make($type, $suffix,$sub_items = false,$is_sub = false)
	{
        static $id_index  = 1;

        $items = $sub_items ? $sub_items:$this->_extract('items');

		$max = count($items);
		if ($max == 0) return '';

		$selected = $this->_extract('value');
        $caption_class = $this->_extract('caption_class');
        $config = $this->_extract('config');
        $hkey = $config['hashMap'][0];
        $hval = $config['hashMap'][1];

        foreach ($items as $item)
        {
            $value = $item->$hkey;
            $caption = $item->$hval;

            $checked = false;
            if($type == 'checkbox' || $type == 'radio')
            {
                 if (!is_array($selected))
                 {
                     $selected = array($selected);
                 }
                 if (in_array($value,$selected))
                 {
                     $checked = true;
                 }
            }
            else
            {
                if ($value == $selected && strlen($value) == strlen($selected) && strlen($selected) > 0)
                {
                    $checked = true;
                }
            }

            $this->checked_by_value = $checked;

			$this->out .= "<li style='line-height:18px;'>";
		    $name = $this->name() . $suffix;
		    $id = $this->id() . "_{$id_index}";

		    $id_index++;

		    $parent =  isset($config['parent']) ? $config['parent']:false;

			if($parent != 'hide' || $sub_items)
			{
			    $this->out .= "<input type=\"{$type}\" ";
                $this->out .= "id=\"{$id}\" ";
                if (strlen($value) == 0) $value = 1;
                if($parent != 'noName' || $sub_items)
                {
                    $this->out .= "name=\"{$name}\" ";
                }
			    $this->out .= 'value="' . htmlspecialchars($value) . '" ';
			    $this->out .= $this->_printAttrs();
			    $this->out .= $this->_printChecked();
                $this->out .= $this->_printDisabled();
			    $this->out .= "/>&nbsp;";
			}
            if ($caption)
            {
                $this->out .= Q::control('label', "{$id}_label", array(
                    'for'     => $id,
                    'caption' => $caption,
                    'class'   => $caption_class
                ))->render();
			}
            $this->out .= "</li>";

            //$item->children();
            //dump($item->children());
            if($ch = call_user_func(array($item,$config['nextFunc'])))
            {
                $this->out .= "<li><ul class=\"sub_cat\">";
                $this->_make($type,$suffix,$ch,true);
                $this->out .= "</ul></li>";
            }

		}

        $this->out = str_replace('<li><ul></ul></li>','',$this->out);
        return $this->out;
	}
}

