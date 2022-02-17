<style type="text/css">
:root{
    --Teal:#008489;
    --Dark-Teal:#003e41;
    --Mid-Teal:#007075;
    --Light-Teal:#00a5b5;
    --Vivid-Cyan:#0efbf3;
    --Charcoal-Black:#111111;
    --Ash-Grey:#333333;
    --Dark-Slate:#666666;
    --Light-Slate:#999999;
    --Silver:#cccccc;
    --Smoke:#D2D2D2;
    --Nickel:#E6E6E6;
    --White:#FFFFFF;
    --Grey-Ice:#F2F2F2;
    --Primary-Orange:#F37921;
    --Dark-Red:#A51D3D;
    --Hover-Orange:#db6d1d;
    --Hover-Teal:#00777b;
    --Hover-Red:#941a37;
}
a, a:active, a:hover, a:visited{color: var(--Teal);outline: 0; text-decoration: none;}
*{font-family: 'open sans';}
*,*:before,*:after{-moz-box-sizing:border-box; -webkit-box-sizing:border-box; box-sizing:border-box;}
html,body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,abbr,address,cite,code,del,dfn,em,img,ins,kbd,q,samp,small,strong,sub,sup,var,b,i,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,figcaption,figure,footer,header,hgroup,menu,nav,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;outline:0;vertical-align:baseline;background:transparent}
html{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;}
body{line-height:1; font-size:12px; color:#333;background:#f2f2f2; font-family:'Open Sans', sans-serif; height:100%; overflow-y: auto;}
#trnsprnt-ovrly{width: 100%;height: 100%;position: fixed;top: 0;bottom: 0;z-index: 10000;opacity: 0;}
audio,canvas,video{display:inline-block;}
button,input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;vertical-align:baseline;}
article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}
div[data-role="page"]{display: none;}
#wrapper{display: block;}
.caret{display:inline-block;width:0;height:0;vertical-align:middle;border-top:10px solid transparent; border-bottom:10px solid transparent; border-right:10px solid transparent;border-left:10px solid; color:#fff; position:relative}
.flLt{float:left} .flRt{float:right} .tac{text-align:center}
.sprite{background:url(<?php echo IMGURL_SECURE; ?>/public/mobileSA/images/mbl-sprt6.png) no-repeat; display:inline-block; position:relative; vertical-align:middle}
.src-headIcn{background:url(<?php echo IMGURL_SECURE; ?>/public/mobileSA/images/src-HomeIcn.svg) no-repeat; width:20px; height:20px; margin:0 7px;vertical-align:middle; top:-1px; float: right; top:10px;}
* html .clearfix {height: 1%;} .clearfix {display: block;} .clear{clear:both; width:100%; float:left}
.header{background:#fff; width:100%; display:table; position:relative; z-index:9; height:40px; border-bottom:1px solid #aaaaaa; border-top:none !important;}
.hem-menu, .back-col{background:transparent; border: 0 none; cursor: pointer; line-height: 0; margin: 0; padding: 5px 12px; position: relative; width:18px; display:table-cell; vertical-align:middle; text-align:center}
.hem-menu .icon-bar{display: block; height: 2px; margin: 3px 0 0; width:18px; background:#666666}
.header-shortlist{width:40px; display:table-cell; vertical-align:middle; position:relative; font-size:10px}
.right-menu{width:22px; display:table-cell; vertical-align:middle; text-align:center; padding:5px 12px; position:relative; background:#fff !important}
.right-menu span{background-position:-11px -36px !important; width:20px; height:18px;}
.logo, .header-title{display:table-cell; vertical-align:middle; text-align:center}
.logo{text-align: left;}
.logo span{background-position:0 0 !important; width:96px; height:30px; text-indent:-9999px; margin-top:4px}
.content-section{padding:10px}
.content-wrap{background:#fff; margin:12px 8px 15px}
.layer-wrap{border-bottom:1px solid #d6d6d6; background:#fff; padding-bottom:5px}
.content-inner{padding:8px}
.content-inner2{padding:10px 0px}
.layer-header, .layer-search{background:#fff; padding:0px; border-bottom:1px solid #aaaaaa; font-size:12px; font-weight:bold; width:100%; display:table}
.layer-header p, .layer-header div, .layer-search-box{display: table-cell; line-height: 1.58; vertical-align: middle;font-size: 16px;font-weight: 600;color:#333;}
.layer-search-box{padding:6px 10px 6px 0}
.search-icn{background-position:-32px -36px; width:17px; height:18px; top:2px}
.search-icn2{background-position:-108px -36px; width:12px; height:12px; position:absolute; top:50%; -webkit-transform:translateY(-50%); -moz-transform:translateY(-50%); transform:translateY(-50%); left:50%; margin-left:-6px}
.mob-icn{width:10px; height:14px; float:left; background-position:-122px -55px; top:2px}
.mob-mail-icn{background-position:-184px -213px; width:17px; height:11px;float: left; left:-5px;top:4px; }
.contact-details{color:#666}
.rt-arr{background-position:-23px -54px; width:4px; height:10px; position:absolute; right:5px; top:50%; -webkit-transform: translateY(-50%); -moz-transform: translateY(-50%); transform: translateY(-50%);}
.tabs{width:100%; display:table;}
.tabs ul{display:table-row}
.tabs ul li{display:table-cell; width:50%}
.tabs ul li:first-child {border-right:solid 2px #999999}
.tabs ul li:last-child {border-right:none !important;}
.tabs ul li a{padding:10px 0px; display:block; color:#333333; text-align:center; font-size:14px; background:#cccccc;}
.tabs ul li.active a{font-weight:bold; background:#fff}
.color0{color:black;}
@font-face{font-family:'Open Sans';src:url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.eot);src:url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.eot?#iefix) format('embedded-opentype'),url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.woff) format('woff'),url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.ttf) format('truetype'),url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.svg#OpenSans) format('svg');font-weight:400;font-style:normal;}

@font-face{font-family:'Open Sans';src:url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/OpenSans-SemiBold.eot);src:url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/OpenSans-SemiBold.eot?#iefix) format('embedded-opentype'),url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/OpenSans-SemiBold.woff) format('woff'),url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/OpenSans-SemiBold.ttf) format('truetype'),url(<?php echo IMGURL_SECURE; ?>/public/fonts/open-sans/OpenSans-SemiBold.svg#OpenSans) format('svg');font-weight:600;font-style:normal}
.right-menu{background: none repeat scroll 0 0 #fff !important;display: block; float: right; padding: 5px 10px; top: 6px; vertical-align: middle; width:42px;}
.star-icn{background-position:-68px -55px; width:14px; height:13px; top:2px;}
#shortlistHeaderCount{position: absolute;top: -5px;left: 19px;color: #fff;}
.search-sprite {background-image: url(<?php echo IMGURL_SECURE; ?>/public/mobileSA/images/abroadSearch-sprite1.svg);display: inline-block;}
.wrap-title {font-size: 14px;font-weight: 700;margin-bottom: 10px;}
.bro-icn{background-position:-121px -37px; width:17px; height:12px; vertical-align:middle; margin-right:3px}
.btn-row{margin:10px 0}
.btn{display:inline-block;padding:6px 7px;margin-bottom:0;font-size:14px;line-height:1.42857143;text-align:center;white-space:nowrap;vertical-align:middle;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-image:none;border:1px solid transparent; text-decoration:none; font-size:12px; font-weight:bold;}
.btn-default, a.btn-default, .btn-gray, a.btn-gray{background:var(--Primary-Orange); color:#fff;}
.btn-full{display:block; width:100%;}
a.btn-gray, .btn-gray{background:#f5f5f5; border:1px solid #aaaaaa; color:#323232; font-weight:bold}
a.btn-primary, .btn-primary{background:var(--Primary-Orange); border:0 none; color:#fff; font-weight:bold}
.btn-full{display:block; width:100%;}
.clearfix:after{visibility: hidden; display: block; content: " "; clear: both; height: 0;}
b,strong{font-weight:bold}em{font-style:italic;}fieldset,img{border:0}
.dyanamic-content ul{margin-left:15px;  list-style: disc;}
.dyanamic-content ul li{margin:0 0 8px 18px}
.dyanamic-content a{text-decoration: underline;}
nav ul{list-style:none}
.hide{display:none;}
/*BSB - Start*/
.bsb-btm{background: #fff; /*transition: all 600ms cubic-bezier(0.25, 0.46, 0.45, 0.94);*/position: fixed;bottom: 0; width: 100%;box-sizing: border-box; font-size: 14px; line-height: 17px; color: #333;box-shadow: -2px -2px 2px rgba(0, 0, 0, 0.15); z-index:999; display: none; font-family: 'Open Sans'}
a.knw-mre{text-align:center;cursor:pointer;display:inline-block;background:var(--Primary-Orange);border-radius:2px;height:28px;font-weight:600;line-height:28px;color:#fff;font-size:14px;width: 98px;}
.bsb-btm:before {content: "";display: block;height: 4px;background: -webkit-linear-gradient(left,rgba(19,78,84,1) 0,rgba(39,163,175,1) 50%,rgba(20,83,90,1) 100%); width: 100%;left: 0;top: 0;}
.bsb-btm a{text-decoration: none;display: inline-block}
.bsb-mob{width: 85%; padding: 8px 0 10px 0;margin: 0 auto; display: table;max-width: 428px;}
.bsb-mob p{width:62%;display: table-cell; vertical-align: middle; font-weight: 600;}
a.cross-ico{background: url("<?php echo IMGURL_SECURE; ?>/public/mobileSA/images/mbl-sprt6.png") no-repeat;width: 16px; height: 16px;position: absolute; top: 3px; left: 0;background-position: -177px -233px;}
.knw-div{width: 30%; text-align: right; display: table-cell; vertical-align: middle;}
/*BSB - End*/
/*top heading*/
._topHeadingCont img#beacon_img{position: absolute;}
._topHeading{font-size: 16px;font-weight: 600;line-height: 1.58;color:#333;}
._topHeadingCont{padding:10px 12px 0px 12px;}
.hide-header{display:none}
.cokkie-lyr{width:100%;padding:10px;background:rgba(0,0,0,.95);color:#fff;position:fixed;bottom:0;left:0;right:0;font-size:11px;font-family:'open sans',sans-serif;z-index:9999}
.cokkie-box{max-width:945px;margin:0 auto;display:table;line-height:15px}
.cokkie-box p,.cokkie-lyr .tar{display:table-cell;vertical-align:middle}
.cokkie-lyr a{color:var(--Vivid-Cyan);text-decoration:none}
a.cookAgr-btn{background:var(--Primary-Orange);font-size:13px;font-weight:600;border-radius:2px;text-align:center;text-decoration:none;color:#fff;display:inline-block;width:66px;height:30px;line-height:28px;margin-left:5px;font-family:'open sans',sans-serif}
.compare-bottom-sticky-layer{width:100%;background:#fff;position:fixed;bottom:0;color:#333}
.compare-bottom-sticky-layer table{border-top:1px solid #eee;width:100%;border-collapse:collapse}
.compare-bottom-sticky-layer table tr td{padding:8px;color:#333;border-right:1px solid #eee;width:50%}
.compare-bottom-sticky-layer table tr td.compare-full-list{padding:8px 8px 0!important}
.sticky-compare-col{font-size:10px;line-height:18px;position:relative}
.sticky-compare-col strong{font-size:11px}
.sub-clr{color:#666}
.remove-college-mark{display:block;color:#fff!important;-moz-border-radius:50%;-webkit-border-radius:50%;border-radius:50%;position:absolute;background:#666;top:0;right:0;width:18px;height:18px;text-align:center;line-height:18px;font-size:15px;font-weight:bold}
.mbl-compare-btn{color:#666!important;font-weight:bold;text-decoration:none;padding:10px;background:#ccc;text-align:center;display:inline-block;width:100%}
.mbl-compare-icn{background-position:-18px -233px;width:15px;height:15px;vertical-align:middle}

/*SA GNB start*/
@media screen and (max-width:1023px){
    .main-header {position: relative;}
    .main-header {font-size: 16px;}
    .logo,.header-title{display:table-cell;vertical-align:middle;text-align:center}
    #menu a.panel-anchor{padding:0;border-color:transparent}
    #menu{position:absolute;top:0;left:0}
    .search-tabContent {
        display: none;
    }
    .lgn-sgnDiv,.lggd-sgnDiv {
        display: none;
    }
    .abrd-hdr {
    background: #007075;
    display: inline-block;
    width: 100%;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.16), 0 0 0 1px rgba(0,0,0,.08);
    }
    #menu input[type="checkbox"], #menu input[type="radio"], #menu ul span.drop-icon {
    display: none;
}
    .src-fldDiv{display:block;float:right;padding:0px;height:44px}
    .logo-tip,.logo-icn,.src-icon,.user-icon:after,label.drop-icon:after,label.drop-icon.minus-icon:after,.sub-menu .drpdwn-Rgt label.drop-icon:after,.sub-menu .drpdwn-lft label.drop-icon:after,.sub-menu .drpdwn-Rgt label.drop-icon.minus-icon:after,.sub-menu .drpdwn-lft label.drop-icon.minus-icon:after,.shortlist-icon,#toggle-menu p.drop-icon:after{background:url('<?php echo IMGURL_SECURE; ?>/public/responsiveAssets/images/gnb_sprite.svg') no-repeat;position:relative}
    #toggle-menu{padding:12px 15px;position:relative;margin:10px}
    #toggle-menu,#menu a,#menu label.menuTab-div{padding:1em 1.5em}
    #toggle-menu p.drop-icon{margin:0;left:0}
    #toggle-menu .drop-icon,#menu li label.drop-icon{position:absolute;right:0;top:0}
    #toggle-menu p.drop-icon span{display:block;width:16px;height:2px;margin-bottom:2px;position:relative;background:#fff;border-radius:3px;z-index:1}
    #toggle-menu p.drop-icon:after {
        background-position: -239px -6px;
        content: '';
        width: 18px;
        height: 18px;
        display: inline-block;
        top: -2px;
        left: 2px;
    }
    #menu #toggle-menu span.menu-cls,#menu #toggle-menu p.menu-cls{padding:0;color:#fff;font-size:10px;text-transform:uppercase;background-color:transparent;border:0;box-shadow:none;left:-3px;top:18px;position:absolute;display:inline-block;font-weight:400}
    #menu li,#toggle-menu,#menu a,#menu label.menuTab-div{position:relative;display:block;color:#fff}
    #menu li,#toggle-menu,#menu .sub-menu{border-style:solid;border-color:rgba(0,0,0,.05)}
    #menu .main-menu{visibility:hidden}
    .logo {
        display: inline-block;
        text-align: left;
        line-height: 100%;
        bottom: 3px;
        left: 46px;
        position: relative;
        margin-left: 0px;
        border-left: 1px solid rgba(0,0,0,0.15);
        height: 44px;
    }
    .logo a{display:inline-block;height:44px;position: relative;}

    a.search-hdr-icon, a.user-hdr-icon{padding: 0 8px; height: 44px; line-height: 44px; display: inline-block;}
     a.user-hdr-icon{padding: 0 10px 0 5px;}
    .src-icon {
        background-position: -215px -4px;
        width: 20px;
        height: 20px;
        top: 4px;
        display: inline-block;
    }
    .user-icon {
        background: #359093;
        width: 26px;
        height: 26px;
        border: 1px solid #008489;
        border-radius: 50%;
        display: inline-block;
        top: 9px;
        position: relative;
    }
    .user-icon:after {
        content: '';
        background-position: -185px 0;
        width: 21px;
        height: 22px;
        display: inline-block;
        top: -9px;
        text-align: center;
        left: -1px;
    }
    .logo-icn {
        background-position: -2px 0px;
        width: 180px;
        height: 68px;
        display: inline-block;
        top: -10px;
        transform: scale(0.6);
        left: -34px;
        position: absolute;
        text-align: left;
            }
    .shortlist-icon{background-position:-208px -27px;width:20px;height:21px;top:2px;display:inline-block}
    .header-shortlist{float: left;font-size: 10px;margin-right: 5px;}
    .header-shortlist a {
        position: relative;
        height: 44px;
        line-height: 44px;
        display: inline-block;
    }
}
.hide-header{display:none}
#genericTopStky{width: 100%;position: fixed;top: 0px;height: auto;z-index:100}
</style>
