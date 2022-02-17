<style type="text/css">
.CntryHomePage-Head{padding:10px; border-bottom:1px solid #ccc; background:#fff; font-family:'open sans'; font-weight:bold; color:#333;font-size:20px;margin-bottom: 1px;}
.cntry-tabs{width:100%; display:block;}
.cntry-tabs ul{display:table;width:100%;}
.cntry-tabs ul li{display:table-cell; width:33.3%; position:relative;}
.cntry-tabs ul li a{padding:5px 10px; border-right:1px solid #929292; background:#ccc; text-decoration:none !important; display:block; text-align:center; height:40px;box-shadow:0 2px 3px #999;}
.cntry-tabs ul li a span.inner-circle{background:#fff; width:30px; height:30px; text-align:center; -moz-border-radius:50%; -webkit-border-radius:50%; border-radius:50%; display:block; float:left}
.cntry-tabs ul li a p{margin-left:40px; text-align:left; margin-top:6px; font-size:16px; font-weight:bold; font-family:'open sans'}
.cntry-tabs ul li.active a, .cntry-tabs ul li:hover a{background:#fff;color:#333;}
.cntry-tabs ul li.active span.inner-circle, .cntry-tabs ul li:hover span.inner-circle{background:var(--Teal);}
.cntry-tabs ul li.active .study-pointer, .cntry-tabs ul li:hover .study-pointer{display:block;}
.univrsty-title{color:#666; font-size:12px; font-weight:bold; line-height:18px; height:35px;}
.country-container{padding:0 7px; background:#f8f8f8; border-left:1px solid #e7e7dc; border-right:1px solid #e7e7dc; width:100%; float:left;}
.country-Innercontainer{background:#fff;padding:15px 10px; width:100%; float:left; margin-bottom:15px;}
.popular-title, .country-mainTitle{border-bottom:2px solid #999; font-size:14px; font-weight:bold; padding-bottom:5px; margin-bottom:15px; display:inline-block; color:#333; float:left;}
ul.popular-course-slider{width:100%; float:left; width:1000px; overflow:hidden;}
ul.popular-course-slider li{list-style:none; width:206px; float:left; margin-right:10px; background:#fff; border:1px solid #eee; height:336px;}
ul.popular-course-slider li span{display:block; margin:5px 0; color:#989898; height:30px; line-height: 15px;}
.popularCrse-detail{ padding:8px 11px;}
.popularCrse-detail a{line-height: 15px; height: 30px;}
.grey-bg{background:#f8f8f8;}
.poplrUniv-img{text-align:center; margin:0 auto;}
.white-bg{background:#fff;}
.popularCrse-info{margin:8px 0 0; font-family:'open sans'}
.popularCrse-info p{margin-bottom:10px;}
.popularCrse-info p.last{margin:0;}
.popularCrse-info label{color:#666; font-size:12px;}
.popularCrse-info strong{font-weight:bold; display:block; margin-top:7px; color:#666; font-size:11px;}
.viewAll-link{margin:10px 0 20px; float:left; display:inline-block;}
.countryPage-widget{width:100%; float:left; padding-bottom:10px; border-bottom:1px solid #eee; margin-bottom:10px;}
.countryPage-widget strong.widget-title, h3.widget-title{margin:8px 0; font-size:12px; color:#666; display:block; width:100%; float:left;}
.countryPage-widget ul{float:left; width:100%;margin:10px 0 10px 15px;}
.countryPage-widget ul li{list-style:disc;}
.countryPage-widget ul li span{color:#666; font-size:12px; margin-top:8px; display:block;}
.fee-expense-widget{margin:10px 0; border-bottom:1px solid #eee;}
.fee-expense-widget p{margin:6px 0; font-size:12px; line-height:18px; color:#666;}
.fee-expense-widget strong{display:block; color:#666; font-size:12px; margin-bottom:8px;}
h3.avrgCost{font-size:12px; color:#666;}
.study-graph{border-bottom: 1px solid #999; width: 100%; height: 150px; position: relative; margin-bottom: 30px;}
.big-bar, .small-bar{height:90px; width:20px; background:#999; bottom:0; position:absolute; left:70px;}
.bar-tooltip{background:#fff; border:1px solid #eee; padding:5px; color:#666; font-size:11px; text-align:center; position:relative; width:80px; top:-39px; left:-28px;}
.score-graph .bar-tooltip{left:-14px;}
.study-sprite{background:url(<?php echo IMGURL_SECURE?>/public/mobileSA/images/abroad-study-sprite1.png) no-repeat; position:relative; display:inline-block;}
.bar-arrw{background-position:-71px 0px; width:12px; height:10px; top:21px; left:36%; position:absolute}
strong.graphsubtitle, strong.graphsubtitle-exam{display: block;width: 168px; bottom: -32px; position: absolute; left: -32px; font-size:12px;}
strong.graphsubtitle-exam{left:-6px;}
.small-bar{height:60px; width:20px;  left:180px;}
.coachmark-bgLayer{background:rgba(0,0,0,0.8);position:fixed;top:0;left:0;right:0;bottom:0;width:100%;height:100%;z-index:1000}
.countryHome-coachmark{width:100%;float:left;z-index:100;position:absolute;top:0}
.cmark-homeNav{position:absolute;top:80px;z-index:1000;width:100%;text-decoration:none!important;font-family:'open sans'}
.cntry-tabs{width:100%;display:block}
p.select-text{font-size:16px;font-family:'open sans';color:#ccc;z-index:1000;position:absolute;top:145px;left:20%;margin:21px 0 0 0}
.coach-arrw{background-position:-0px -39px;width:39px;height:36px;position:absolute;top:-35px;left:7%}
.Ok-btn{background:#ccc;color:#333!important;padding:8px 20px;z-index:1000;font-family:'open sans';text-align:center;text-transform:uppercase;font-size:12px;text-decoration:none!important;font-weight:600;display:block;margin:0 10px;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}

/*adding css for top second level navigation*/
.nav-List{transition: all 150ms ease-out 0s;background: #fff; margin-top: -1px; box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24); position: relative;padding:0;margin-bottom:10px;}
.nav-List.collapsed{height:40px;overflow:hidden;}
.nav-List.expanded{height:auto;overflow:hidden;}
.expnd-circle {position: absolute;right: 0px;top: -16px;padding: 30px 10px 9px 20px}
.ib-circle {display: inline-block;border-radius: 50%;}
.expnd-switch {display: inline-block;width: 24px;height: 24px;border: 1px solid #008489;border-radius: 50%;text-align: center;position: relative;background-color: #fff;box-shadow: 2px 2px 3px rgba(0,0,0,0.2);}
.expnd-switch:before{content: "";width: 2px;height: 14px;position: absolute;background: #008489;top: 4px;left: 10px;}
.expnd-switch:after{content: "";width: 14px;height: 2px;background: #008489;position: absolute;top: 10px;left: 4px;}
.nav-List.expanded .expnd-switch:before{content: none;}
.expanded ~ ul.navList-items{height:auto;}
ul.navList-items{width:90%;padding:10px 0 0px 10px;}
ul.navList-items li{list-style: none;display: inline-block;padding-bottom:8px;}
ul.navList-items li a {padding: 0 12px;color: #111;display: inline-block;border-bottom: 4px solid transparent;font-size: 0.875rem;font-weight: 600;text-decoration: none;height:32px;line-height:32px;}
ul.navList-items li.active a{background:#008489; color: #fff; font-weight: 600;border-radius:8px;padding:0px 12px;}

</style>
