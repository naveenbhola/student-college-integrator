@font-face {
    font-family: 'Open Sans';
    src: url('//images.shiksha.ws/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.eot');
    src: url('//images.shiksha.ws/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.eot?#iefix') format('embedded-opentype'), url('//images.shiksha.ws/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.woff') format('woff'), url('//images.shiksha.ws/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.ttf') format('truetype'), url('//images.shiksha.ws/public/fonts/open-sans/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.svg#OpenSans') format('svg');
    font-weight: 400;
    font-style: normal;
}

*, .bdl ._container, .bdl .tbSec>ul>li, .bdl .tbc .cfl li, .bdl .tbc .others li {
    box-sizing: border-box;
     font-family: 'Open Sans', Arial, sans-serif;
}


.q-ask,
.q-srch {
    padding: 5px 7px
}

.header {
    background: #007075;
    width: 100%;
    display: table;
    position: relative;
    z-index: 9;
    height: 40px;
    line-height: 1;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 0 1px rgba(0, 0, 0, .08)
}
.header-title,
.logo {
    display: table-cell;
    vertical-align: middle;
    text-align: left
}
.back-col,
.hem-menu {
    background: 0 0;
    border: 0;
    cursor: pointer;
    line-height: 0;
    margin: 0;
    padding: 5px 12px;
    position: relative;
    width: 20px;
    display: table-cell;
    vertical-align: middle;
    text-align: center
}
.back-col, .header-title, .hem-menu, .logo, .q-ask, .right-menu {
    display: table-cell;
    vertical-align: middle;
}
.q-ask,
.q-srch,
.right-menu {
    width: 19px;
    display: table-cell;
    vertical-align: middle;
    position: relative;
    background: transparent !important;
}

.breadcrumb2 {
    padding: 12px 12px 0px;
}
.breadcrumb2 > span {
    font-size: 13px;
    line-height: 21px;
}

.notification,
.q-ask,
.q-srch,
.right-menu {
    text-align: center
}

.q-srch {
    background: #fff
}

.q-ask {
    background: #007075
}

.right-menu {
    padding: 8px 17px 5px 12px;
    background: #007075!important
}

.back-col .back-ico,
.hem-menu>span.icon-bar,
.logo>span.msprite,
.q-ask>span,
.right-menu>span,
.short-lst.q-srch>span.search,
.short-lst.q-srch>span.social-share-icon
 {
    background: url('https://images.shiksha.ws/pwa/public/images/pwa-header-mobile-v3.svg') -9px -9px no-repeat
}

.msprite , .rotate-ico{
    background: url(//<?=IMGURL?>/public/mobile5/images/mobile-sprite.png) no-repeat;
    display: inline-block;
    position: relative;
}

.hem-menu>span.icon-bar {
    display: block;
    background-position: -103px -5px;
    width: 20px;
    height: 20px
}

.q-ask span,
.short-lst.q-srch span {
    vertical-align: middle;
    display: inline-block
}

.short-lst.q-srch>span.search {
    background-position: -183px -6px;
    width: 24px;
    height: 24px
}
.short-lst.q-srch>span.social-share-icon {
    background-position: -46px -65px;
    width: 24px;
    height: 24px
}

.logo span {
    width: 88px;
    height: 20px;
    text-indent: -9999px
}

.short-lst span {
    line-height: 21px;
    font-size: 10px;
    color: #fff
}

.q-ask>span {
    background-position: -155px -8px;
    width: 24px;
    height: 20px;
}

.right-menu>span {
    background-position: -129px -8px
}

.right-menu span {
    width: 20px;
    height: 20px
}

.notification {
    background-color: #f9b34e;
    width: 23px;
    height: 18px;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    position: absolute;
    top: 5px;
    right: 4px;
    color: #fff;
    font-size: 10px;
    line-height: 15px;
    cursor: pointer;
    border: 1px solid #fff;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box
}

.compare-colg-slist ul li,
ul.list-items li {
    border-bottom: 1px solid #ccc
}

#myrightpanel {
    position: fixed;
    height: 100%;
    width: 17em;
    background: #e5e5da;
    top: 0;
    bottom: 0;
    overflow-y: scroll;
    -webkit-overflow-scrolling: touch;
    right: -310px;
    -webkit-box-shadow: -5px 0 5px rgba(0, 0, 0, .15);
    -moz-box-shadow: -5px 0 5px rgba(0, 0, 0, .15);
    box-shadow: -5px 0 5px rgba(0, 0, 0, .15)
}

