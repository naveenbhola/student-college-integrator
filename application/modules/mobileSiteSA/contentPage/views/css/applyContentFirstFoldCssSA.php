<style>
*{font-family: 'Open Sans'}
.detail-widget{padding:12px 0px;}
.detail-widegt-sec{background:#fff; border:1px solid #ebebeb;font-size:14px; line-height:1.58;color:var(--Charcoal-Black);}
.detail-widget._headingSec .detail-widegt-sec{background: transparent;border: none;padding: 0;}
.detail-info-sec{padding:15px;}
.detail-widegt-sec p{margin-bottom:10px;}
.sop-content{color:var(--Charcoal-Black); line-height:1.58; padding:5px 15px !important; word-wrap:break-word !important;}
.sop-content p{margin-bottom:6px;}
.sop-content h1, .sop-content h1 a, .sop-content h1 strong{font-size:20px; font-weight:600;line-height: 26px;}
.sop-content h2, .sop-content h2 a, .sop-content h2 strong{font-size:16px;font-weight:600;line-height: 22px;}
.sop-content h3, .sop-content h3 a, .sop-content h3 strong{font-size:15px;font-weight:600;line-height: 21px;}
.sop-content h4, .sop-content h4 a, .sop-content h4 strong{font-size: 14px;font-weight:600;line-height: 21px;}
.sop-content h5, .sop-content h5 a, .sop-content h5 strong{font-size: 13px;font-weight:600;line-height: 19px;}
.sop-content h6, .sop-content h6 a, .sop-content h6 strong{font-size: 12px;font-weight:600;line-height: 18px;}
.sop-content blockquote, blockquote{padding:10px; background:#f8f8f8; color:#666; font-size:12px; border-left:3px solid var(--Teal); margin:5px 0; line-height:1.58}
.sop-content blockquote h1, blockquote h1{font-size:20px; font-weight:normal;}
.sop-content blockquote h2, blockquote h2{font-size:16px; font-weight:normal}
.sop-content blockquote h3, blockquote h3{font-size:15px; font-weight:normal}
.sop-content blockquote h1 strong, blockquote h1 strong, blockquote strong{font-weight:600;}
.sop-content blockquote h2 strong, blockquote h2 strong{font-weight:600}
.sop-content blockquote h3 strong, blockquote h3 strong{font-weight:600}
.sop-content strong/*, .sop-content h1*/{font-size:13px; color:var(--Charcoal-Black); margin-bottom:6px !important;}
.mobile-sop-sprite{background:url(<?php echo IMGURL_SECURE; ?>/public/mobileSA/images/mbl-sop-sprt3.png) no-repeat; display:inline-block; position:relative;}
.sop-content img{vertical-align:middle; margin-right:10px;}
.detail-info-sec.sop-content.dyanamic-content h2.ContentHeading-h2{font-size: 16px !important;line-height:1.58; color:#333;margin-bottom:5px;}
/* .sop-content h1, .sop-content h1 a{font-size:16px !important;}
.sop-content h2, .sop-content h2 a{font-size:14px !important;}
.sop-content h3, .sop-content h3 a, .sop-content h4, .sop-content h4 a, .sop-content h5, .sop-content h5 a, .sop-content h6, .sop-content h6 a{font-size:13px !important;} */
.sop-content h1 img, .sop-content h2 img, .sop-content h3 img, .sop-content h4 img, .sop-content h5 img, .sop-content h6 img{margin-right:5px;}
.font-11{font-size:11px;}
.sop-content ul{margin-left:20px;display: inline-block;}
.sop-content ul li{margin-bottom:5px !important; list-style:disc !important;color:var(--Charcoal-Black);}
.sop-content table{width:100%; float: left; border:1px solid #eee; margin: 10px 0;}
.sop-content table tr td, .sop-content table tr th{border: 1px solid #eee; border-collapse: collapse}
.sop-banner-sec{background:#e0f8fc; border:1px solid #e4e4e4; color:#666; font-size:30px; text-align:center; padding:70px 0}
/*.newExam-page {padding: 15px;background: #fff;color: #333;font-size: 14px;line-height: 1.58;font-family: 'open sans';width: 100%;float: left}
.newExam-page h1 {display: block;font-size: 16px;color: #333;line-height: 1.58;margin-bottom: 8px;font-family: 'open sans'}
.newExam-page p {margin-bottom: 8px}
.newExam-page h2,.newExam-page h3 {font-size: 18px;color:var(--Charcoal-Black);margin-bottom: 8px;display: block}
.newExam-page h3 {font-size: 16px;line-height: 1.58;}
*/
.dynamic-content.apply-abroad p{font-size: 14px}
.content-NavHead{background:#fff;padding: 10px 12px;font-size: 14px;color: #111;font-family: 'open sans'}
.content-NavHead h1{font-size: 20px; line-height: 26px;}
.content-img {width: 100%;min-height: 180px;}
.content-quickLink {background:  #fff;margin-top: -1px;box-shadow: 0 0 2px 0 rgba(0,0,0,.12), 0 2px 2px 0 rgba(0,0,0,.24);position: relative;}
.content-quickLink ul {min-width:  max-content;display:  table;padding-right: 29px;float:  none;padding-left: 0;}
.content-quickLink ul li {display: table-cell;font-size:  14px;list-style:  none;text-align:  center;float:  none;}
.content-quickLink ul li a {padding: 13px 14px 10px;display:  inline-block;color: #111;border-bottom: 5px solid transparent;}
.content-quickLink ul li a.active {color: var(--Teal);border-bottom: 5px solid var(--Teal);font-weight: 600;}
.content-grd {content: '';background: linear-gradient(90deg,rgba(255,255,255,0) 14%,rgba(255,255,255,.87) 45%,rgba(255,255,255,1) 73%);position: absolute;right: 0;width: 48px;height: 42px;top: 0;opacity: 0.99;z-index: 1;}
.content-grd i.arrow.right {border: solid var(--Teal);border-width: 0 2px 2px 0;display: inline-block;padding: 4px;transform: rotate(-45deg);-webkit-transform: rotate(-45deg);top: 15px;position:  relative;right: -24px;}
.content-qckLins {white-space: nowrap;overflow:  auto;background: #fff;}
.content-quickLink.fixed-hdr {position:  fixed;z-index:  10;top: 1px;width: 100%}
.wrapper.mb10 ::-webkit-scrollbar { display: none;}

</style>
