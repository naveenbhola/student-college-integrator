<style>
#main-wrapper{width: 100%;}
.activity_tabs,.profile_intro,.tab_wrapper{box-shadow:0 2px 2px 0 rgba(0,0,0,.16),0 0 0 1px rgba(0,0,0,.08)}
.abroad_intrst ul,.exam_list{list-style:none}
*,:after,:before{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}
abbr,address,article,aside,audio,b,blockquote,body,canvas,caption,cite,code,dd,del,details,dfn,div,dl,dt,em,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,p,pre,q,samp,section,small,span,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,ul,var,video{background:0 0;border:0;margin:0;outline:0;padding:0;vertical-align:baseline}
.edit_ic{position:relative;width:20px;height:20px;background:url(<?php echo IMGURL_SECURE?>/public/images/abroad-common-sprite8.png) -229px -206px;display:inline-block;vertical-align:middle}
.edu_name sup,.schooling sup{vertical-align:super}
.user_container{font-family:'Open Sans',sans-serif;box-sizing:border-box;width:1180px;margin:0 auto;padding:30px 0;}
.profile_block{width:783px;margin:0 auto}
.profile_lft{width:100%}
.aspirent, .admitted{background-color:#f38130;padding:0 4px;border-radius:2px;margin-right: 8px}
.admitted{background-color: #069b99}
.profile_intro{min-height:221px;display:block;background-color:#fff}
.profile_slct{padding:18px 16px;background:url(<?php echo IMGURL_SECURE?>/public/images/sa_pp_bg_2.jpg) top center no-repeat rgba(0, 0,30,103);position:relative}
.edit_col{display:block;position:absolute;right:16px;z-index: 1}
.edit_col a:hover{text-decoration: none}
.profile_slct .edit_col a{float:right;font-size:14px;cursor:pointer;color:#fff;font-weight:600;display:inline-block}
.user_wrap{position:relative;display:block}
.abroad_intrst ul li,.pic_around.clearfix,.plan_info,.stream strong,.txt_wrap,.work_expo{display:inline-block}
.pic_around.clearfix{position:relative;top:0;left:0;vertical-align:middle}
.pic_col{width:96px;height:96px;border-radius:50px;background-color:#bfd1df;border:1px solid #e1e1e1;background-size: cover !important;background-position: center !important;background-repeat: no-repeat !important;}
.alias_dtls,.tab-content,.tab_wrapper{background-color:#fff}
.txt_wrap{padding-left:10px;width:74%;margin-left:2px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;word-break:break-word;vertical-align:middle}
.user_alias{font-size:16px;color:#fff;font-weight:600;margin-bottom:8px;line-height:22px}
.rank_state{font-size:12px;font-weight:400;line-height:18px;color:#fff}
.alias_dtls{padding:14px 16px}
.course_intrst{width:265px;position:relative;padding-right:12px;float:left;min-height: 60px;}
.course_intrst .stream strong{display: block;}
.course_intrst .stream strong span{font-size: 12px;font-weight: 400}
.country_aspire,.work_expo{padding:0 12px;min-height:60px;float:left}
.country_aspire :after,.course_intrst:after,.work_expo:after{content:'';position:absolute;right:0;border:1px solid #e1e1e1;top:0;height:100%}
.similar_p{font-size:12px;color:#666;line-height:17px;margin-bottom:2px}
.abroad_intrst,.stream{line-height:18px}
.stream{font-size:12px;color:var(--Charcoal-Black)}
.stream strong{font-size:14px;font-weight:600}
.country_aspire{width:180px;position:relative}
.work_expo{width:156px;position:relative}
.plan_info{width:107px;padding-left:12px}
.abroad_intrst ul li:first-child{font-size:14px;font-weight:700}
.abroad_intrst ul li,.schooling{font-size:12px;line-height:18px}
.abroad_intrst ul li{color:var(--Charcoal-Black)}
.abroad_intrst ul li a{color:inherit;cursor:pointer}
.abroad_intrst ul li a:hover{text-decoration: none;cursor: text;}
.schooling{color:var(--Charcoal-Black)}
.schooling strong{display:block;font-weight:600;font-size:14px;line-height:18px}
.tab_wrapper{margin-top:16px;width:100%;height:auto;margin-bottom:16px}
.tab-content{padding:0}
.flot_col{display:block;padding:16px 16px 0}
.tabs_data .flot_col:last-child{padding-bottom:16px; margin-top: 10px;}
.edu_info{font-size:12px;color:#666;line-height:18px}
.edu_name,.title_of{font-size:14px;color:var(--Charcoal-Black);line-height:18px}
.title_of{font-weight:600; font-size: 16px;}
.edu_name{font-weight:600}
.exam_list li{display:inline;font-size:12px;color:#666}
.exam_list li a{color:inherit;cursor:pointer}
.exam_list li:first-child{display:block;font-size:14px;color:var(--Charcoal-Black);line-height:20px;font-weight:600}
.split-divs{font-size:0;display:block;margin-top:8px}
.split-divs .divide_col{width:240px;display:inline-block;vertical-align:top;margin:15px 15px 0 0}
.split-divs .divide_col:nth-child(1),.split-divs .divide_col:nth-child(2),.split-divs .divide_col:nth-child(3){margin-top:0}
.split-divs .divide_col:nth-child(3n+3){margin-right:0}
.text_p{font-size:12px;color:var(--Charcoal-Black);line-height:18px}
.personal_inf .edu_info{line-height:21px}
.personal_inf .edu_name{line-height:18px}
.activity_tabs{height:36px;background-color:#f8f7f7;position:relative}
.inner_tabs{text-decoration:none;padding:10px 13px;display:inline-block}
.inner_tabs h2{font-size:14px;line-height:18px;color:#666;font-weight:400}
.inner_tabs.active h2{font-weight:600;color:#3A6696}
#tab3-tab.active~span.yellow-bar{left:0;width:134px;height:2px}
#tab4-tab.active~span.yellow-bar{left:135px;width:147px;height:2px}
.list_tuples{display:block;padding:15px}
.tuple-box,.tuple-image{padding:5px;position:relative}
ul.tuple-cont li{background:#fbfbfb;border:1px solid #f3f3f3;margin-bottom:8px;list-style:none}
ul.tuple-cont li.sponsered{background:#fef9dc}
.tuple-box{float:left;width:100%}
.flLt{float:left}
.tuple-image{background:#fff;border:1px solid #f3f3f3;width:184px}
.tuple-detail{margin-left:193px}
.tuple-title{margin-bottom:10px;width:90%}
.tuple-title a{font-weight:700;font-size:13px;color:#666}
.tuple-title span{color:#999}
.font-11{font-size:11px}
.paid-icon{background-position:-226px 0!important;width:12px;height:10px;margin-right:4px}
.tuple-sub-title{font-weight:700;font-size:15px}
.cate-sprite{background:url(<?php echo IMGURL_SECURE?>/public/images/abroad-category-sprite4.png);display:inline-block;font-style:none;vertical-align:middle}

.add-shortlist,.added-shortlist{background-position:0 -51px;width:78px;height:77px;position:absolute;top:0;right:-2px;cursor:pointer}
.added-shortlist{background-position:-81px -51px;width:77px;right:0}
.uni-course-details{width:356px;border-right:1px solid #dadada;background:#fff;margin:8px 25px 8px 0;padding:8px;position:relative}
.uni-course-details:after,.uni-course-details:before{right:-1px;height:1px;background-image:-webkit-linear-gradient(right,#dadada,#fff);background-image:-moz-linear-gradient(right,#dadada,#fff)}
.uni-course-details:before{width:100%;bottom:-1px;background-image:-webkit-gradient(linear,0 100%,0 0,from(#fff),to(#dadada));background-image:-o-linear-gradient(#fff,#dadada)}
.uni-course-details:after{left:-1px;top:-1px;background-image:-webkit-gradient(linear,0 0,100% 0,from(#dadada),to(#fff));background-image:-o-linear-gradient(left,#dadada,#fff)}
.uni-course-details:after,.uni-course-details:before{content:"";position:absolute}
.detail-col{width:33%;font-size:13px;color:#666}
.detail-col strong{color:#999;margin-bottom:5px;display:block}
.detail-col p{margin-bottom:3px}
.accepted-tooltip{background:#eee;border:1px solid #ccc;border-radius:2px;font-size:11px;font-style:italic;padding:2px 4px;position:absolute;top:-34px;width:277px}
.hide{display:none}
.detail-col p.non-available{color:#999}
.cross-mark{color:#a7a7a7;margin-right:3px;font-weight:700;font-size:14px}
.tick-mark{color:#666;margin-right:3px;font-weight:400}
.btn-col{margin:27px 0 0;float:left}
.btn-brochure a.button-style{padding:8px 9px 9px}
.btn-rate-change{float:left;margin:3px 0 0;position:relative}
a.rate-change-button{color:#fff}
.rate-change-button{background:#069;font-weight:700;-webkit-border-bottom-right-radius:22px;-moz-border-radius-bottomright:22px;border-bottom-right-radius:22px;border:1px solid transparent;padding:7px 16px 6px 10px;font-size:15px;width:152px}
.btn-rate-change .race-change-button{padding:8px}
.text-center{text-align:center}
.compare-box{font-size:11px;color:#666;width:125px}
.flRt{float:right}
.customInputs input[type=checkbox],.customInputs input[type=radio],.customInputs-large input[type=checkbox],.customInputs-large input[type=radio]{display:none}
input[type=checkbox]{vertical-align:bottom}
.sponsered-text{color:#999;font-size:12px;font-style:italic;margin:15px 0 5px;display:none}
.tar{text-align:right}
.customInputs input[type=checkbox]+label span,.customInputs-large input[type=checkbox]+label span{background-position:-116px -148px;background-color:rgba(0,0,0,0);cursor:pointer;display:block;margin:0;vertical-align:middle;width:16px;height:14px;float:left}
.clearfix{display:block}
.clearfix:after{visibility:hidden;display:block;content:" ";clear:both;height:0}
.clear_max:after,.clear_max:before{content:'';display:table}
.clear_max:after{clear:both}
.flot_col.clear_max.personal_inf{padding-bottom: 16px;margin-top: 17px;position: relative;padding-top: 18px;}
.flot_col.clear_max.personal_inf:before{content: '';position: absolute;border-top: 1px solid #e1e1e1;width: 96%;left: 0;margin: 0 auto;right: 0;top: 0;}
</style>
