<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 22/8/18
 * Time: 5:35 PM
 */
?>
<style>
    .src-cont{background:#fff;font-family:'open sans';width:100%;float:left}

    .pop-crse {font-weight: 700;color: #333;margin-bottom: 4px;display: block;font-size: 12px;}
    ul.pop-crseList {width: 100%;float: left;padding-left: 20px;margin-bottom: 10px;box-sizing: border-box;}
    ul.pop-crseList li {color: #666;}
    .srcBtn-cl {width: 100%;float: left;margin: 6px 0 10px 0;}
    .srt-filSec{width:100%;float:left;background:#fff;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;font-family:'open sans';font-size:11px;display:table;border-bottom:1px solid #ccc}
    .srt-filSec a{width:50%;color:#666!important;text-decoration:none!important;display:table-cell;text-align:center;padding:10px;font-size:12px;font-weight:600}
    .srt-filSec a.first-child{border-right:1px solid #ccc}
    .search-sprite{background-image:url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/abroadSearch-sprite1.svg);display:inline-block}
    .abFil-icn{background-position:0 -20px;width:18px;height:16px;margin-left:5px;vertical-align:middle}
    .abSrt-icn{background-position:-24px -20px;width:28px;height:19px;margin-left:5px;vertical-align:middle}
    .cMrk-bg{background:rgba(0,0,0,.8);position:fixed;top:0;left:0;right:0;bottom:0;width:100%;height:100%;z-index:10}
    .cMrk{width:100%;float:left;z-index:100;position:absolute;top:0}
    p.select-text{font-size:16px;font-family:'open sans';color:#fff;z-index:1000;position:absolute;top:88px;left:10%;margin:0;line-height:23px}
    .study-sprite{background:url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/abroad-study-sprite1.png) no-repeat;position:relative;display:inline-block}
    .coach-arrw{background-position:0 -39px;width:39px;height:36px;position:absolute;top:-35px;left:7%}
    .ok-box{margin:0 auto;text-align:center;position:fixed;bottom:50px;display:block;left:0;right:0}
    .Ok-btn{background:#ccc;color:#333!important;padding:8px 20px;z-index:1000;font-family:'open sans';text-align:center;text-transform:uppercase;font-size:12px;text-decoration:none!important;font-weight:600;display:block;margin:0 10px;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}
    .rsPge-tit,h1.rsPge-tit{padding:10px;background:#eee;border-bottom:1px solid #ddd;color:#666;width:100%;float:left;line-height:19px}
    h1.rsPge-tit {font-size: 16px; font-weight: 600;color:#000;line-height: 25px;}

    .srcRs-con{background:#f8f8f8;padding:10px 5px;width:100%;float:left}
    .mb20{margin-bottom:20px;width:100%;float:left}
    .srcRs-lst{width:100%;float:left;background:#fff;border-bottom:1px solid #ccc;padding:10px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    .mb20 .srcRs-lst:first-child{border-bottom:0;padding:10px 10px 20px 10px}
    .srcRs-det{margin-bottom:8px}
    .rsCr-img,.rsCr-img img{width:142px;height:95px}
    .rsCr-img,.rsCr-img img{width:142px;height:95px}
    .rsCr-inf{margin-left:150px;padding-top:5px;font-size:11px;line-height:18px}
    .src-uni{margin-bottom:2px;line-height:16px}
    .src-uni strong a {color: #333;font-weight:bold;}
    .src-uni span{font-size:11px;color:#999;display:block}
    .srcCr-info{width:100%;float:left;font-size:12px;line-height:18px;color:#666}
    .cr-dur{font-size:11px;color:#333;display:block;margin-bottom:5px}
    ul.cr-dur,ul.cr-dur li{width:100%;float:left;margin-top:5px}
    ul.cr-dur li{margin:2px 0!important;font-size:12px;list-style:none;line-height:18px}
    ul.cr-dur li label{font-weight:500;float:left;width:60%}
    ul.cr-dur li span{float:right;width:40%;text-align:left}
    .compare-bro-sec{width:100%}
    a.crse-btn,a.crse-btn2{display:inline-block;background:#eee;font-size:12px;color:#333;padding:8px 6px;border:1px solid #ccc;width:49%;float:left;text-align:center}
    a.crse-btn2{float:right;background:var(--Primary-Orange);border:1px solid var(--Primary-Orange);color:#fff}
    .simCr-col{width:100%;float:left;height:calc(100px - 90px);border-top:1px solid #ccc;text-align:center;margin-top:0;line-height:0}
    .simTitle{padding:4px 10px;background:#fff;font-weight:600;color:#666;text-align:center}
    .srcRs-lst{width:100%;float:left;background:#fff;border-bottom:1px solid #ccc;padding:10px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    .brdrTop0{border-top:0}
    .srcRs-det{margin-bottom:8px}
    .srcRs-det ul li{width:100%;float:left;list-style:none;margin:5px 0}
    .srp-sim{width:100%;float:left;cursor:pointer}
    .minus-icon,.plus-icon{content:"+";font-size:22px;font-weight:600;color:#666;background:#eee;padding:2px 6px;display:inline-block;position:relative;top:3px;float:left;border:1px solid #ccc;height:25px;line-height:18px}
    .srp-courseTab{margin-left:35px;margin-top:3px;line-height:15px}
    .srcCr-name{font-size:14px;color:var(--Teal);font-weight:700}
    .rmcButtonDiv{position:relative;margin-top:10px}
    .srcCr-info .rate-change-button{margin-top:0}
    .src-tac{text-align:center}
    a.mrCr-btn{background:#eee;color:#333;font-size:12px;padding:5px 10px;border:1px solid #ccc;position:relative;top:-5px;text-align:center;font-weight:600}
</style>
