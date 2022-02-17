<style type="text/css">
body{min-width: 1200px}
#main-wrapper{WIDTH:100%;}
#header-top-section, .navigation{width: 988px;margin: 0 auto;float: none;}
.apply_container, *{font-family: "Open Sans",sans-serif;box-sizing: border-box}
/* .apply_bg{position:relative;height: 470px;background: #584441 url(<?php echo IMGURL_SECURE?>/public/images/sa-hp-bg.jpg) 50% 0 no-repeat fixed;border-bottom: 1px solid #ccc}
.main_sectn{position: absolute; left: 0px;top: 50%;width: 1180px;margin:-110px auto;right:0px;color: #fff}
.main_sectn .fnt_42{font-size: 42px;line-height:44px;color: #fff;width: 672px;margin-bottom: 30px;}
.main_sectn .fnt_42 span{font-weight: 400;font-size: 26px;display: block}
.main_sectn .btn, .sticky_div .btn{display: inline-block; border-radius: 2px;font-size:16px;font-weight: 600;padding: 10px 15px;background-color: var(--Primary-Orange);color: #fff;box-shadow:2px 2px 3px rgba(0,0,0,0.3);}
.main_sectn .btn:hover{cursor: pointer;text-decoration: none;background:#ea942d;box-shadow: 2px 2px 3px rgba(0,0,0,0.4)}*/

