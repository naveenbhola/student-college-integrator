<style type="text/css">
 #main-wrapper{width:100%;box-shadow: none;background: #f9f9f9;display: inline-block;}
 .loan-container {
     width: 100%;
     font-family: 'open sans';
     color: var(--Charcoal-Black);
     line-height: 1.58;
 }
 .loanCont-widget {
     padding: 12px;
     box-shadow: 0 0 2px 0 rgba(0,0,0,0.12), 0 2px 2px 0 rgba(0,0,0,0.24);
     margin: 16px 0;
     position: relative;
     font-size: 14px;
     line-height: 22px;
     background: var(--White);
 }
 .loan-title {
     font-size: 20px;
     line-height: 26px;
     font-weight: 600;
     margin-bottom: 6px;
 }
 .edu-loanWidget {
     padding: 10px 0;
     border-top: 1px solid var(--Smoke);
     border-bottom: 1px solid var(--Smoke);
     margin: 20px auto;
     text-align: center;
     max-width: 1000px;
 }
 .edu-loanWidget strong {
     font-size: 18px;
     font-weight: 600;
 }
 .edu-loanBox {
     display: table;
     width: 100%;
     margin: 10px 0;
 }
 .edu-loanBox>div {
     display: table-cell;
     font-size: 14px;
     font-weight: 600;
     position: relative;
     width: 33%;
 }
 .sgnup-icn, .sgnup-Licn, .sgnup-Picn {
     background-position: -62px 4px;
     width: 42px;
     height: 50px;
 }
 .edu-loanBox>div p.edu-lnDiv {
     width: 100px;
 }
 .edu-loanBox>div:after {
     content: '';
     position: absolute;
     top: 28%;
     right: 0;
     background-position: -9px -61px;
     width: 12px;
     height: 14px;
 }
 .sgnup-icn, .sgnup-Licn, .sgnup-Picn, .edu-loanBox>div:after, .loan-contentBox ul li p:before, .loan-contentBox ul li p:after, .thanku-widget:before, .Ldummy-txt strong:before, .one-icn, .two-icn, .three-icn, .step-sec ul li.active label .one-icn, .step-sec ul li.active label .two-icn, .step-sec ul li.active label .three-icn, .step-sec2 ul li.active label .one-icn, .step-sec2 ul li.active label .two-icn, .step-sec2 ul li.active label .three-icn {
     background: url(<?php echo IMGURL_SECURE ?>/public/responsiveAssets/images/loan-sprite.svg) no-repeat;
     display: inline-block;
     position: relative;
 }
 .sgnup-icn, .sgnup-Licn, .sgnup-Picn {
     background-position: -62px 4px;
     width: 42px;
     height: 50px;
 }
 .sgnup-Licn {
     background-position: -104px 4px;
 }
 .sgnup-Picn {
     background-position: -151px 4px;
 }
 .loan-btnWrap {
     text-align: center;
     margin: 15px 0;
 }
 .loan-btnWrap a {
     display: inline-block;
     background: var(--Primary-Orange);
     height: 36px;
     line-height: 36px;
     padding: 0 25px;
     border-radius: 2px;
     color: var(--White);
     font-size: 14px;
     font-weight: 600;
     text-decoration: none;
 }

 .loanCont-widget.noPadding {
     padding: 0;
 }
 .loanCont-widget .widget-title {
     font-size: 16px;
     font-weight: 600;
     padding: 12px;
     border-bottom: 1px solid var(--Smoke);
     display: block;
     width: 100%;
 }
 .loan-contentBox.wht-spc {
     white-space: nowrap;
 }
 .loan-contentBox {
     padding: 10px 12px;
     overflow: auto;
 }
 .loan-contentBox ul {
     min-width: max-content;
 }
 .loan-contentBox ul li {
     width: 300px;
     height: 230px;
     border-radius: 2px;
     display: inline-block;
     text-align: center;
     box-shadow: 0 0 2px 0 rgba(0,0,0,0.16), 0 2px 4px 0 rgba(0,0,0,0.32);
     padding: 10px;
     white-space: normal;
     margin-right: 10px;
     }
 .loan-contentBox ul li p:before, .loan-contentBox ul li p:after {
     background-position: -33px -50px;
     width: 17px;
     height: 16px;
     content: '';
     display: inline-block;
 }
 .loan-contentBox ul li p:after {
     background-position: -33px -65px;
 }
 .loan-testInfo {
     margin: 10px auto;
     width: 65%;
     font-size: 12px;
     line-height: 18px;
 }
 .loan-testInfo strong {
     display: block;
     font-size: 14px;
     font-weight: 600;
     margin-bottom: 4px;
 }
 .loan-btnWrap a.btn-disable {background: #e6e6e6;color: #333;cursor: default;  outline: 0;}
 @media only screen and (min-width: 768px){
 .loan-container {
     width: 1200px;
     margin: 0 auto;
 }
 .loanCont-widget:first-child {
     text-align: center;
 }
 .loanCont-widget {
     padding: 28px 12px 4px;
 }
 .loan-title {
     margin-bottom: 5px;
 }
 .edu-loanWidget {
     padding: 17px 0;
 }
 .edu-loanBox {
     margin: 2px 0;
 }
 .edu-loanBox>div p.edu-lnDiv {
     width: auto;
 }
 .loanCont-widget .widget-title {
     padding: 12px 16px;
 }
 .loan-contentBox.wht-spc {
     width: 100%;
 }
 .loan-contentBox {
     padding: 16px;
 }
 .loan-contentBox ul {
     width: 100%;
     min-width: auto;
 }
 .loan-contentBox ul li {
     width: 49%;
     height: auto;
     margin-right: 18px;
 }
 }
</style>
