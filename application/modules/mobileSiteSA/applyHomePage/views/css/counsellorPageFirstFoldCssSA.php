<style type="text/css">
body{
    font-family: 'Open Sans',sans-serif;background: #f9f9f9;
}
*, *:before, *:after {
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
}
.counselr-page {
    width: 100%;
    margin: 0 auto;
    padding: 10px 0;
    box-sizing: border-box;
    cursor: pointer;
}

.profile_left {
    width: 100%;
    word-wrap: break-word
}

.profile_right {
    width: 100%;
    margin: 0
}

._card {
    width: 100%;
    box-shadow: 0 0 2px 0 rgba(0,0,0,.12),0 2px 2px 0 rgba(0,0,0,.24);
    background-color: #fff;
    margin-bottom: 15px
}

.clear_max:after,.clear_max:before {
    content: '';
    display: table
}

.clear_max:after {
    clear: both
}
.counceller_profile {
    padding: 12px 15px 15px 15px;
}
.pic_col {
    height: 64px;
    overflow: hidden;
    background-position: center center !important;background-size: auto 100% !important;background-repeat: no-repeat !important;

}
.pic_col, .score {
    width: 78px;
    height: 78px;
    display: inline-block;
    float: left;
}
.counselr_expert {
    margin-left: 96px;
    margin-top: 3px;
}
.main_h1 {
    font-size: 16px;
    font-weight: 700;
    color: #000;
    margin-bottom: 3px;
}
.expert_txt, .fnt_1_2 {
    font-size: 12px;
    color: #333;
    line-height: 20px;
}
.overall_rating {
    margin: 12px 0 16px;
}
.score {
    width: 105px;
    padding: 10px 5px;
    background-color: #f9f9f9;
}
.rating_titl {
    font-size: 12px;
    color: #333;
    text-align: center;
    margin-bottom: 2px;
}
.avg_rating {
    font-size: 36px;
    color: #333;
    text-align: center;
}
.overall_rating .counselr_expert {
    margin-left: 0;
    float: right;
    min-width: 180px;
}
.expert_txt {
    line-height: 1.58;
}
.rating_bar {
    list-style: none;
}
.rating_bar li {
    display: table;
    width: 100%;
    margin-bottom: 6px;
}
.vrfy_txt, .vrfy_txt strong {
    font-size: 12px;
    font-weight: 400;
    color: #666;
    line-height: 17px;
}
.rating_bar li div {
    display: inline-block;
    width: 53%;
    text-align: left;
    position: relative;
}
.rating_bar li div.vrfy_txt:first-child {
    margin-left: 22px;
}
.rating_bar li div.vrfy_txt p {
    position: relative;
    font-weight: 600;
    color: #333;
}
.fit_table ul li:before, .learn_more:after, .rating_bar li div.vrfy_txt p>i, .vrfy_txt strong:before {
    background: url(../../public/images/counslr-sprite3.png) no-repeat;
}
.helpText {
    z-index: 1;
}
.rating_bar li div.vrfy_txt p>i {
    content: '';
    background-position: 0 0;
    width: 17px;
    height: 16px;
    position: absolute;
    top: 2px;
    left: 68px;
}
.rating_bar li:first-child div.vrfy_txt p>i {
    left: 95px;
}
.rating_bar li div:last-child {
    text-align: right;
    width: 33%;
}
.bar_col {
    padding-left: 11px;
    color: #333;
    font-weight: 600;
    font-size: 12px;
}
span.stu-cmt {
    float: left;
    width: 78%;
}
.rating_block {
    float: right;
    display: inline-block;
    background-color: #f5f5f5;
    position: relative;
    color: #333;
    font-size: 14px;
    top: 0px;
    border-radius: 2px;
    line-height: 17px;
    padding: 1px 4px;
    background-color: #f5f5f5;
    border: 1px solid #cbcbcb;
    max-width: 22%;
    text-align: center;
}
.fnt1_4_6 {
    color: #666;
    font-weight: 400;
    line-height: 18px;
    font-size: 12px;
}
.text_desc {
    margin-top: 10px;
}
.fnt_1_4_7 {
    font-weight: 400;
    line-height: 1.58;
    color: #333;
    font-size: 14px;
}
.new-left {
    display: inline-block;
    float: left;
    font-size: 13px;
    margin-top: 10px;
}
.r_date {
    font-size: 12px;
    color: #7d7d7d;
    float: right;
    display: inline-block;
    margin-top: 10px;
}
.fnt1_4_3{color: #333;font-weight: 600;line-height: 18px;font-size: 14px;}
.about_col{border-top: 1px solid #e6e5e5;padding: 10px 0 0;}
.about_col p{font-size: 14px;color: #333;line-height: 1.58;}
.apply_exprt{text-align: center;}
.learn_more .about_col {text-transform: capitalize;}
.about_col .learn_more, .about_col .rd_more{display: block;margin-top: 0px;cursor: pointer;text-align: left}
.card_head{padding:10px 16px;display: table;border-bottom: 1px solid #e1e1e1;position: relative;width: 100%;vertical-align: middle;}
.card_head .btn_blue{width: 128px;float: right;position: absolute;right: 12px;top: 50%;margin-top: -16px;}
.card_head > .title{font-size: 16px;font-weight: 600;color: #333;line-height: 21px}
.vrfy_txt strong{color: #00cdcb;font-weight: 400;font-size: 12px;}
.pic-wrap{position: relative;width: 40px;height: 40px;background-color: transparent;border-radius: 50%;display: block;overflow: hidden;background-position: center center;background-size: auto 100%;background-repeat: no-repeat;border: 1px solid #e6e6e6}
.fit_table ul li:first-child:before, .fit_table ul li:before, .fit_table ul li:last-child:before {
    content: '';
    background-position: -2px -18px;
    width: 40px;
    height: 28px;
    position: absolute;
    top: 50%;
    margin-top: -10px;
}
.fit_table ul li:first-child:before{height:43px;top:9px}
.card_body{padding: 0 10px;min-height: 76px}
.review_tuple{display: block;position: relative;margin-top: 16px;padding-bottom: 16px;border-bottom: 1px solid #e6e5e5}
.review_tuple:last-child{border: none}
.input-helper {
    background: #666;
    color: #fff;
    max-width: 250px;
    border-radius: 2px;
    width: 240px;
    top: 100%;
    text-align: center;
    margin-top: 1px;
    line-height: 1.4;
    z-index: 8;
    font-size: 12px;
    opacity: 0;
    pointer-events: none;
    -webkit-transform: scale(.9);
    -ms-transform: scale(.9);
    transform: scale(.9);
    -webkit-transform: 15px 0;
    -moz-transform: 15px 0;
    -ms-transform: 15px 0;
    transform: 15px 0;
    -webkit-transform-origin: 15px 0;
    -moz-transform-origin: 15px 0;
    -webkit-transition: .2s ease all;
    transition: .2s ease all;
    -ms-transform-origin: 15px 0;
    transform-origin: 15px 0;
}
.input-helper, .up-arrow {left: auto;position: absolute;right: -29px;
    position: absolute;
    top: 22px;}
.up-arrow {top: 0;left: auto;right: 10px}
.up-arrow:before {content: '';border: solid transparent;position: absolute;top: -10px;left: -5px;border-width: 6px;border-bottom-color: #666;margin: -2px 0 0 -1px;}
.up-arrow:after {content: '';border-width: 5px;border-bottom-color: #666;}
.helper-text {padding: 4px 8px;display: block;}
.info_tip:hover + span.input-helper{opacity: 1;}
.vrfy_txt strong:before{content: '';background-position: -19px 0; width: 13px; height: 9px;display: inline-block;margin-right: 3px;}
@media screen and (device-aspect-ratio: 40/71) {
  .rating_bar li div.vrfy_txt:first-child{
          width:117px;
          margin-left: 0px;
   }
  .rating_bar li:first-child div.vrfy_txt p > i{
      left:105px;
  }
  .rating_bar li:nth-chikd(2) div.vrfy_txt p i{left:71px;}
  .rating_bar li div.vrfy_txt p > i{left:74px}
}
#reviewHead{margin-bottom: 5px;}
</style>
