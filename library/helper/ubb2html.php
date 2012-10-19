<?php

class Helper_Ubb2Html
{

static function  run($sUBB)
{
    $sHtml=$sUBB;

    global $cnum,$arrcode,$bUbb2htmlFunctionInit;$cnum=0;$arrcode=array();

    if(!$bUbb2htmlFunctionInit){
    function saveCodeArea($match)
    {
        global $cnum,$arrcode;
        $cnum++;$arrcode[$cnum]=$match[0];
        return "[\tubbcodeplace_".$cnum."\t]";
    }}
    $sHtml=preg_replace_callback('/\[code\s*(?:=\s*((?:(?!")[\s\S])+?)(?:"[\s\S]*?)?)?\]([\s\S]*?)\[\/code\]/i','saveCodeArea',$sHtml);

    $sHtml=preg_replace("/&/",'&amp;',$sHtml);
    $sHtml=preg_replace("/</",'&lt;',$sHtml);
    $sHtml=preg_replace("/>/",'&gt;',$sHtml);
    $sHtml=preg_replace("/\r?\n/",'##_FIX_##',$sHtml);//<br />

    $sHtml=preg_replace("/\[(\/?)(b|u|i|s|sup|sub|del)\]/i",'<$1$2>',$sHtml);
    $sHtml=preg_replace('/\[color\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="color:$1;">',$sHtml);

    $sHtml=preg_replace('/\[vip\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*##_FIX_##/i','<span style="color:red;">$1</span><br />',$sHtml);
    $sHtml=preg_replace('/\[ing\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*##_FIX_##/i','<span style="color:green;">$1</span><br />',$sHtml);
    $sHtml=preg_replace('/\[end\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*##_FIX_##/i','<span style="color:#ccc;">$1</span><br />',$sHtml);
    $sHtml=preg_replace('/\[ccc\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*##_FIX_##/i','<span style="color:gray;"><del>$1</del></span><br />',$sHtml);

    $sHtml=str_replace("##_FIX_##",'<br />',$sHtml);//<br />

    if(!$bUbb2htmlFunctionInit){
    function getSizeName($match)
    {
        $arrSize=array('8pt','10pt','12pt','14pt','18pt','24pt','36pt');
        return '<span style="font-size:'.$arrSize[$match[1]-1].';">';
    }}
    $sHtml=preg_replace_callback("/\[size\s*=\s*(\d+?)\s*\]/i",'getSizeName',$sHtml);
    $sHtml=preg_replace('/\[font\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="font-family:$1;">',$sHtml);
    $sHtml=preg_replace('/\[back\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]/i','<span style="background-color:$1;">',$sHtml);
    $sHtml=preg_replace("/\[\/(color|size|font|back)\]/i",'</span>',$sHtml);

    for($i=0;$i<3;$i++)$sHtml=preg_replace('/\[align\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\](((?!\[align(?:\s+[^\]]+)?\])[\s\S])*?)\[\/align\]/','<p align="$1">$2</p>',$sHtml);
    $sHtml=preg_replace('/\[img\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/img\]/i','<img src="$1" alt="" />',$sHtml);

    $sHtml=preg_replace('/\[url\]\s*(((?!")[\s\S])*?)(?:"[\s\S]*?)?\s*\[\/url\]/i','<a href="$1">$1</a>',$sHtml);
    $sHtml=preg_replace('/\[url\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]*?)\s*\[\/url\]/i','<a href="$1">$2</a>',$sHtml);
    $sHtml=preg_replace('/\[email\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/email\]/i','<a href="mailto:$1">$1</a>',$sHtml);
    $sHtml=preg_replace('/\[email\s*=\s*([^\]"]+?)(?:"[^\]]*?)?\s*\]\s*([\s\S]+?)\s*\[\/email\]/i','<a href="mailto:$1">$2</a>',$sHtml);
    $sHtml=preg_replace("/\[quote\]([\s\S]*?)\[\/quote\]/i",'<blockquote>$1</blockquote>',$sHtml);
    if(!$bUbb2htmlFunctionInit){
    function getFlash($match)
    {
        $w=$match[1];$h=$match[2];$url=$match[3];
        if(!$w)$w=480;if(!$h)$h=400;
        return '<embed type="application/x-shockwave-flash" src="'.$url.'" wmode="opaque" quality="high" bgcolor="#ffffff" menu="false" play="true" loop="true" width="'.$w.'" height="'.$h.'" />';
    }}
    $sHtml=preg_replace_callback('/\[flash\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/flash\]/i','getFlash',$sHtml);
    if(!$bUbb2htmlFunctionInit){
    function getMedia($match)
    {
        $w=$match[1];$h=$match[2];$play=$match[3];$url=$match[4];
        if(!$w)$w=480;if(!$h)$h=400;
        return '<embed type="application/x-mplayer2" src="'.$url.'" enablecontextmenu="false" autostart="'.($play=='1'?'true':'false').'" width="'.$w.'" height="'.$h.'" />';
    }}
    $sHtml=preg_replace_callback('/\[media\s*(?:=\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*(\d+)\s*)?)?\]\s*(((?!")[\s\S])+?)(?:"[\s\S]*?)?\s*\[\/media\]/i','getMedia',$sHtml);
    if(!$bUbb2htmlFunctionInit){
    function getTable($match)
    {
        return '<table'.(isset($match[1])?' width="'.$match[1].'"':'').(isset($match[2])?' bgcolor="'.$match[2].'"':'').'>';
    }}
    $sHtml=preg_replace_callback('/\[table\s*(?:=(\d{1,4}%?)\s*(?:,\s*([^\]"]+)(?:"[^\]]*?)?)?)?\s*\]/i','getTable',$sHtml);
    if(!$bUbb2htmlFunctionInit){
    function getTR($match){return '<tr'.(isset($match[1])?' bgcolor="'.$match[1].'"':'').'>';}}
    $sHtml=preg_replace_callback('/\[tr\s*(?:=(\s*[^\]"]+))?(?:"[^\]]*?)?\s*\]/i','getTR',$sHtml);
    if(!$bUbb2htmlFunctionInit){
    function getTD($match){
        $col=isset($match[1])?$match[1]:0;$row=isset($match[2])?$match[2]:0;$w=isset($match[3])?$match[3]:null;
        return '<td'.($col>1?' colspan="'.$col.'"':'').($row>1?' rowspan="'.$row.'"':'').($w?' width="'.$w.'"':'').'>';
    }}
    $sHtml=preg_replace_callback("/\[td\s*(?:=\s*(\d{1,2})\s*,\s*(\d{1,2})\s*(?:,\s*(\d{1,4}%?))?)?\s*\]/i",'getTD',$sHtml);
    $sHtml=preg_replace("/\[\/(table|tr|td)\]/i",'</$1>',$sHtml);
    $sHtml=preg_replace("/\[\*\]((?:(?!\[\*\]|\[\/list\]|\[list\s*(?:=[^\]]+)?\])[\s\S])+)/i",'<li>$1</li>',$sHtml);
    if(!$bUbb2htmlFunctionInit){
    function getUL($match)
    {
        $str='<ul';
        if(isset($match[1]))$str.=' type="'.$match[1].'"';
        return $str.'>';
    }}
    $sHtml=preg_replace_callback('/\[list\s*(?:=\s*([^\]"]+))?(?:"[^\]]*?)?\s*\]/i','getUL',$sHtml);
    $sHtml=preg_replace("/\[\/list\]/i",'</ul>',$sHtml);

    for($i=1;$i<=$cnum;$i++)$sHtml=str_replace("[\tubbcodeplace_".$i."\t]", $arrcode[$i],$sHtml);

    if(!$bUbb2htmlFunctionInit){
    function fixText($match)
    {
        $text=$match[2];
        $text=preg_replace("/\t/",'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$text);
        $text=preg_replace("/ /",'&nbsp;',$text);
        return $match[1].$text;
    }}
    $sHtml=preg_replace_callback('/(^|<\/?\w+(?:\s+[^>]*?)?>)([^<$]+)/i','fixText',$sHtml);

    $bUbb2htmlFunctionInit=true;

    return $sHtml;
}
}