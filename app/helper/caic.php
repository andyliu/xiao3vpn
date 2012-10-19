<?php

class Helper_Caic
{
    protected $curl_crsc,$data_length,$curl_rest,$curl_error;

    public $curl_info;
    
    protected $user_agent = array(
        array('','Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
        #array('',"Baiduspider+(+http://www.baidu.com/search/spider.htm)"),
        #array('',"Mozilla/5.0 (compatible; YodaoBot/1.0; http://www.yodao.com/help/webmaster/spider/; )"),
        #array('',"Sosoimagespider+(+http://help.soso.com/soso-image-spider.htm)"),
        #array('','Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.152 Safari/535.19')
    );

    protected $preg_rest;


    static function meta()
    {
        return new self();
    }
    
    #$caic->dump($caic->preg('|<div>(.*)</div>',$str = '<div>1</div><div>2</div>'));
    function preg($pattern,$subject,$postion = 1)
    {
        $pattern = str_replace('/','\/',$pattern);
        if($pattern[0] == '|')
        {
            $pattern = substr_replace($pattern, '', 0,1);
            preg_match_all('/'.$pattern.'/Us',$subject,$matches);
            $this->preg_rest = $matches;
            if(isset($matches[$postion]))
            {
                return array_filter($matches[$postion]);
            }
        }else
        {
            preg_match('/'.$pattern.'/Us',$subject,$matches);
            $this->preg_rest = $matches;
            if(isset($matches[$postion]))
            {
                return trim($matches[$postion]);
            }
        }

        return null;
    }
    
    #$caic->dump($caic->wget($url = 'http://code.xiao3.org/us.php'));
    function wget($url,$arg = array(),$post = array(),$cookie_name = null)
    {
        if($this->curl_crsc)
        {
            curl_close($this->curl_crsc);
        }
        $this->curl_crsc = curl_init();
        $header = array();
        $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"; 
        $header[] = "Cache-Control: max-age=0"; 
        $header[] = "Connection: keep-alive"; 
        $header[] = "Keep-Alive: 300"; 
        $header[] = "Accept-Charset: iso-8859-1,utf-8;q=0.7,*;q=0.7"; 
        $header[] = "Accept-Language: en-us,en;q=0.5"; 
        $header[] = "Pragma: ";
        
        $temp_path = Q::ini('app_config/CONFIG_CACHE_SETTINGS/QCache_PHPDataFile/cache_dir') . '/';
        if($cookie_name)
        {
            $cookie_file = $temp_path . $cookie_name . '.tmp';
        }else
        {
            $cookie_file = tempnam($temp_path, "xi_");
        }
        #echo $temp_path;
        #exit;
        shuffle($this->user_agent);
        $user_agent = array_shift($this->user_agent);
        
        $_referer    = isset($arg['referer']) ?    $arg['referer']    : $user_agent[0];
        $_user_agent = isset($arg['user_agent']) ? $arg['user_agent'] : $user_agent[1];
        if($_referer)
        {
            curl_setopt( $this->curl_crsc, CURLOPT_REFERER, $_referer);
        }
        if($_user_agent)
        {
            curl_setopt( $this->curl_crsc, CURLOPT_USERAGENT, $_user_agent);
        }
        
        if(isset($arg['cookie']))
        {
            $temp = array();
            foreach ($arg['cookie'] as $key=>$val)
            {
                $temp[] = $key . '=' . $val;
            }
            curl_setopt( $this->curl_crsc,CURLOPT_COOKIE,implode(':',$temp));
        }
        
        #echo $cookie_file;

        curl_setopt( $this->curl_crsc, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $this->curl_crsc, CURLOPT_HTTPHEADER, $header); 
        curl_setopt( $this->curl_crsc, CURLOPT_COOKIEJAR, $cookie_file );
        curl_setopt( $this->curl_crsc, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt( $this->curl_crsc, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $this->curl_crsc, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $this->curl_crsc, CURLOPT_AUTOREFERER, true );
        curl_setopt( $this->curl_crsc, CURLOPT_CONNECTTIMEOUT, 600 );
        #curl_setopt( $this->curl_crsc, CURLOPT_CONNECTTIMEOUT_MS, 180000 );
        curl_setopt( $this->curl_crsc, CURLOPT_TIMEOUT, 600 );
        curl_setopt( $this->curl_crsc, CURLOPT_MAXREDIRS, 10 );
        curl_setopt( $this->curl_crsc, CURLOPT_URL, $url);

        
        if($post)
        {
            curl_setopt ($this->curl_crsc, CURLOPT_POST,true);
            if(is_array($post))
            {
                curl_setopt ($this->curl_crsc, CURLOPT_POSTFIELDS,http_build_query($post));
            }else
            {
                curl_setopt ($this->curl_crsc, CURLOPT_POSTFIELDS,$post);
            }
            #dump( $post );
        }
        
        if(isset($arg['data_length']))
        {
            $this->data_length = $arg['data_length'];
            curl_setopt ($this->curl_crsc, CURLOPT_WRITEFUNCTION, array($this,'curl_test'));
            curl_exec($this->curl_crsc);
        }else
        {
            $this->curl_rest = curl_exec($this->curl_crsc);
        }

        $this->curl_info = curl_getinfo($this->curl_crsc);
        if(($error = curl_error($this->curl_crsc)))
        {
            $this->curl_error = $error;
            return false;
        }
        return $this->curl_rest;
    }
}