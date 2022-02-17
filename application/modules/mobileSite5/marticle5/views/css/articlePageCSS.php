   :root{font-size: 16px;}
    h1,h2,h3,h4,h5, h6, 
   .h1,.h2,.h3,.h4,.h5, .h6{color: #000;font-weight: 600;margin: 6px 0px 4px;} 
   h1,.h1{font-size: 1.25rem;line-height: 1.625rem;} 
   h2,.h2{font-size: 1rem;line-height: 1.375rem;} 
   h3,.h3{font-size: 0.9375rem;line-height: 1.3125rem;} 
   h4,.h4{font-size: 0.875rem;line-height: 1.3125rem;} 
   h5,.h5{font-size: 0.8125rem;line-height: 1.1875rem;;} 
   h6,.h6{font-size: 0.75rem;line-height: 1.125rem;} 


a,hr{
    padding:0
}
a,button,input,nav li,nav ul,select,textarea{
    margin:0
}
b,mark,strong,th{
    font-weight:700
}
table,textarea{
    overflow:auto;
    display:block
}
td,td img,textarea{
    vertical-align:top
}
.clearfix,article,aside,details,figcaption,figure,footer,header,hgroup,hr,menu,nav,section,table,textarea{
    display:block
}
.article-content,.slide-text,pre{
    word-wrap:break-word
}
.clearfix:after,.dots__section:after,.main-divs:after,.slide-col-ul:after{
    clear:both
}
@charset "UTF-8";
abbr,address,article,aside,audio,b,blockquote,body,canvas,caption,cite,code,dd,del,details,dfn,div,dl,dt,em,fieldset,figcaption,figure,footer,form,header,hgroup,html,i,iframe,img,ins,kbdf,label,legend,li,mark,menu,nav,object,ol,p,pre,q,samp,section,small,span,strong,sub,summary,sup,time,ul,var,video{
    background:0 0;
    border:0;
    margin:0;
    outline:0;
    padding:0;
    vertical-align:baseline
}
ins,mark{
    background-color:#ff9;
    color:#000
}
*{font-family: arial,sans-serif!important;}
body{
    font-size:100%;
    background:#efefee;
    color:#000;
    -webkit-text-size-adjust:none
}
html{
    overflow-y:scroll;
    -webkit-text-size-adjust:100%;
    -ms-text-size-adjust:100%
}
footer ul,nav ul,ol,ul{
    list-style:none
}
blockquote,q{
    quotes:none
}
blockquote:after,blockquote:before,q:after,q:before{
    content:'';
    content:none
}
a{
    background:0 0;
    vertical-align:baseline
}
ins{
    text-decoration:none
}
mark{
    font-style:italic
}
del{
    text-decoration:line-through
}
abbr[title],dfn[title]{
    border-bottom:1px dotted;
    cursor:help
}
table{
    border-collapse:collapse;
    border-spacing:0;
    font-family:inherit;
    width:100%!important
}
hr{
    border:0;
    border-top:1px solid #ccc;
    height:1px;
    margin:1em 0
}
input,select{
    vertical-align:middle
}

a,a:active,a:hover,a:visited{
    outline:0;
    text-decoration:none;
    color:#4285f4
}
:focus,a:focus,input:focus,textarea:focus{
    outline:0
}
ol{
    list-style-type:decimal
}
small{
    font-size:85%
}
sub{
    font-size:smaller;
    vertical-align:sub
}
sup{
    font-size:.9em;
    vertical-align:text-top
}
pre{
    padding:15px;
    white-space:pre;
    white-space:pre-line;
    white-space:pre-wrap
}
button,input[type=button],input[type=reset],input[type=submit]{
    cursor:pointer;
    -webkit-appearance:button
}
label{
    cursor:pointer
}
button,input{
    line-height:normal
}
[hidden]{
    display:none
}
button[disabled],input[disabled]{
    cursor:default
}
.clearfix:after{
    visibility:hidden;
    display:block;
    font-size:0;
    content:" ";
    height:0
}
* html .clearfix{
    height:1%
}
textarea{
    resize:none;
    border:1px solid #ddd;
    padding:.5em;
    -moz-border-radius:12px;
    -webkit-border-radius:12px;
    border-radius:12px;
    -moz-box-shadow:0 3px 4px rgba(61,61,61,.2) inset;
    -webkit-box-shadow:0 3px 4px rgba(61,61,61,.2) inset;
    box-shadow:0 3px 4px rgba(61,61,61,.2) inset;
    width:90%;
    font:400 1em inherit;
    color:#656565;
    -moz-background-clip:padding;
    -webkit-background-clip:padding;
    background-clip:padding-box
}
.head-group{
    background:#fff;
    position:static;
    z-index:9;
    display:table;
    width:100%;
    height:50px;
    position: relative;
    z-index: 1
}
.head-group>strong{
    text-align:left;
    display:table-cell;
    padding-left:12px;
    padding-right:98px;
    text-shadow:0 1px 0 rgba(255,255,255,1);
    vertical-align:middle
}
.head-group aside{
    padding:5px 5px 3px
}
.head-group h1,.head-group h3,.title-text{
    font-weight:600;
    padding: 12px 12px 0px;
}
.left-align,.title-text{
    text-align:left!important
}
.left-align{
    margin-left:40px
}
.title-text{
    margin:10px 0 4px 45px;
    display:block
}
.ana-btns,.article-readmore,.background,.carausel__bullets,.content-wrap2 h3.signup-h3,.qna-icon,.txt-cntr{
    text-align:center
}
.head-group h1 p,.head-group h3 p{
    color:#a07f13;
    font-size:.9em;
    margin-top:3px
}
.content-wrap{
    background:#fff;
    margin-bottom:10px;
    font-size:90%
}
.content-wrap,.content-wrap2{
    background-color:rgba(255,255,255,.4);
    margin:0 .4em .6em;
    border-top:0 none;
}
.content-wrap2{
    background:#fff;
    border-radius:0;
    margin:0;
    position:relative
}
/*Global Pwa Icon*/
.icons{background:url(https://images.shiksha.ws/pwa/public/images/pwa-header-mobile-v3.svg) no-repeat}

/*bread crumb css*/
.ic_brdcrm{display: inline-block;width: 18px;height: 16px;}
.homeType1{background-position: -396px -27px;}
.homeType2{background-position: -377px -27px}

 <?php if($_REQUEST['test'] != '1234') { ?>
    /*.articleDetails{height:550px;overflow:hidden} | disabling it for now*/
    .articleDetails{height:auto;}
<?php } ?>
.article-content{padding:12px;color:#000;line-height:21px;font-size: 14px;}.photo-widget p,.photo-widget-full p{width:100%!important;background:#ddd;padding:5px}.article-content p{margin:4px 0px 10px;font-size: 14px;line-height: 21px;}.photo-widget-full{width:100%!important}.photo-widget-full .figure{width:100%!important;float:left}.photo-widget-full .figure img{width:100%!important}.photo-widget-full p{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.photo-widget-full p strong{display:block!important;margin-bottom:5px!important}.photo-widget{width:100%!important}.photo-widget .figure{width:100%!important;float:left}.photo-widget p{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.article-content ol li{margin:0 0 5px 18px}.article-content ul{list-style:disc;margin-bottom:15px}.most-viewd ul,.qna-wrap,.slider-col-tab>ul.slide-col-ul{list-style:none}.article-content ul li{margin:0 0 5px 16px}.photo-widget p strong{display:block!important;margin-bottom:5px!important}.inf-li>li,.inf-txts>strong,a.nw-btn{display:inline-block}.slide-content{padding:0px 12px;background: #fff;}.article-keywords{color:#000}.article-keywords p{color:#666;font-size: 14px;}.article-keywords span{color:#797979}.article-readmore{display:none;width:100%;padding:60px 0 30px;background:linear-gradient(to bottom,rgba(255,255,255,0) 0,rgba(255,255,255,.79) 30%,rgba(255,255,255,1) 98%,rgba(255,255,255,1) 100%);position:absolute;bottom:0}a.link-blue-medium.articleViewMore{padding:11px 20px;display:inline-block;border:1px solid #008489;line-height:16px;font-weight:600;font-size:16px;background:#fff}.search-block{background-color:#fff;padding:15px 10px;border:1px solid #e6e5e5;width:100%;box-sizing:border-box;-moz-box-sizing:border-box}.inf-li>li,.selctnTool{box-sizing:initial}.content-wrap2 h3.signup-h3{float:none;font-weight:600;font-size:18px;color:#000;margin-bottom:5px}.inf-txts{font-size:14px;color:#000;font-weight:400}.inf-li>li>strong,a.nw-btn{font-size:14px;font-weight:600}a.nw-btn,a.nw-btn:hover{color:#fff}.inf-txts>strong{font-weight:600;text-decoration:underline}.a-btn:hover,.ana-btns,.f-btn:hover,.link-col,a{text-decoration:none}a.nw-btn{background:#008489;padding:8px 19px;margin:12px 0 16px;border-radius:2px}.background{position:relative;z-index:1;margin:0 0 15px}.z-ind{z-index:0}.background:before{border-top:1px solid #dfdfdf;content:"";margin:0 auto;position:absolute;top:50%;left:0;right:0;bottom:0;width:85%;z-index:-1}.background>span{background:#fff;padding:0 8px;color:#666}.inf-li>li{padding:0 8px;font-size:16px;color:#000;position:relative;line-height:18px;width:18%}.inf-li>li:first-child:before{width:0;height:0;border:transparent}.inf-li>li:before{content:'';position:absolute;width:5px;border-left:1px solid #ccc;left:0;top:0;height:100%}.inf-li>li>strong{display:block}#wrapper-404 h3,.content-wrap h3,.content-wrap2 h3,.home-content-wrap h1,.home-content-wrap h2,.home-content-wrap h3{font-weight:400;margin-bottom:.7em;float:left;width:100%}#wrapper-404 h3{margin-bottom:.9em}#wrapper-404 h3 p,.content-wrap h3 p,.content-wrap2 h3 p,.home-content-wrap h1 p,.home-content-wrap h2 p,.home-content-wrap h3 p,.related-header p{position:relative;font-size:1.1em;font-weight:700;line-height:90%;margin:6px 0 0 32px;margin:6px 0 0 30px\0}#wrapper-404 h3 p{font-size:1.3em;margin:4px 0 0 33px}.related-articles-head{background:#ededed;padding:12px 10px;box-shadow:0 4px 3px #dfdfdf;font-weight:700;margin-bottom:10px; color:#000}#relatedArticles .tupple-wrap{border-bottom:1px solid #e6e6e6}.tupple-wrap{padding:12px 11px 12px 10px;display:block}.tupple-wrap h2,.tupple-wrap h3{display:block;margin-bottom:5px}.footer-links{color:#006fa2;font-size:95%;line-height:125%}#relatedArticles .tupple-wrap::after{display:table;clear:both;content:""}.btn-mob{background:#F37921;color:#fff;border:1px solid #F37921;padding:5px 15px;width:100%}.para-L3{font-size:12px;line-height:16px;word-break:break-word}.content-inner{padding:8px}.content-header{border-bottom:1px solid #ccc}.title-txt{text-transform:uppercase;font-weight:700}.border-class>p>strong,.para-L1{font-weight:600}.selctnTool .selctnToolInner .slctnGird h2,.selctnTool .selctnToolInner .slctnGird h3{height:18px!important;line-height:18px!important;overflow-y:hidden;margin-bottom:1px!important}.panel-pad{padding:0 5px;margin-bottom:20px}.btn-mob-blue,.btn-mob-dis{padding:5px 30px;width:100%}.head-L1,.head-L2,.head-L3,.head-L4,.head-L5{margin:0 0 10px}.btn-mob-blue{background:#fff;color:#4285f4;border:1px solid #008489}.btn-mob-dis{pointer-events:none;background:#d0d1d1;border:1px solid #d0d1d1;color:#000}.btn-mob,.btn-mob-blue,.btn-mob-dis{text-align:center;vertical-align:middle;font-size:14px;font-weight:600;cursor:pointer;touch-action:manipulation;white-space:nowrap;background-image:none;display:inline-block;margin:20px 0 0;box-sizing:border-box;height:36px;line-height:24px}.para-L1{color:#000;font-size:14px;line-height:22px}.search__block{background-color:#fff;padding:10px;border:1px solid;border-color:#e5e6e9 #dfe0e4 #babbbd;margin-bottom:8px;box-sizing:border-box}.s-hide{display:inline-block;width:70%;position:relative}.top-m{margin-top:10px}.slider-col-tab{display:block;overflow:hidden}.slide-col-ul::after,.slide-col-ul::before{content:'';display:table}.slide-text,.slider-col-tab .slide-img{display:inline-block;vertical-align:top}.slider-col-tab .slide-img{width:60px;margin-right:10px;height:48px;overflow:hidden}.slide-text{width:73%;overflow-wrap:break-word;word-break:break-word}.sc-loc{font-size:12px;color:#999;display:block}.border-class{border:1px solid #e6e5e5;padding:7px 8px 0;display:table;width:100%;margin:10px 0 18px;box-sizing:border-box}.border-class>p{display:inline-block;width:50%;font-size:14px;color:#666;vertical-align:top;padding:7px 0}.most-viewd ul{margin:5px 0 0}.most-viewd ul>li{display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}.most-viewd ul>li:last-child{margin-bottom:0}.most-viewd ul>li>a,.most-viewd ul>li>p{display:inline-block;font-size:14px;color:#666;font-weight:400;line-height:22px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden}.most-viewd ul>li>p a:hover{color:#4285f4;cursor:pointer}.most-viewd ul>li>p a{display:inline;color:#4285f4}.most-viewd ul>li>a{color:#4285f4}.most-viewd ul>li>div,.widget-li>li{width:49%;color:#666;display:inline-block;font-size:14px}.most-viewd ul>li>div{font-weight:400;line-height:22px;vertical-align:text-bottom;box-sizing:border-box}.most-viewd .ana-btns,.widget-li>li{vertical-align:top;box-sizing:border-box}.widget-li>li{padding:0 0 7px}.widget-li>li.w100{width:100%}.widget-li>li:nth-child(2n+2){padding-left:5px}.widget-li>li a{display:inline-block;cursor:pointer;font-size:14px;color:#4285f4;line-height:1.3}.widget-li>li a strong{color:#666}.widget-li>li a:hover{color:#4285f4;text-decoration:none}.main-divs:after,.main-divs:before{content:'';display:table}.ana-btns,.link-col,.most-viewd .ana-btns{display:inline-block}.cs-section{width:100%;padding:5px 0 8px}.cs-section a{font-size:11px;color:#4285f4}.fl-sec{float:right}.fl-sec>.ana-btns{margin-top:4px}.most-viewd .ana-btns{width:49%;padding:0;float:left;font-size:12px;font-weight:600;border-radius: 2px;}.most-viewd .ana-btns.f-btn{margin-right:5px}.most-viewd .ana-btns span{font-weight:400;font-size:11px}.slider-cntrl{position:absolute;right:0;top:0;height:20px}.i-left,.i-left-disable,.i-right,.i-right-disable{background:url(../images/ana-icons-new-l.png) -104px -164px no-repeat #fff;width:18px;height:20px;display:inline-block;cursor:pointer}.i-right{background-position:-118px -164px}.i-left-disable{background-position:-131px -164px;cursor:default}.i-right-disable{background-position:-146px -164px;cursor:default}.col-heading.s-hide{font-size:16px;font-weight:400;color:#000;margin:3px 0 4px;width:100%}.ana-btns{width:88px;height:30px;line-height:28px;font-size:14px;text-transform:capitalize}.a-btn:hover{background-color:rgba(251,181,78,.75);color:#fff}.f-btn{color:#0aa5b5;background:0 0;border:1px solid #0aa5b5}a.a-btn{color:#fff!important;background:#F37921}.f-btn:hover,a.f-btn:link,a.f-btn:visited{color:#00a4b4}.f-btn:hover{background:rgba(204,226,255,.22);border:1px solid #00a4b4}.most-txt{font-size:14px;color:#000;font-weight:600}.dwn-btns-col{margin-top:8px}.link-col{font-size:12px;color:#4285f4;position:relative;cursor:pointer}.cs-section>p,.slide-text .inf-txts{font-size:14px;color:#000;font-weight:600}.rhs__ul{width:20000px}.rhs__li{width:310px!important;float:left;margin-right:10px}.hideen__div{overflow:hidden;margin-bottom:6px}.dots__section{display:block;width:100%;float:left}.dots__section:after,.dots__section:before{content:'';display:table}.carausel__bullets{width:66px;margin:0 auto}ol.carausel__bullets li{width:8px;height:8px;overflow:hidden;-moz-border-radius:50%;-webkit-border-radius:50%;border-radius:50%;background:#999;display:inline-block;content:'';margin-right:8px;vertical-align:middle;cursor:pointer}ol.carausel__bullets li.active{width:10px;height:10px;background:#008489}.most-viewd ul.mvie-courses{min-height:81px}.most-viewd ul.loc-widget>li{width:49%;display:inline-block;white-space:inherit}.cs-section>p{line-height:20px}.max__height{height:95px;margin:10px 0 15px;display:flex;align-items:center;width:100%}.slide-text .inf-txts{margin:-3px 0 0;white-space:nowrap;text-overflow:ellipsis;overflow:hidden}.qna-wrap{float:left;padding:0;width:100%;margin:0 0 18px}.qna-box{width:100%;float:left;margin-bottom:5px}.qna-icon{background:#cecece;border-radius:50%;color:#fff;display:block;float:left;font:700 20px/30px;height:30px;width:30px}.qna-details{color:#121212;margin:5px 0 0 40px}.article-cd-widget{background:#f9f9f9;-moz-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;margin:10px 0;border:1px solid #eee;text-align:center;padding:10px 10px 14px}.cd-heading{font-size:14px;color:#666;font-weight:600;line-height:1.58}.article-cd-widget .cd-btnBx{width:100%;margin:10px auto;padding:0}.article-cd-widget .cd-btnBx a{font-weight:600;width:90%;padding:4px 0;line-height:29px;height:inherit}.article-cd-widget .cd-btnBx a:first-child{margin-bottom:8px}span.articleDate{color: #000;}strong.authorName{font-weight: 600;color: #4285f4;}
strong.authorName a{text-decoration: none;}
.article-content a{font-weight: 400;text-decoration: underline;}
.article-content img,
.article-content iframe{max-width: 100%;margin-left: 0px;margin-right: 0px;}

.article-content table {
    width: 100%!important;
    height: 100%!important;
    border-collapse: collapse;
    background: #fff;
    display: block;
    overflow: auto;
    border:none;
    margin-bottom: 15px;
    margin-left: 0!important;
    margin-right: 0!important;
}



.article-content table tr td *,
.article-content table tr th * {
    padding: 0px;
    margin: 0px;
}
.article-content table tr th *,
.article-content table tr th{
    font-size:15px!important;
    line-height: 22px;
}
.article-content table th{
    background: #003D5C;
}
.article-content table th *,
.article-content table th{
    color: #fff;
    font-weight: 600;
}
.article-content table th a{
    text-decoration: underline;
    cursor: pointer;
}
.article-content table td a,
.article-content table td a *{
    color: #4285f4!important;
}
.article-content table tr ul,
.article-content table tr ol{
    margin-left: 16px;
    margin-bottom: 5px;
}
.article-content table tr ol{
    margin-left: 20px;
}
.embed{max-width: 100%!important;overflow: auto;}/*to make table scrollable in case more than screen width*/
.article-content ul, .article-content ol{margin-left: 16px;margin-bottom: 5px;}
.article-content ol{marginl-left: 20px;}
/*Page level Font hirerchy added*/
h1.f22,
.title-text{
    font-size:22px;
    line-height: 1.3;
}
 


[data-role="content"]{
  position: relative;
  top:0px;
}
.social-wrapper-top .sharing-list,
.social-wrapper-btm .sharing-list{flex-direction:row;margin:0;}
.social-wrapper-btm{
    margin: 16px 0px;
    background: #fff;
    padding: 4px 0px 0px 0px;
    box-shadow: 0px 2px 4px 2px rgba(0,0,0,0.08);
}
.social-wrapper{background: #fff;
    padding-top: 5px;
    border-top: 1px solid #d2d2d2;
    border-bottom: 1px solid #d2d2d2;
    margin-top: 0;}
.article-content a{text-decoration:underline;}
.article-content table a{text-decoration: none;}
.embed.half-width{width:100%;text-align:left;}/*due to nishant pandey editor issue*/


/*upcoming exams css*/
    .upcoming-states {border-radius: 2px;margin: 16px 0px;;background: #fff}
    .upcoming-title {font-size: 16px;padding:12px 16px;font-weight: 600;border-bottom: 1px solid #d8d8d8;}
    .upcmng-examlist {padding: 0px 16px;}
    .examslist {padding: 10px 0px;border-bottom: 1px solid #d8d8d8;}
    .anchor_holder a {font-size: 14px;color: #4285f4;font-weight: 400;cursor: pointer;}
    .exams-infodata {font-size: 14px;color: #000;line-height: 21px;margin: 2px 0px 5px;}
    .exams-infodata strong {font-weight: 600;}
    .upcoming-states .collections_a a.quick-links {font-size: 14px;color: #4285f4;font-weight: 400;}
    .upcoming-states .collections_a b {display: inline-block;color: #ccc;margin: 0 1px;font-weight: 400;}
    .examslist:last-child{border-bottom: none;}
    /*only for mobile case */
    .viewSectn {text-align: center;padding: 10px 0 20px;}
    
    /*ends*/
    
    

/**/ 
/*comment box start*/
div#topicContainer {
    background: #fff;
    margin: 12px 0px;
}

.show-option {
    padding: 10px 12px;
    border-bottom: 1px solid #d2d2d2;
}
.comment-wrapp {
    position: relative;
    margin: 0px 10px;
    color: #000;
    border-bottom: 1px solid #d2d2d2;
}
.comment-details {
    border-radius: 2px;
    float:left;
    width: 100%;
    padding: 10px 0px;
  
}
.comment-wrapp:last-child{border:none;}
.fbkBx.w6 {
    width: 100%;
    
}
.fbkBx {
    margin-bottom: 0px;
    padding: 0px;
    width: 100%;
    font-size: 14px;
    font-weight: 400;
}
.row, .wdh100 {
    width: 100%;
}
.fbkBx div.imgBx {
    width: 36px;
    height: 36px;
    overflow: hidden;
    border-radius: 50%;
    border: 1px solid #ccc;
}
.fbkBx div.cntBx {
    margin: 0 5px 0 54px;
}

.fcdGya {
    font-size: 12px;
    color: #999;
}
.clearFix {
    clear: both;
    font-size: 1px;
}
.flRt {
    float: right;
}
.fbkBx[id^=completeMsgContent] {
    border-bottom: 1px solid #e6e6e6;
    margin-left: 16px;
    padding-bottom: 6px;
    margin-bottom: 10px;
    width: 92%;
   
}
.fbkBx[id^=completeMsgContent]:before, .fbkBx[id^=completeMsgContent]:after{
    content: '';display:table;
}
.fbkBx[id^=completeMsgContent]:after{clear: both;}
.ftBx[id^=replyCommentText] {
    height: 40px!important;
}
.fbkBx .cntBx .ftxArea, .ftBx {
    border: 1px solid #e6e6e6;
    border-radius: 2px;
    color: #757575;
    font-size: 14px!important;
    overflow: auto;
    padding: 7px 5px 4px 7px;
    width: 98%;
}
.ftBx {
    font-size: 11px;
    color: #757575;
    border: 1px solid #d1d1d1;
    width: 528px;
    padding: 3px 5px;
    overflow: auto;
}
.float_L {
    font-size: 12px;
}
.errorMsg {
    color: red;
    font-size: 11px;
}
.Fnt11, .fontSize_11p_Link, .font_size_11 {
    font-size: 11px;
}
.btnSubmit, .float_R {
    float: right;
}
.orange-button, .orangeButtonStyle, .orangeButtonStyle_disabled, a.orange-button {
    background-color: #eea234;
    border: solid 1px #d2d2d2;
    overflow: visible;
    padding: 4px 8px;
    font: 400 14px 'Open Sans',sans-serif;
    color: #fff;
    line-height: normal;
    cursor: pointer;
    margin: 0;
}
.orange-button {
    border: 1px solid #eea234!important;
}
@media screen and (-webkit-min-device-pixel-ratio: 0){
  .orange-button {
      height: 30px;
   }
}
/*latest*/
.comment-wrapp:nth-child(1){
    margin-top:5px;
}
.topic_titleblock{font-size: 16px;color: #000f;font-weight: 600;}
.topic_titleblock strong{font-weight: 400;color: #666;font-size:14px;display: inline-block;}
.topic-textbox {
    padding: 10px 0px 15px;
    margin: 0px 10px;
    <!-- border-bottom: 1px solid #d2d2d2; -->
}

.limit-nums {
    font-size: 13px;
    margin: 7px 0px 0px;
    display: flex;
    flex-direction: row;
    align-items:end;
}

.limit-nums strong {
    font-weight: 400;
}
.cmntBox{width: 90%;}
.cmntBox .alignLeft{width:100%;margin: 5px 0 0;line-height: 21px;font-size: 14px;}
.alignLeft {
    width: 85%;
}

.follw-guidelines:before, .follw-guidelines:after {
    content: '';
    display: table;
}

.follw-guidelines:after {
    clear: both;
}

.alignright {
    margin-left: auto;
    width: 40%;
    justify-content: flex-end;
    display: flex;
    align-items: flex-end;
}

a.topic-btn {
    height: 34px;
    line-height: 35px;
    padding: 0px 10px;
    font-size: 14px;
    color: #fff;
    border-radius:2px;
    background: #F37921;
    display: block;
    text-align: center;
    font-weight: 600;
}

.follw-guidelines , .mobile-flex{
    display: -webkit-box;       
      display: -moz-box;        
      display: -ms-flexbox;      
      display: -webkit-flex;    
      display: flex;             
     flex-direction:row;
     align-items: center;
}
.mobile-flex{ width: 100%;}
.topic-textbox textarea {
    border: 1px solid #666;
    border-radius: 2px;
    width: 100%;
    box-shadow: none;
    min-height: 60px;
}
.flwup-txt {
    font-size: 14px;
    color: #666;
    line-height: 1.32;
}
.guideline-link a {
    font-weight: 600;
    font-size: 14px;
}
.topic-discusn{
    margin-top:10px;
}
.topic-flex {
    display: flex;
    flex-direction: row;
    margin: 6px 0px 4px;
    align-items: center;
}
.topic-flex span.rplybox {
    margin-left: auto;
    font-size: 14px;
    color: #4285f4;
    font-weight: 600;
}
.rplybox a{position: relative;padding-left: 18px;}
.rplybox i.reply-ico, .load-cmntsico, .load-rplyico{
    background: url('/public/mobile5/images/new-header-v1-01.svg')
}
.rplybox i.reply-ico {
    left: 0px;
    position: absolute;
    width: 15px;
    height: 15px;
    background-position: -9px -35px;
    display: inline-block;
}
.rplybox.disable i.reply-ico{
    background-position: -25px -35px;
}
.topic-textbox.inner-rply{margin:0px 0px 0px;padding-bottom:0px;}
div[id^=repliesContainer]{
    position: relative;
    float:left;
    margin:15px 0px 0px 9px;
    width: 100%;
}
div#commentContainer{border-top: 1px solid #d2d2d2;}
div[id^=repliesContainer]:after{
    content: '';
    position: absolute;
    left: 0px;
    width: 1px;
    height: 100%;
    bottom:0px;
    background: #d2d2d2;
}
.fbkBx[id^=completeMsgContent]:last-child{border-bottom:none;padding-bottom: 0px;margin-bottom: 0px;}
.fbkBx .mobile-flex div.cntBx{margin: 0px 5px 0px 14px}
.comment-wrapp:before, .comment-wrapp:after{content: '';display:table;}
.comment-wrapp:after{clear: both;}
.topic-textbox .errorMsg{margin: 10px 0px 0px;}
.imgBx img {width: 100%;height: 100%;}
.hide{display:none;}
.topic-flex span.rplybox.disable a{color: #c8c8c8;pointer-events: none;}
.topic-btn.disabled {
    background: #d6d7d7;
    color: #666;
    pointer-events: none;
}
.cancelBtn a{
    padding: 0px 7px;
    display: inline-block;
    height: 35px;
    line-height: 33px;
    font-weight: 600;
    margin-right: 7px;
    border-radius: 2px;
    font-size: 16px;
    border: 1px solid #008489;
}
.topic-textbox.inner-rply .follw-guidelines{margin: 5px 0px 12px;}
.topic-textbox.inner-rply .alignright{width: 55%;}
.load-replies {
    font-size: 0.875rem;
    float: left;
    width: 100%;
    font-weight: 600;
    padding:5px 0px 6px 22px;
    position: relative;
    margin: 10px 0px 0px;
}
a.load-cmnts {
    display: block;
    padding: 10px;
    border-top: 1px solid #ccc;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    position: relative;
}
i.load-cmntsico {
    position: absolute;
    top: 10px;
    margin-left: 5px;
    width: 15px;
    height: 15px;
    background-position: -43px -34px;
}
i.load-rplyico {
    position: absolute;
    top: 4px;
    width: 18px;
    left: 2px;
    height: 18px;
    background-position: -63px -33px;
}
/*comment box end*/

/*CHP Interlinking start*/
.chp-interlinking{background:#fff;padding:0;box-shadow:0 0 2px 0 rgba(0,0,0,.12),0 2px 2px 0 rgba(0,0,0,.24);border-radius:2px;border:none;position:relative;margin-bottom: 15px;}
.groupcard h2{padding:10px 12px;border-bottom:1px solid #e6e5e5;margin-bottom:0;font-size:16px;color:#000;font-weight:600}
.chplist{padding:10px 16px;display:flex;flex-flow:row wrap}
.chplinks{width:100%;position:relative;padding-left:15px;margin: 0 0px 5px 0;}
.chplinks:last-child{margin-bottom: 0px;}
.chplinks:before{content:'';width:5px;height:5px;background:#000;display:block;position:absolute;left:0;top:9px;border-radius:50%}
.chplinks a{color:#4285f4;font-size:14px;font-weight:400;cursor: pointer;display: block;line-height: 1.32}
.showchplinks{display:block;text-align:center;font-size:14px;color:#4285f4;font-weight:400;padding:5px 0 15px;position: relative}
.groupcard input {display: none; }
.showchplinks:after{content:"";display:inline-block;width:6px;height:6px;border-bottom:2px solid #4285f4;border-right:2px solid #4285f4;margin:0 0 0 9px;transform:rotate(45deg);position: relative;top: -3px;}
.groupcard input[type='radio']:checked~.showchplinks{display:none;}
.chplinks.hidechp{display: none;}
.groupcard input[type='radio']:checked + .chplist > .chplinks.hidechp{display: block}
/*CHP Interlinking end*/



a.button--orange{color: #fff !important;}

.article-conten table tr td p, table tr td span {color: #000 !important;font-size: 14px !important;line-height: 21px !important;}


:root{font-size: 16px;}
    h1,h2,h3,h4,h5, h6, 
   .h1,.h2,.h3,.h4,.h5, .h6{color: #000;font-weight: 600;margin: 6px 0px 4px;} 
   h1,.h1{font-size: 1.25rem;line-height: 1.625rem;} 
   h2,.h2{font-size: 1rem;line-height: 1.375rem;} 
   h3,.h3{font-size: 0.9375rem;line-height: 1.3125rem;} 
   h4,.h4{font-size: 0.875rem;line-height: 1.3125rem;} 
   h5,.h5{font-size: 0.8125rem;line-height: 1.1875rem;;} 
   h6,.h6{font-size: 0.75rem;line-height: 1.125rem;} 

   /*buttons*/
    [type=button]:not(:disabled), [type=reset]:not(:disabled), 
    [type=submit]:not(:disabled), button:not(:disabled) { cursor: pointer;}
    .button{display:inline-block;cursor: pointer;position: relative;font-weight:600;text-align:center;vertical-align:middle;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-color:transparent;border:1px solid transparent;padding: 0px 25px;font-size:0.875rem;height: 34px;line-height: 32px;border-radius:2px;outline: none;}
    .button--orange, a.button--orange{background-color: #F37921;border-color: #F37921;color: #fff;}
    .button--blue{background-color: #1E90FF;border-color: #1E90FF;color: #fff;}
    .button--secondary{background-color: transparent;border: 1px solid #008489;color: #008489;}
    .button[disabled], .button.btn-mob-dis, .button.disabled{background-color: #D6D7D7;color: #666;pointer-events: none;border: 1px solid #D6D7D7;cursor: default;} 
/*table typography*/ 
   .artilce-content table, .table{display: table;border-collapse: collapse;width: 100%;border-radius: 2px;} 
    .article-content table tr th{text-align: center;background-color: #003D5C;color: #fff;font-size: 0.9375rem;font-weight: 600;padding: 4px 8px 6px;line-height: 1.3125rem;border: 1px solid #003D5C;} 
   .article-content table tr td{color: #000;text-align: left;background-color: #fff !important;border: 1px solid #003D5C !important;font-size: 0.875rem;padding: 2px 8px 6px !important;line-height: 1.3125rem;} 
   .article-content table tr th h1,.article-content table tr th h2, .article-content table tr th h3,.article-content table tr th h4,.article-content table tr th h5,.article-content table tr th h6{color: #fff;}
/*ends*/

/*asklink*/
i.chatIcon {
  position: relative;
  display: inline-block;
  width: 16px;
  height: 13px;
  z-index: 1;
}
i.chatIcon::first-line {
  line-height:40px;
}
i.chatIcon::before,
i.chatIcon::after {
  content: "";
  position: absolute;
  top: 2px;
  left: 0px;
  z-index: -1;
  transition:none;
}
i.chatIcon::before {
  width: 12px;
  height: 8px;
  margin: 0;
  background-color: #fff;
  border-radius: 3px;
  border: 1px solid #4c4c4c;
}
i.chatIcon::after {
  height: 0;
  margin: 0px 0px 0px 0px;
  border-width: 2px;
  border-style: solid;
  border-color: #4c4c4c transparent transparent #4c4c4c;
  bottom: -2px;
  right: 3px;
  left: auto;
  top: auto;
}
div#askLink {
    padding: 16px 14px;
    background: #fff;
    font-size: 14px;
    display: none;
}
div#askLink span{
    padding-left: 4px;
}
/*end ask link*/
/*Unchangeable DFP Tags CSS :  untill editorial requirments change*/
.hiddenCMSElem{
    display:none!important;
}
/*end*/


/*social sharing band*/
.social-sharing-widget{
    background: transparent;
}
.sharing-box{
    position: relative;
}
.social-icons{
    display: block;
    width: 26px;
    height: 26px;
    background-image: url('https://images.shiksha.com/pwa/public/images/social_shring-02.svg');
    background-repeat: no-repeat;
    background-position: 0 0;
    transform:scale(1.15);
}
.social-icons.facebook{
    background-position: -84px 0 ;
}
.social-icons.twitter{
    background-position: -56px 0px ;
}
.social-icons.email{
    background-position: -112px 0 ;
}
.social-icons.linkedin{
    background-position: -28px 0 ;
}
.social-icons.whatsapp{
    background-position: 0 0 ;
}
.sharing-band-list{
    list-style: none;
    display: flex;
    justify-content: center;
    align-items: center;
}
.sharing-band-list li{
    padding: 5px 7px;
}
.sharing-band-list li:last-child{
    border-bottom: none;
}
.sharethis{
    white-space: nowrap;
}
.rytPipe{
    padding-right: 15px;
    position: relative;
    text-align: center;
}
.rytPipe:after{
    content: "";
    display: block;
    width: 1px;
    background: #ccc;
    position: absolute;
    height: 30px;
    right: 0px;
    top: 3px;
}
.rytPipe > *{
    line-height: 18px;
}
.block{
    display: block;
}
.sharing-band-list li:first-child {
    padding-left: 0px;
}
/*end*/