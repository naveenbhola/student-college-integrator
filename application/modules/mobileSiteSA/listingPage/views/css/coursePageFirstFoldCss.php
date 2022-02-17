<style type="text/css">
/*Basic CSS For Page Layout*/
.detail-widget{padding:8px 8px 15px;}
.detail-widegt-sec{background:#fff; border:1px solid #ebebeb;font-size:12px; line-height:18px;}
.detail-info-sec{padding:15px;}
.detail-widegt-sec p{margin-bottom:10px;}
.detail-widget._headingSec .detail-widegt-sec{background: transparent;border: none;padding: 0;}
ul.univ-detail-list{width:100%; float:left; font-size:12px !important; color:var(--Charcoal-Black)!important;}
ul.univ-detail-list li{border-bottom:1px solid #e0e0e0; padding-bottom:20px; margin-bottom:20px !important; list-style:none}
ul.univ-detail-list li.last-child{border:none; margin-bottom:0 !important;}
/*sop*/
.header-fixed{position:fixed; left:0px; z-index:9; width:100%; box-shadow:0 0 0 1px #4c4c4c}
.detail-header{background:#f5f5f5; display:table; width:100%; padding:0}
.detail-header a{display:table-cell; width:80px;white-space:nowrap;padding:6px; line-height:1.58; color:var(--Charcoal-Black) !important; text-align:center;}
.detail-header a.active{background:var(--Charcoal-Black);}
.pointer{background-position:-215px -52px; width:10px; height:5px;  display:none;position:relative; bottom:-7px; left:48%}
.detail-header a.active .pointer{ display:block;}
.detail-header a p{margin-top:8px;overflow: hidden;line-height: 1.58;font-size: 14px;}
.detail-header a.active p{color:#fff;}
.overview-icn, .overview-icn-a{background-position:-55px -96px; width:16px; height:15px;}
.overview-icn-a{background-position:-37px -96px}
.eligibility-icn, .eligibility-icn-a{background-position:-92px -96px; width:19px; height:18px;}
.eligibility-icn-a{background-position:-72px -96px;}
.fee-icn, .fee-icn-a{background-position:-129px -96px; width:16px; height:16px;}
.fee-icn-a{background-position:-113px -96px;}
.placement-icn, .placement-icn-a{background-position:-167px -96px; width:21px; height:16px;}
.placement-icn-a{background-position:-146px -96px;}
.more-info-icn, .more-info-icn-a{background-position:-206px -96px; width:17px; height:17px;}
.more-info-icn-a{background-position:-188px -96px;}
.application-icn, .application-icn-a{background-position:-38px -213px; width:18px; height:18px;}
.application-icn-a{background-position:-18px -213px;}
.consultant-icn, .consultant-icn-a{background-position:-73px -213px;width:18px; height:18px;}
.consultant-icn-a{background-position:-57px -213px;}
.photo-icn, .photo-icn-a{background-position:-113px -213px;width:18px; height:18px;}
.photo-icn-a{ background-position:-90px -213px;}
.scholarship-icn, .scholarship-icn-a{background-position:-37px -233px; width:18px; height:25px;}
.scholarship-icn-a{ background-position:-58px -233px; }

.video-icn, .video-icn-a{ background-position:-160px -213px;width:18px; height:18px;}
.video-icn-a{ background-position:-136px -213px;}
.less-more-sec{border-top:1px solid #ebebeb; text-align:center; padding:8px;}
.course-title{padding:15px 20px 0 20px; font-size: 16px; font-weight: bold; color: #333;line-height: 1.58;}
.detail-info{background:#fff; color:var(--Charcoal-Black); font-size:12px; line-height:18px; border-bottom:1px solid #ebebeb;}
.detail-info p{margin-bottom:8px;}
.detail-info-sec{padding:15px;}
.detail-widegt-sec{background:#fff; border:1px solid #ebebeb;font-size:12px; line-height:18px;}
.detail-widegt-sec p{margin-bottom:10px;}
.detail-info-sec ul{width:100%;}
.detail-info-sec ul li{width:100%; color:var(--Charcoal-Black); font-size:14px;line-height: 1.58;list-style:none; margin-bottom:15px;line-height: 1.58;padding: 0px 15px;}
.detail-info-sec ul li strong{display:block; color:var(--Charcoal-Black); margin-bottom:10px; font-size:14px;}
.detail-info-sec ul li p{color:var(--Charcoal-Black);}
.font-12{font-size:14px !important;}
.detail-info-sec h1, .detail-info-sec strong, .detail-widegt-sec strong {font-size: 14px;margin-bottom: 8px;display: block;}
.univ-detail-section{cursor:pointer}
.fee-eligibilty-criteria{width:100%; margin:10px 0 0; display:table;}
.fee-eligibilty-col{width:48%; display:table-cell}
.fee-eligibilty-col span{display:block; color:#999;}
.fee-eligibilty-col p{color:var(--Charcoal-Black); margin:5px 0 0;}
.univ-detail-arrw{background-position:-212px -135px; width:10px; height:16px; cursor:pointer}
.available-course-detail{background:#fff; padding:10px 10px 0; color:#333; font-size:12px; line-height:16px;}
.load-more-courses{display:inline-block; color:#fff !important; font-size:12px; font-weight:bold; padding:10px; background:var(--Teal); width:100%; text-align:center; margin:5px 0;}
.search-course-title{margin:15px 0 10px;}
.shortlist-icn, .shortlist-icn-filled{background-position:-174px -51px; width:19px; height:19px; margin-bottom:3px}
.shortlist-icn-filled{background-position:-194px -51px;}
.univ-name{text-transform:uppercase; color:var(--Charcoal-Black); font-size:12px;vertical-align:middle; display:inline-block; line-height:1.58; margin-left:5px; word-wrap: break-word;}
.detail-widegt-sec strong, .detail-info-sec strong, .detail-info-sec h1{font-size:16px; margin-bottom:5px; display:block;line-height: 1.58;color:#333;}
.dynamic-content{font-size:14px !important; text-align: left !important; color:var(--Charcoal-Black) !important;line-height: 1.58;}
.dynamic-content ul, .dynamic-content ol{margin-left: 15px; width: auto !important}
.dynamic-content ul li,.dynamic-content ol li{list-style: disc !important; color:var(--Charcoal-Black) !important; margin-bottom: 8px !important; padding: 0 !important}
.dynamic-content ul li strong,.dynamic-content ol li strong{display:inline;margin:0}
.dynamic-content ol li{list-style:decimal !important;}
.dynamic-content ol li ul, .dynamic-content ul li ol{margin-left: 22px}
.dynamic-content ol li ul li, .dynamic-content ul li ol li{list-style: disc !important; padding: 0 !important}
.dynamic-content ul li ol li{list-style:decimal !important;}
.mbl-compare-btn2{color:#666 !important; font-weight:bold; text-decoration:none; padding:6px 7px; background:#ccc; text-align:center; display:inline-block; margin-bottom:8px; width:100%;}
.mbl-compare-icn{background-position:-18px -233px; width:15px; height:15px; vertical-align:middle;}
.email-icon{background-position:-121px -36px; width:17px; height:10px; margin-right:5px; position:relative;top:-2px;}
strong._topHeading{font-size: 18px;}
</style>