/*adding css for apply home top card starts*/
.apply_bg{height:440px; background:#f7f9c0;font-family:'open sans';background-image: linear-gradient(-180deg, #FDDB92 0%, #D1FDFF 100%);z-index:0;position: relative;}
.apply-carouselList{width:100%;position: relative;}
.apply-carouselList ul li{list-style: none;display:inline-block;width:100%;color:#000;}
.apply-carouselDiv{text-align: center;padding:45px 0 10px;}
.apply-carouselDiv span{font-size:24px;display:block;margin-bottom:6px;position: relative;font-weight:600}
.apply-carouselDiv span:before{content:'';background:url(<?php echo IMGURL_SECURE?>/public/images/apply-desktop-sprite2.png)no-repeat;width: 30px;height: 28px;background-position: -282px -170px;display: inline-block;position: absolute;top: -38px;left: 50%;margin-left: -22px;}
.apply-carouselDiv p{font-size:14px;}
.apply-carouselDiv p strong{font-weight:600;}
.main_sectn{width:100%;height:130px;margin:0px auto 25px auto;color: #000;background: #fff;padding: 10px;box-sizing: border-box;text-align: center;position:relative;}
.main_sectn h1{font-size:28px;}
.main_sectn h1 span{font-size:18px;font-weight:400;}
.apply-caraouselbullets {text-align: center;display: inline-block;width:100%;margin-bottom: 25px;}
.apply-caraouselbullets ul li{width:10px; height:10px; background: #8c8c8c; border-radius:50%; margin-right:10px;display:inline-block;cursor: pointer;}
.apply-caraouselbullets ul li.active{background: #008489;}
.counseling-boxes{width:800px;display: table;margin:10px auto;}
.counseling-boxes>div{display: table-cell;width:25%;color: #666; font-size: 14px;text-align:center;}
.counseling-boxes>div span{display:block;margin-top:4px;font-weight:600;}
.Rated-box{width:250px;text-align: center;margin: 0 auto;}
.Rated-box a.evaluationCall-btn{background: var(--Primary-Orange);color:var(--White);font-size: 14px;font-weight: 600;height: 32px;line-height: 32px;width: 246px;text-decoration: none;display: block;border-radius: 2px;}
.review-rateTab{font-size: 14px;margin-bottom:15px;color:var(--Charcoal-Black);}
.review-rateTab p{display:inline-block;}
.review-rateTab p strong{font-size: 20px;display:inline-block;font-weight:600;}
.Rating-block{background: url(<?php echo IMGURL_SECURE?>/public/images/star_icon.svg) no-repeat; background-position: -5px -10px;width: 96px;height: 15px;display: inline-block;background-size: 108px 59px;position: relative;top: 0px;left: 5px;}
.Rating-Fullblock{background: url(<?php echo IMGURL_SECURE?>/public/images/star_icon.svg) no-repeat; background-position: -5px -36px;width: 70%;height: 15px;display: inline-block;background-size: 108px 59px;position: absolute;left: 0;top: -1px;}
.Rated-box a.review-tag{display:block;color:var(--Teal); text-decoration: none;font-size: 12px; font-weight:600;}
.Rated-box a.review-tag:after{content:'';    border: solid #008489;border-width: 0 2px 2px 0;font-size: 20px;display: inline-block;font-weight: 600;line-height: 31px;width: 8px;height: 8px;transform: rotate(-45deg);top: 0px;position: relative;left: 2px;}
.main_sectn.effect2:before, .main_sectn.effect2:after{z-index: -1;position: absolute;content: "";bottom: 17px;left: 0px;width: 50%;top: 80%;max-width:645px;background: #000;-webkit-box-shadow: 0 18px 28px #000;-moz-box-shadow: 0 18px 28px #000;box-shadow: 0 18px 28px #000;-webkit-transform: rotate(-3deg);-moz-transform: rotate(-3deg);-o-transform: rotate(-3deg);-ms-transform: rotate(-3deg);transform: rotate(-3deg);}
.main_sectn.effect2:after{-webkit-transform: rotate(3deg);-moz-transform: rotate(3deg);-o-transform: rotate(3deg);-ms-transform: rotate(3deg);transform: rotate(3deg);right: 0px;left: auto;}
i.univ-icon,i.chat-icon,i.counslng-icon,i.personalised-icon{background:url(<?php echo IMGURL_SECURE?>/public/images/apply-desktop-sprite2.png)no-repeat;width: 33px;height: 30px;background-position: -282px -11px;display: inline-block;vertical-align: middle;margin-right:5px;}
i.chat-icon{background-position: -282px -49px;}
i.counslng-icon{background-position: -282px -92px;}
i.personalised-icon{background-position: -282px -130px;}
/* span.valign-mid {position: relative;top: -7px;} */
/*adding css for apply home top card ends*/

/*Top slider animation starts*/
.apply-carouselList .slider-list{width: 100%;height:135px;position:relative;overflow: hidden;}
.apply-carouselList .slider-list li{width: 100%;height:135px;position: absolute;}
.apply-carouselList .slider-list li.inactiveSlide{opacity: 0;z-index: 1;}
.apply-carouselList .slider-list li.active{opacity: 1;z-index: 2;}
.apply-carouselList .slider-list li.buble > .random_box{transform:scale(.9)}
.apply-carouselList .slider-list a, .slider-list a:hover{text-decoration: none;}
.apply-carouselList .reviewSlide.slide--up {-webkit-transform: translateX(100px) translateZ(0);transform: translateX(100px) translateZ(0);}
.apply-carouselList .reviewSlide.slide--up.slide-in {-webkit-transform: translateX(0px) translateZ(0);transform: translateX(0px) translateZ(0);}
/*Top slider animation ends*/










.apply_process{position:absolute;background-color: rgba(0, 0, 0, 0.65);left:0px;right:0px;width:100%;bottom:0px;height:90px;}
.apply_col .page_width{width: 1224px;margin: 0 auto;padding:42px 20px 58px;}
.apply_process .page_width{width: 1224px;margin: 0 auto;padding: 0 20px;display: table}
.apply_col .page_width.test_div{padding:30px 20px}
.apply_page div.apply_col{width: 100%;}
.apply_page div.apply_col:nth-child(odd){background-color: #fff;}
.apply_page div.apply_col:nth-child(even){background-color: #f9f9f9;}
.prc_img i{background: url(<?php echo IMGURL_SECURE?>/public/images/apply-desktop-sprite2.png)no-repeat;margin: 0 auto;display: inline-block;width: 62px;height: 65px;vertical-align: middle;position: relative;}
.process_1 .prc_img i{background-position: -176px 4px;left: 3px}
.process_2 .prc_img i{background-position: 5px 6px;left: 0px;}
.process_3 .prc_img i{background-position: -55px 15px;width: 67px;left: 0px}
.process_4 .prc_img i{background-position: -120px 6px;width: 60px;left: 5px}
.process_1, .process_2, .process_3, .process_4{color: #fff;vertical-align:middle;display: table-cell;width: 25%}
.prcs_data{display: table-cell;vertical-align: middle;}
.process_1 .prc_title, .process_2 .prc_title, .process_3 .prc_title, .process_4 .prc_title{font-size: 16px;font-weight: 600;line-height: 21px;}
.process_4 { width: 20%;}
.process_3 { width: 26%; padding-left: 18px;}
.process_2 { padding-left: 12px;}
.process_1 p, .process_2 p, .process_3 p, .process_4 p{font-size: 14px;font-weight: 400;line-height: 21px;}
.prc_img{width: 70px;height: 90px;vertical-align: middle;display: table-cell;padding-right: 12px}
#statsDiv{display: none;}
#statsLoader{text-align:center;height: 100px;}
#statsLoader img{margin-top: 40px;}
.test_div h2{line-height:28px}
.sticky_div .btn{display: inline-block; margin-left:16px;border-radius: 2px;font-size:16px;font-weight: 600;padding: 10px 15px;background-color: var(--Primary-Orange);color: #fff;box-shadow:2px 2px 3px rgba(0,0,0,0.3);}
.test_div h2 p{font-weight: 400}
.test_div p strong{font-weight: 600}
.sub_txt{font-size: 16px;}
.fnt_20{font-size: 20px;line-height: 23px;color:var(--Charcoal-Black);font-weight: 600;text-align: center;}
.flt__lft {float: left;}
.flt__right{float: right;}
.txt_cntr{text-align:center;}
.fnt_12{font-size:12px;}
.fnt_14{font-size:14px;color:var(--Charcoal-Black);line-height:21px;font-weight:400}
.fnt_16{font-size:16px;color:var(--Charcoal-Black);line-height:24px;font-weight:600}
.fnt_18{font-size:18px;color:var(--Charcoal-Black);line-height:22px;font-weight:600}
.fnt_28{font-size:28px;color:var(--Charcoal-Black);line-height:41px;font-weight:700}
.fnt_36{font-size:36px;color:var(--Charcoal-Black);}
.steps .min_sub_txt{line-height: 21px;margin-top: 4px;}
.fade {opacity: 0;transition: opacity .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1);transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);-webkit-transform: translateZ(0);transform: translateZ(0);-webkit-backface-visibility: hidden;backface-visibility: hidden;}
.slide {transition: opacity .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1);transition: opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1),-webkit-transform .5s cubic-bezier(.4,0,.2,1);-webkit-backface-visibility: hidden;backface-visibility: hidden;}
.slide--up {-webkit-transform: translateY(100px) translateZ(0);transform: translateY(100px) translateZ(0);}
.slide-in {-webkit-transform: translate(0) translateZ(0);transform: translate(0) translateZ(0);}
.fade-in {opacity: 1;}
.sticky_div{-webkit-transform: translateY(-107%) translateZ(0);transform: translateY(-107%) translateZ(0);}
/*slider animation*/
a.random_box{display: block;}
.random_box.slide--up {-webkit-transform: translateX(100px) translateZ(0);transform: translateX(100px) translateZ(0);}
.random_box.slide--up.slide-in {-webkit-transform: translateX(0px) translateZ(0);transform: translateX(0px) translateZ(0);}
.reviewSlide.slide--up {-webkit-transform: translateX(100px) translateZ(0);transform: translateX(100px) translateZ(0);}
.reviewSlide.slide--up.slide-in {-webkit-transform: translateX(0px) translateZ(0);transform: translateX(0px) translateZ(0);}
.slider-list{width: 100%;height:370px;position:relative;overflow: hidden;}
.slider-list li{width: 100%;height:366px;position: absolute;}
.slider-list li.inactiveSlide{opacity: 0;z-index: 1;}
.slider-list li.active{opacity: 1;z-index: 2;}
.slider-list li.buble > .random_box{transform:scale(.9)}
.slider-list a, .slider-list a:hover{text-decoration: none;}
</style>