.layer-shade {
    background: #fff
}

.clearfix {
    display: block
}

ul.added-items {
    background: 0 0
}

ul.list-items li {
    list-style: none
}

ul.list-items.added-items li a  {
    color: #006fa2;
    font-weight: 700
}

ul.list-items li a,
ul.list-items li p {
    text-decoration: none;
    color: #000;
    width: 100%;
    font-size: 13px;
    padding: 12px 10px;
    display: block;
    box-sizing: border-box
}

.compare-colg-slist {
    display: block;
    background: 0 0
}

.compare-colg-slist ul {
    list-style: none;
    margin: 0;
    padding: 0;
    box-shadow: 5px 0 5px rgba(0, 0, 0, .15) inset;
    background: #E5E5DA
}

.compare-colg-slist ul li:last-child {
    border-bottom: 0
}

.compare-colg-slist ul li a {
    font-size: 12px;
    color: #666;
    display: block;
    text-decoration: none;
    cursor: default;
    font-weight: 500;
    text-align: left;
    padding: 17px 21px;
    line-height: 18px
}

.cls-clg {
    float: right;
    font-size: 20px;
    font-weight: 400;
    color: #999;
    font-family: Open Sans
}

.compare-colg-slist ul li a .locality {
    display: block;
    color: #828282;
    font-weight: 400;
    margin-top: 6px;
    font-size: 12px
}

i.compare-locality {
    background-position: -46px -199px;
    width: 14px;
    height: 17px;
    left: 0;
    top: 2px;
    margin-right: 4px
}

button,
input[type=button],
input[type=reset],
input[type=submit] {
    cursor: pointer;
    -webkit-appearance: button
}

.compare-btn {
    background-color: #008489;
    width: 100%;
    display: inline-block;
    line-height: normal;
    color: #fff!important;
    outline: 0;
    border: 1px solid #008489;
    padding: 10px 7px;
    text-transform: uppercase;
    font-weight: 600;
    -webkit-appearance: none
}

.clearfix:after {
    visibility: hidden;
    display: block;
    font-size: 0;
    content: " ";
    clear: both;
    height: 0
}

.ui-footer-fixed,
.ui-header-fixed {
    left: 0;
    right: 0;
    width: 100%;
    position: fixed;
    z-index: 1000;
}

.ui-header-fixed {
    top: -1px;
    padding-top: 1px;
}

/***body css start***/
body.ui-mobile-viewport, div.ui-mobile-viewport {
    overflow-x: hidden;
}

.ui-mobile body {
    height: 99.9%;
}

.ui-mobile-viewport {
    margin: 0;
    overflow-x: visible;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: none;
    -webkit-tap-highlight-color: transparent;
}


.ui-mobile .ui-page-active {
    display: block;
    overflow: visible;
    overflow-x: hidden;
}
.ui-mobile [data-role=dialog], .ui-mobile [data-role=page], .ui-page {
    top: 0;
    left: 0;
    width: 100%;
    min-height: 100%;
    position: absolute;
    border: 0;
}
.ui-page {
    outline: 0;
}
.ui-mobile fieldset, .ui-page {
    padding: 0;
    margin: 0;
}
@media screen and (orientation: portrait){
    .ui-mobile .ui-page {
        min-height: 420px;
    }
}
@media screen and (orientation: landscape){
    .ui-mobile .ui-page {
        min-height: 300px;
    }
}
@media only screen and (orientation: landscape){
 #mypanel{
    width:19em;
}
.menuwrapper{
    max-width: 19em;
}
}

nav#mypanel{
    visibility:hidden;
}

.drawermenu-right i{ font-style: normal;}
/**body css end ****/
.dfp-add > div{margin: 12px auto;}
.dfp-add > div iframe{display: block;margin: 0 auto;}
.ht-dfp *[id^="google_ads_iframe"]{width:100%!important;}
.dfp-wraper.ht-dfp ~ .dfp-wraper.ht-dfp{margin-top:-18px;}
.dfp-wraper{width: 100%;}
div.ht-dfp[id^="div-gpt-ad"]{width: 100% !important;margin: 0 auto!important;}
div.ht-dfp[id^="div-gpt-ad"] div iframe{width: 100% !important;margin:0 auto!important;}

