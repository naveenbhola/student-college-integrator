<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 1/6/18
 * Time: 5:33 PM
 */
?>
<style>
    #main-wrapper{width: 100%; background: #f9f9f9;}
    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        font-size: inherit;
    }
    .examBreadcrumb{background: #f9f9f9;}
    .content-wrap {padding: 0;background: #f9f9f9;}
    .sop-left-col{width:780px;float:left;font-family:'open sans',sans-serif;}
    .sop-right-col{width:380px;float:right;}
    .sop-sprite{background:url(<?php echo IMGURL_SECURE?>/public/images/sop-sprite2.png); display:inline-block;font-style:none; vertical-align:middle; position:relative}
    .newExam-content{font-size:14px;font-family:'open sans';color:var(--Charcoal-Black);}
    .newExam-content p{font-size:14px;color:#111;line-height:23px;margin-bottom:10px;}
    .newExam-content ol,.newExam-content ul{float:left; width:100%;padding-left:20px;}
    .newExam-content ol li,.newExam-content ul li{width:100%;float:left;margin-bottom:8px;list-style:disc;}
    .newExam-content p a, .newExam-content ul li a{text-decoration:underline;}
    .newExam-content ul li a.button-style{text-decoration: none;}
    .examRight-widget{background:#fff;border:1px solid #e5e5e5;width:100%;float:left;font-family:'open sans';}
    .bottomBrder{border-bottom:1px solid #e5e5e5;padding:5px;}
    .examRight-widget h2{line-height:8px;margin-bottom:15px;}
/*    .examRight-widget h2,.newExam-widget h2{border-bottom:2px solid #9cb9d2;margin-bottom:10px;}*/
    .examRight-widget .widget-head,.newExam-widget .widget-head{width:auto;color:#333;position:relative;top:10px;display:inline-block;}
    .examRight-widget .widget-head p{font-size:15px!important;background: #fff;}
    /*.blue-arrw{background-position:-102px -203px;width:12px;height:16px;margin:0 10px;}*/
    .examRelated-list,.examRelated-list ul{width:100%;float:left;}
    .examRelated-list{padding:15px;}
    .examRelated-list ul{margin:0;}
    .examRelated-list ul li{width:100%;float:left;margin-bottom:15px;background:#fff;font-family:'open sans';list-style:none;border-bottom:1px solid #eee;}
    .examRelated-list ul li.last{border-bottom: none;}
    .examRelated-list ul li .artcle-fig{width:320px;height:213px; margin:0 auto}
    .examRelated-list ul li .artcle-info{font-size:14px;padding:10px;}
    .examRelated-list ul li .sop-commnt-title{color:#666;}
    .sop-commnt-title{font-size:11px;color:#999;display:block;margin:5px 0 0;}
    .sop-commnt-title .gray-commnt-icon{background-position:-39px -53px; width:17px; height:14px;margin-right:4px;vertical-align: bottom;}
    .examRight-widget .widget-head p,.newExam-widget .widget-head p{background:#fbfcff;width:auto;font-size:18px;color:#333;}
    .sop-other-widget{background:#f3f3f3;border:1px solid #e5e5e5;margin-bottom:15px;width:100%;float:left;}
    .sop-other-widget-head{color:var(--Charcoal-Black);font-size:14px;border-bottom:2px solid var(--Teal);display:block;padding:15px;}
    .sop-other-widget-content{text-align:center;padding:10px;}
    .sop-dwnld-guide{background:var(--Primary-Orange);color:#fff!important;font-weight:700;font-size:14px;-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;text-decoration:none!important;display:inline-block;text-align:center;padding:8px 75px;}
    .newExam-content table{width:100%!important;border-collapse:collapse!important;font:normal 12px 'open sans'!important;border:1px solid #e3e3e3!important;margin-bottom:15px;}
    .newExam-content table tr td{border:1px solid #e3e3e3!important;font:normal 12px 'open sans'!important;padding:10px!important;}
    .newExam-content table tr td p{width:auto!important;margin:0;padding:0!important;}
    .newExam-content table tr td ol,.newExam-content table tr td ul{margin-left:0!important;font-family:'open sans';}
    .newExam-content table tr td ol li,.newExam-content table tr td ul li{margin-left:10px!important;font-family:'open sans';}
    .newExam-widget ul li{width:49%;list-style:none;border:1px solid #9cb9d2;margin:0 0 10px;}
    .examClg-sec{background:#9cb9d2;width:60px;height:80px;float:left;padding:8px;}
    .examClg-icon{background-position:-119px -203px;width:34px;height:26px;position:relative;top:16px;}
    .examClg-link{margin-left:60px;font-size:14px;padding:8px;}
    .newExam-widget .examClg-link a{padding-top:14px;display:block;font-size:13px;text-decoration: none;}
    .examClg-link span{font-size:12px;color:#333;}
    .newExam-content ol li{list-style: decimal;}
    .newExam-content ul li h3, .newExam-content ol li h3{font-size:16px; color:#303030; font-weight:600; margin-bottom:5px;}
    .newExam-content blockquote, blockquote{background:#f8f8f8; border:1px solid #eee; padding:10px 12px; color:#333; font-size:15px; line-height:22px; border-left:4px solid var(--Teal); margin:10px 0 15px; font-family:'open sans'; font-weight:600; width: 100%; float: left;}
    .newExam-content h1{font-size: 20px;line-height: 26px;}
    .newExam-content h3{ margin-bottom:5px;font-size: 15px; line-height: 21px; font-weight: 600;}
    .newExam-content h4{ font-size: 14px; line-height: 21px; font-weight: 600;}
    .newExam-content h5{ font-size: 13px; line-height: 19px; font-weight: 600;}
    .newExam-content h6{ font-size: 12px; line-height: 18px; font-weight: 600;}
    .newExam-content img{vertical-align: middle; margin:5px 0;}
    .newExam-widget,.newExam-widget ul{width:100%;float:left;margin:10px 0;}
    .newExam-widget{border:1px solid #cecece; border-radius: 2px;padding: 15px;background: #fbfcff;}
    .newExam-content h2{font-size:16px;font-weight:600;color:#111;margin-bottom:5px;}
    ul.examArtcle-list{width:100%;float:left;padding:0;}
    ul.examArtcle-list li{width:100%;float:left;list-style:none;border:none!important;margin-bottom:15px;}
    .examArtcle-col{width:49%;display:table;}
    .newExam-widget .widget-head{top:0px;}
    .examArtcle-img img{width:75px;height:50px;float:left;margin:5px 0;}
    .examArtcle-img{display:table-cell;height:50px;vertical-align:middle;width:75px;}
    .examArtcle-info{font-size:14px;font-weight:600;display:table-cell;vertical-align:middle;}
    .form-label {
        width: 180px;
        text-align: right;
        float: left;
        display: block;
        padding-top: 12px;
    }
    td, td img {
        vertical-align: top;
    }
.exm-contnt, .exam-content-div{width: 1180px;margin: 0 auto;font-family: 'open sans';color: #000;}
.exam-content-div{margin-top: 20px;}
/*.newExam-heading {width: 100%;margin-bottom: 8px;font-size: 22px;font-family: 'open sans';font-weight: 600;display: inline-block;background: #fff;padding: 15px;color: #000;box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24);}*/
.newExam-heading {width: 100%;margin-bottom: 8px;font-size: 20px;font-family: 'open sans';font-weight: 600;display: inline-block;background: #fff;padding: 15px;color: #111;box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24);line-height:26px;}
.headNav-wrap{white-space:nowrap;overflow: hidden;}
ul.exam-headNav{width:100%; border-bottom: 1px solid #e0e0e0;min-width: 1180px;font-family: 'open sans';padding: 15px 0 0 0;margin: 0px 0 0px;}
ul.exam-headNav:after, ul.exam-headNav:before{content: ''; display: table;}
ul.exam-headNav>li{display: inline-block;  position: relative; margin:0 10px 9px 0;}
/*ul.exam-headNav>li>a{font-size: 16px; color: #000;text-decoration: none;padding: 0 12px 5px;outline: none;}*/
ul.exam-headNav>li>a{font-size: 16px; color: #111;text-decoration: none;padding: 0 12px 5px;outline: none;}
ul.exam-headNav>li>a.active{color:var(--Teal); border-bottom: 5px solid var(--Teal);}
section.fixd-menu {
    position: fixed;
    top: 0;
    background: #fff;
    z-index: 99;
    width: 100%;
    box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24);
    /*transition: all 0.3s ease-in;*/
}
section.fixd-menu h1.newExam-heading{box-shadow: none;border: none;margin-bottom: 0;padding:15px 15px 12px 15px}
section.fixd-menu ul.exam-headNav{padding: 0;border: none;}
.exmContentWrap, .commentSectionWrap{box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24); padding: 14px 16px;margin-bottom: 15px;background: #fff;}
</style>
