<?php

class Control_Pagination extends QUI_Control_Abstract
{
    function render()
    {
        $pagination = $this->pagination;
        $pageuri    = ($this->pageuri) ? $this->pageuri:'page';
        $udi        = $this->get('udi', $this->_context->requestUDI());
        $length     = $this->get('length', 9);
        $slider     = $this->get('slider', 2);
        $prev_label = $this->get('prev_label', '上一页');
        $next_label = $this->get('prev_label', '下一页');
        $url_args   = $this->get('url_args');
        
        $_get = $_GET;
        unset($_get[$pageuri]);unset($_get['module']);unset($_get['controller']);unset($_get['action']);
        #$url_args   = $url_args ? $url_args : $_GET;

        $out = "<div class=\"pagination\">\n";

        if ($this->get('show_count'))
        {
            $out .= "<p>共 {$pagination['record_count']} 个条目</p>\n";
        }
        $out .= "<ul>\n";

        $url_args = (array)$url_args;
        if ($pagination['current'] == $pagination['first'])
        {
            $out .= "<li class=\"disabled\">&#171; {$prev_label}</li>\n";
        }
        else
        {
            $url_args[$pageuri] = $pagination['prev'];
            $url  = url($udi, $url_args);
            $url .= !$_get ? '':'?'. http_build_query($_get);
            $out .= "<li><a href=\"{$url}\">&#171; {$prev_label}</a></li>\n";
        }

        $base = $pagination['first'];
        $current = $pagination['current'];

        $mid = intval($length / 2);
        if ($current < $pagination['first'])
        {
            $current = $pagination['first'];
        }
        if ($current > $pagination['last'])
        {
            $current = $pagination['last'];
        }

        $begin = $current - $mid;
        if ($begin < $pagination['first'])
        {
            $begin = $pagination['first'];
        }
        $end = $begin + $length - 1;
        if ($end >= $pagination['last'])
        {
            $end = $pagination['last'];
            $begin = $end - $length + 1;
            if ($begin < $pagination['first'])
            {
                $begin = $pagination['first'];
            }
        }

        if ($begin > $pagination['first'])
        {
            for ($i = $pagination['first']; $i < $pagination['first'] + $slider && $i < $begin; $i ++)
            {
                $url_args[$pageuri] = $i;
                $in = $i + 1 - $base;
                $url  = url($udi, $url_args);
                $url .= !$_get ? '':'?'. http_build_query($_get);
                $out .= "<li><a href=\"{$url}\">{$in}</a></li>\n";
            }

            if ($i < $begin)
            {
                $out .= "<li class=\"none\">...</li>\n";
            }
        }

        for ($i = $begin; $i <= $end; $i ++)
        {
            $url_args[$pageuri] = $i;
            $in = $i + 1 - $base;
            if ($i == $pagination['current'])
            {
                $out .= "<li class=\"current\">{$in}</li>\n";
            }
            else
            {
                $url  = url($udi, $url_args);
                $url .= !$_get ? '':'?'. http_build_query($_get);
                $out .= "<li><a href=\"{$url}\">{$in}</a></li>\n";
            }
        }

        if ($pagination['last'] - $end > $slider)
        {
            $out .= "<li class=\"none\">...</li>\n";
            $end = $pagination['last'] - $slider;
        }

        for ($i = $end + 1; $i <= $pagination['last']; $i ++)
        {
            $url_args[$pageuri] = $i;
            $in = $i + 1 - $base;
            $url  = url($udi, $url_args);
            $url .= !$_get ? '':'?'. http_build_query($_get);
            $out .= "<li><a href=\"{$url}\">{$in}</a></li>\n";
        }

        if ($pagination['current'] == $pagination['last'])
        {
            $out .= "<li class=\"disabled\">{$next_label} &#187;</li>\n";
        }
        else
        {
            $url_args[$pageuri] = $pagination['next'];
            $url  = url($udi, $url_args);
            $url .= !$_get ? '':'?'. http_build_query($_get);
            $out .= "<li><a href=\"{$url}\">{$next_label} &#187;</a></li>\n";
        }

        $out .= "</ul></div>\n";

        return $out;
    }
}
