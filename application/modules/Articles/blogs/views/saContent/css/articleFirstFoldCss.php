<style>
#main-wrapper{width: 100%;}
.content-wrap{padding:0;background: #f9f9f9;}
.articleGuide-content{width: 1180px;margin: 0px auto 0;font-family: 'open sans';color: #000;}
body{font: normal 100% 'open sans', sans-serif;background: #f1f1f1;color: #000;}
.breadcrumb-arr{font-size:16px; color:#b8b7b7; margin:2px 2px 2px 4px; position:relative; top:1px}
#article-header{margin:0px 0 15px 0; padding-left:0px;border-bottom:1px solid #ccc;}
#article-header h1{font:normal 20px 'open sans', sans-serif; color:#4d4d4d; margin-bottom:5px;line-height: 26px;}
.commnt-sec{margin:7px 0 0 7px;}
.commnt-icon{background-position:0 0; width:23px; height:17px;}
.article-sprite{background:url(<?php echo IMGURL_SECURE?>/public/images/guide-article-sprite2.png); display:inline-block;font-style:none; vertical-align:middle; position:relative}
.author-details{color:#333; font-size:12px; margin:5px 0 15px;}
.author-details span{color:#999;}
#left-col{width:780px; float:left;}
.artGuide-cont, .commentSectionWrap{box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24); padding: 14px 16px; margin-bottom: 15px; background: #fff;}
.article-content,.dyanamic-content{line-height:25px;color:#000000; font-size:14px; clear:both;}
.article-widget{margin-bottom:20px; color:#666;}
.article-widget h5{font:bold 18px 'open sans', sans-serif; color:#333; margin-bottom:6px;}
.clearwidth{width:100%; float:left}.flLt{float:left}.flRt{float:right}.pipe{color:#aaaaaa; margin:0 4px}
.article-content p,.dyanamic-content p{margin-bottom:10px; word-wrap: break-word;}
.article-content p{font:normal 14px 'open sans', sans-serif; line-height:24px;}
.article-content img,.dyanamic-content img{border:0 none !important; margin:8px !important}
.article-content ul,.dyanamic-content ul, .dyanamic-content ol,.article-content ol{width:100%; float:left;margin:0; padding:0}
.article-content ul li,.dyanamic-content ul li,.article-content ol li,.dyanamic-content ol li{margin-bottom:8px;margin-left:20px; clear:both;list-style: disc;}
#right-col{width:380px; float:right;}
.artcl-right-wdgt{margin:0 0 15px 0; position:relative;background:#fff;}
.widget-heading{background:#eee; padding:10px;}
.widget-heading h2{margin:4px 0 0 27px; font-size:12px}
.widget-heading span{color:#999; font-size:9px; font-weight:normal; float:right; margin:4px 0 0}
.guide-artcl-icon{background-position:0 -41px; width:18px; height:21px; float:left}
.widget-list{padding:10px;}
.widget-list ul li{border-bottom:1px solid #eee; padding-bottom:11px; margin-bottom:8px; float:left; width:100%}
.widget-list ul li.last{border-bottom:none; margin-bottom:0;}
.gray-commnt-icon{background-position:0 -19px; width:17px; height:13px; margin-right:4px; vertical-align:bottom;}
.process-box{border: 1px solid #EAEAEA;  margin: 0 0 15px; position: relative;}
.process-box .details{border-top:1px solid #eee; font-size: 12px; padding: 8px;}
.process-box h3{font:normal 14px 'open sans', sans-serif; color:#000; margin:10px}
.guide-menu{position:absolute; left:2px; top:254px;}
.guide-btn{background:#4573b1; padding:4px 8px 0; font-weight:bold; border:3px solid #003676;color:#fff !important; font-size:13px; text-decoration:none !important; border-radius:2px; position:relative; display:block; height:70px}
.guide-menu-icon{background-position: -28px 0px; width: 14px; height: 14px; top: 7px; vertical-align: top !important; display: block; left: 13px; position: absolute;}
.guide-arrow{background-position:-33px -117px; width:17px; height:16px; position:absolute; left:24%; bottom:-16px; z-index:2;}
.guide-btn span{vertical-align: top; margin-left: 5px;}
.guide-info{color: rgb(121, 119, 119); font-size: 14px; word-wrap: break-word; margin: 20px 0px 0px 10px;display:none;}
.apl-contnt, .apply-content-div {width: 1180px;margin: 0 auto;font-family: 'open sans';color: #000;}
.apl-contnt{margin-bottom:20px;}
.newApply-heading {width: 100%;margin-bottom: 8px;font-size: 22px;font-family: 'open sans';font-weight: 600;display: inline-block;background: #fff;padding: 15px;color: #000;box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24);}
.headNav-wrap {white-space: nowrap;overflow: hidden;position:relative;}
ul.apply-headNav {width: 100%;border-bottom: 1px solid #e0e0e0;min-width:1180px;font-family: 'open sans';padding: 15px 0 0 0;margin: 0px 0 0px;}
ul.apply-headNav:after, ul.apply-headNav:before {content: '';display: table;}
ul.apply-headNav>li {display: inline-block;position: relative;padding: 0 10px 0px 0;}
ul.apply-headNav>li:first-child{padding-left: 0;}
ul.apply-headNav>li>a {font-size: 16px;color: #000;text-decoration: none;padding: 0 12px 10px 0;outline: none;display:block;}

ul.apply-headNav>li>a.active {color: var(--Teal);border-bottom: 5px solid var(--Teal);}

.nav__back, .nav__nxt {position: absolute;right: 0;top: 0;width: 55px;height: 47px;z-index: 1;background: linear-gradient(to right,rgba(249,249,249,0) 0,rgba(249,249,249,1) 73%);background: -webkit-linear-gradient(to right,rgba(249,249,249,0) 0,rgba(249,249,249,1) 73%);background: -moz-linear-gradient(to right,rgba(249,249,249,0) 0,rgba(249,249,249,1) 73%);cursor: pointer;}
.nav__back {left: 0;right: auto;background: linear-gradient(to left,rgba(249,249,249,0) 0,rgba(249,249,249,1) 73%);background: -webkit-linear-gradient(to left,rgba(249,249,249,0) 0,rgba(249,249,249,1) 73%);background: -moz-linear-gradient(to left,rgba(249,249,249,0) 0,rgba(249,249,249,1) 73%);}
.nav__back i.arrow.left, .nav__nxt i.arrow.right{border: solid var(--Teal);border-width: 0 2px 2px 0;display: inline-block;padding: 4px;transform: rotate(-45deg);-webkit-transform: rotate(-45deg);top: 20px;position: relative;right: -30px;}
.nav__back i.arrow.left{left: 11px;right: auto;transform: rotate(135deg);}
section.fixd-menu {position: fixed;top: 0;background: #fff;z-index: 99;width: 100%;box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24);/*transition: all 0.3s ease-in;*/}
section.fixd-menu .apl-contnt{margin-bottom:0;}
section.fixd-menu h1.newApply-heading{box-shadow: none;border: none;margin-bottom: 0;padding:15px 15px 12px 15px}
section.fixd-menu ul.apply-headNav{padding: 0;border: none;}
.exmContentWrap, .commentSectionWrap{box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24); padding: 14px 16px;margin-bottom: 15px;background: #fff;}
section.fixd-menu .nav__back,section.fixd-menu .nav__nxt{height:31px;background:linear-gradient(to left,rgba(255,255,255,0) 0,rgba(255,255,255,1) 73%)}
section.fixd-menu .nav__nxt{background:linear-gradient(to right,rgba(255,255,255,0) 0,rgba(255,255,255,1) 73%)}
section.fixd-menu .nav__back i.arrow.left,section.fixd-menu .nav__nxt i.arrow.right{top:7px;}
</style>