/*social sharing widget css*/
.shadow{box-shadow:0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);margin:12px 0px;}
.table{display:table;width:100%;}
.table-cell{display:table-cell;text-align:center;vertical-align:middle;}
.table-cell > *{display:inline-block;vertical-align:middle}
.social-share-widget{background:#fff;}
.social-share-widget-content{padding:8px 0px;font-size: 14px;color: #333;}
.social-share-widget-content .txtmsg{margin-right:5px;}
.border-top .social-share-widget-content{border-top:1px solid #dfdfdf;}
.border-bottom .social-share-widget-content{border-bottom:1px solid #dfdfdf;}
.social-share-widget .at-resp-share-element.at-mobile .at-share-btn{margin-bottom:0px;}
.space-around{margin:0px auto;width:calc(100% - 33px);}
/*feedback widget css*/
.opeclayer{position:fixed;left:0;right:0;top:0;bottom:0;background:rgba(0,0,0,0.4);z-index:2000}.feedbackbox{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:calc(100% - 20px);max-width:450px;}.form-container{background:#fff;box-shadow:0 1px 6px 0 rgba(32,33,36,0.28);position:relative;text-align:center}.form-container .cross{position:absolute;right:0;top:0;color:#fff;font-size:30px;cursor:pointer;width:45px;    height: 45px;line-height: 45px;text-align:center}.form-head{padding:10px;background:#008489;color:#fff;font-size:16px;text-align:left;height:45px;line-height:24px;}.form-container-inner{padding:10px;line-height: 21px;
    font-size: 14px;}.rating-icon-container{display:flex;align-items:center;justify-content:center}.aligned{display:flex;align-items:center;justify-content:center}.aligned .rating-icon-container{margin-left:5px}.rating-container h2{text-align:center}.rating-icon{display:inline-block;width:28px;height:28px;cursor:pointer;margin:0 3px;background:transparent url(/pwa/public/images/star.svg) no-repeat 0 0}.rating-icon.fill{background-position:0 0}.rating-icon.blank{background-position:-33px 0}.options-box{padding:8px 0 5px;border-top:1px solid #d2d2d2;}.information-section{text-align:left}.inline-list{display:block;overflow: hidden;
    -webkit-transition: all 0.5s ease-in-out;
      -moz-transition: all 0.5s ease-in-out;
      -ms-transition: all 0.5s ease-in-out;
      -o-transition: all 0.5s ease-in-out;
      transition: all 0.5s ease-in-out;}.inline-list li{display:inline-block;margin:0 8px 8px 0}.capsule{border:1px solid #999;border-radius:4px;padding:2px 8px 4px;background:#fff;color:#000;display:block}.capsule-selection{display:none}.capsule-selection:checked + .capsule{border:1px solid #008489;background:#008489;color:#fff}.feedback-text{white-space: nowrap;text-align:center;padding:5px 0 10px}.field-head-text{padding:0 0 8px}.suggetion-section{text-align:left}.suggetion-box{padding:8px 0 0;border-top:1px solid #d2d2d2}.inputbox{border:1px solid #979797;padding:10px;width:100%;height:50px;border-radius:2px;box-sizing:border-box}.btn-container{text-align:center;padding:12px 0 8px}.form-container-inner .rating-container{margin-bottom: 8px;}#topicContainer > .rating-container{padding: 10px 0px 16px;}.rating-icon-container *{line-height: 0;/*to remove extra spacing*/}.user-action-msg-box{display: table;margin:16px 0px;}
.user-action-msg-box .child-box{display: table-cell; vertical-align: middle;}
.user-action-msg-box .msg-icon{padding-right: 10px;}
/*feedback layer - end*/
.inline-list.shortHeight{height: 38px;}
.inline-list.fullHeight{height: 76px;}
@media only screen and (min-device-width: 320px) and (max-device-width: 568px) 
and (-webkit-device-pixel-ratio: 2) and (device-aspect-ratio: 40/71){
    .inline-list.shortHeight{height: 76px;}
    .inline-list.fullHeight{height: height: 114px;;}
}
/*feedback layer - end*/
