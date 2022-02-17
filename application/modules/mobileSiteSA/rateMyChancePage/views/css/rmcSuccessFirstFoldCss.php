<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 24/8/18
 * Time: 5:06 PM
 */
?>
<style>
    *{font-family: 'open sans';}
    body{background: #fff;margin: 0px;}
    .ap-pos{position:relative; z-index:2}
    /* .paralex-div{background:#573f3a url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/apply-bg.jpg) 100% 0 no-repeat fixed;width: 100%; height: 212px;font-size: 16px; color: #fff;position: relative;background-size: contain;}
    .paralex-div p{line-height: 20px;}
    .ap-txtDv{line-height: 22px;  color: #fff;}
    .paralex-cont{position: absolute;bottom: 24px;width: 100%; padding: 0 10px;text-shadow: 1px 1px 5px rgba(0,0,0,0.8);}
    p.hd-tl{font-size: 22px;font-weight: 700;position: relative;line-height: 26px;width: 260px;margin-bottom: 8px;}
    p.hd-sbtl{ font-size: 16px;margin-bottom: 18px;font-weight: 600;}
    */
    a.book-btn{max-width: 300px;height: 36px;border-radius: 2px;color:#fff;background-color: var(--Primary-Orange);text-align: center;line-height:34px;display:block;margin: 0 auto;border:1px solid var(--Primary-Orange);font-size: 14px;font-weight: 600;font-size: 16px;box-shadow: 0px 2px 2px 0px rgba(0,0,0,0.3);text-shadow:none;}
    .ap-whtBg{background: #fff;}
    .apl-widgt{padding: 10px 12px;}
    .apl-widgt ul{margin: 20px 0 0 0;}
    .apl-widgt ul li{list-style: none;padding-bottom: 25px;position: relative;overflow: hidden;}
    .apl-widgt ul.csl-proc li:before{content: '';position: absolute;border-left: 1px solid #999;left: 29px;bottom: 0px;top:10px;display: block;}
    .apl-widgt ul.csl-proc li:last-child:before{display: none;}
    .apl-box:nth-child(2n+1) { background: #f9f9f9;}
    .apl-box:nth-child(2n) { background: #fff;}
    .apl-hed{font-size: 20px; font-weight: 700; line-height: 24px; margin: 20px 0 4px 0; text-align: center;letter-spacing: -0.175px;}
    .apl-cptn{font-size: 14px; line-height: 20px; text-align: center;padding: 0px 10px;}
    .choose-dv{width: 100%;margin-top: 20px;height: 372px; overflow: hidden;}
    .choose-bx{background: #fff;padding: 10px; border: 1px solid #e2e2e2;margin-bottom: 8px;border-radius:2px;}
    .initAnim.anim-box2{opacity:0;-webkit-animation:fadeIn .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);-moz-animation:fadeIn .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);animation:fadeIn .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);
        -webkit-animation-fill-mode:forwards;-moz-animation-fill-mode:forwards;animation-fill-mode:forwards;}
    .initAnim.anim-box2.one {-webkit-animation-delay: 0.05s;-moz-animation-delay: 0.05s;animation-delay: 0.05s;}

    .initAnim.anim-box2.two {-webkit-animation-delay: 0.1s;-moz-animation-delay:0.1s;animation-delay: 0.1s;}

    .initAnim.anim-box2.three {-webkit-animation-delay: 0.15s;-moz-animation-delay: 0.15s;animation-delay: 0.15s;}
    .initAnim.anim-box2.four {-webkit-animation-delay: 0.2s;-moz-animation-delay: 0.2s;animation-delay: 0.2s;}
    .counseling-ico,.univercity-ico,.response-ico,.free-ico{background:url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/apply-sprite1.svg) no-repeat; position: relative;float: left; width: 60px; height: 60px;}
    .counseling-ico{background-position: -1px 0px;}
    .univercity-ico{background-position: -55px 2px;right:2px;}
    .response-ico{background-position: -113px 2px;right:2px;}
    .free-ico{background-position:-167px 1px;}
    .choose-info{margin-left: 60px;font-size: 14px; line-height: 18px;}
    .choose-info.last{padding-top: 10px;}
    .choose-info strong{font-size: 16px;display: block; margin-bottom: 4px;}
    .play_back i,.acad-ico, .eval-call-ico, .shrtl-ico,.dc-prp-ico, .app-sub-ico, .vis-cnc-ico,.info-ico, .vw-prfl a:after {background:url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/apply-sprite1.svg) no-repeat; position: relative; display: inline-block; background-position: -60px -60px; width: 51px; height: 50px;left:4px;}
    @-webkit-keyframes fadeIn { from { opacity:0;transform:translateY(100px) translateZ(0)} to { opacity:1;transform: translate(0) translateZ(0);}}
    @-moz-keyframes fadeIn { from { opacity:0; transform:translateY(100px) translateZ(0) } to { opacity:1;transform: translate(0) translateZ(0); } }
    @keyframes fadeIn { from { opacity:0; transform:translateY(100px) translateZ(0)} to { opacity:1;transform: translate(0) translateZ(0);  } }
    @media screen and (orientation: landscape) {
        .paralex-div{background-size: 100%;}
        .paralex-cont a.book-btn{margin-left: 0px;}
        .paralex-cont{padding-left: 15px;}
    }
    .layer-header,.layer-search{background:#fff;padding:0;border-bottom:1px solid #aaa;font-size:12px;font-weight:bold;width:100%;display:table}
    .back-box{padding:10px 12px;display:table-cell;vertical-align:middle;width:35px}
    .back-icn{background-position:-99px -36px;width:7px;height:13px}
    .layer-header p,.layer-header div,.layer-search-box{display:table-cell;line-height:1.58;vertical-align:middle;font-size:16px;font-weight:600;color:#333}
    .rmc-form-submit-sec{width:100%;float:left;padding:10px 5px;background:#f7f7f7}
    .sent-bro-icon{background-position:-200px -184px;width:18px;height:18px;margin-right:3px}
    .rmc-form-submit-info{margin-left:25px;margin-bottom:10px;line-height:18px}



/*New Top Card CSS starts*/
    .apply_bg{height:440px; background:#f7f9c0;font-family:'open sans';background-image: linear-gradient(-180deg, #FDDB92 0%, #D1FDFF 100%);z-index:0;position: relative;}
    .apply-carouselList{width:100%;}
    .apply-carouselList ul li{list-style: none;display:inline-block;width:100%;color:#000;}
    .apply-carouselDiv{text-align: center;padding:52px 20px 20px;}
    .apply-carouselDiv span{font-size:18px;display:block;margin-bottom:6px;font-weight: 600;position: relative;line-height: 24px;}
    .apply-carouselDiv span:before {content: '';background: url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/apply-sprite1.svg)no-repeat;    width: 26px;height: 28px;background-position: -230px -140px;display: inline-block;position: absolute;top: -38px;left: 50%;margin-left: -10px;}
    .apply-carouselDiv p{font-size:12px;margin-bottom:5px;}
    .apply-carouselDiv p strong{font-weight:600;}
    .main_sectn{width:100%;height:140px;margin:0px auto;color: #000;background: #fff;padding:15px 10px 10px;box-sizing: border-box;text-align: center;position:relative;}
    .main_sectn h1{font-size:15px;}
    .main_sectn h1 span{font-size:10px;font-weight:400;}
    .apply-caraouselbullets {text-align: center;display: inline-block; width: 100%;margin-bottom: 10px;}
    .apply-caraouselbullets ul li{width:10px; height:10px; background: #8c8c8c; border-radius:50%; margin-right:10px;display:inline-block;cursor: pointer;}
    .apply-caraouselbullets ul li.active{background: #008489;}
    .counseling-boxes{width:100%;display: table;margin:10px auto;}
    .counseling-boxes>div{display:inline-block;width:48%;color: #666; font-size: 12px;font-weight:600;text-align: left;margin-bottom: 10px;}
    .counseling-boxes>div span{display: block;vertical-align: middle;line-height: 20px;padding-left: 37px;}
    .Rated-box{width:250px;text-align: center;margin: 0 auto;}
    a.evaluationCall-btn{background: var(--Primary-Orange);color:var(--White);font-size: 14px;font-weight: 600;height: 32px;line-height: 32px;width: 246px;text-decoration: none;display: block;border-radius: 2px;}
    .review-rateTab{font-size: 12px;margin-top:12px;margin-bottom: 18px;}
    .review-rateTab p{display:inline-block;}
    .review-rateTab p strong{font-size: 20px;display:inline-block;}
    .Rating-block{background:url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/star_icon.svg)no-repeat; background-position: -5px -10px;width: 88px;height: 15px;display: inline-block;background-size: 100px 59px;position: relative;top: -1px;cursor: pointer;left: 5px;}
    .Rating-Fullblock{background: url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/star_icon.svg)no-repeat; background-position: -5px -35px;width: 70%;height: 15px;display: inline-block;background-size: 100px 59px;position: absolute;left: 0;top: 1px;}
    .Rated-box a.review-tag{display:block;color:var(--Teal);text-decoration: none;font-size: 12px; font-weight:600;margin-top:5px;}
    .Rated-box a.review-tag:after {content: '';border: solid #008489;border-width: 0 2px 2px 0;font-size: 20px;display: inline-block;font-weight: 600;line-height: 31px;width: 7px;height: 7px;transform: rotate(-45deg);top: 0px;position: relative;left: 2px;}
    .main_sectn.effect2:before, .main_sectn.effect2:after{z-index: -1;position: absolute;content: "";bottom: 17px;left: 0px;width: 50%;top: 80%;max-width:50%;background: #000;-webkit-box-shadow: 0 15px 20px #000;-moz-box-shadow: 0 15px 20px #000;box-shadow:0 15px 20px #000;-webkit-transform: rotate(-3deg);-moz-transform: rotate(-3deg);-o-transform: rotate(-3deg);-ms-transform: rotate(-3deg);transform: rotate(-3deg);}
    .main_sectn.effect2:after{-webkit-transform: rotate(3deg);-moz-transform: rotate(3deg);-o-transform: rotate(3deg);-ms-transform: rotate(3deg);transform: rotate(3deg);right: 0px;left: auto;}
    i.univ-icon, i.chat-icon, i.counslng-icon, i.personalised-icon {background: url(<?php echo IMGURL_SECURE ?>/public/mobileSA/images/apply-sprite1.svg)no-repeat;width: 27px;height: 23px;background-position: -230px -14px;float:left;vertical-align: middle;margin-right: 5px;position: relative;top:7px;}
    i.counslng-icon {background-position: -230px -85px;}
    i.chat-icon { background-position: -230px -51px;}
    i.personalised-icon {background-position:-230px -117px;}
    /*New Top Card CSS ends*/

    /*Top slider animation starts*/
    .slide {
        transition: opacity .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);
        transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1);
        transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    }
    /* .fade {
        opacity: 0;
        transition: opacity .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);
        transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1);
        transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    } */
    .apply-carouselList .slider-list{width: 100%;height:146px;position:relative;overflow: hidden;margin-bottom: 15px;}
    .apply-carouselList .slider-list li{width: 100%;height:146px;position: absolute;}
    .apply-carouselList .slider-list li.inactiveSlide{opacity: 0;z-index: 1;}
    .apply-carouselList .slider-list li.active{opacity: 1;z-index: 2;}
    .apply-carouselList .slider-list li.buble > .random_box{transform:scale(.9)}
    .apply-carouselList .slider-list a, .slider-list a:hover{text-decoration: none;}
    .apply-carouselList .reviewSlide.slide--up {-webkit-transform: translateX(100px) translateZ(0);transform: translateX(100px) translateZ(0);}
    .apply-carouselList .reviewSlide.slide--up.slide-in {-webkit-transform: translateX(0px) translateZ(0);transform: translateX(0px) translateZ(0);}
    /*Top slider animation ends*/
</style>
