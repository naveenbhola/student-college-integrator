<!--<link href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('collegeReviewWidget','nationalMobile'); ?>" type="text/css" rel="stylesheet" />-->
<style>
/*college selection widget*/
.selctnTool{padding: 15px 0px;width: 100%;overflow: hidden;}
.selctnTool .selctnToolInner{ width: 2000px;}
.selctnTool .selctnToolInner .slctnGird{ width: 246px; position: relative;background-size: contain; height: 128px; margin-right: 10px; float: left; display: inline-block;}
.selctnTool .selctnToolInner .slctnGird i{ position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; /* IE9 SVG, needs conditional override of 'filter' to 'none' */
background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIwIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjElIiBzdG9wLWNvbG9yPSIjZmNmY2ZjIiBzdG9wLW9wYWNpdHk9IjAiLz4KICAgIDxzdG9wIG9mZnNldD0iNDAlIiBzdG9wLWNvbG9yPSIjOTk5OTk5IiBzdG9wLW9wYWNpdHk9IjAiLz4KICAgIDxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iIzAwMDAwMCIgc3RvcC1vcGFjaXR5PSIwLjc1Ii8+CiAgPC9saW5lYXJHcmFkaWVudD4KICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2dyYWQtdWNnZy1nZW5lcmF0ZWQpIiAvPgo8L3N2Zz4=);
background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(252,252,252,0) 1%, rgba(153,153,153,0) 40%, rgba(0,0,0,0.75) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,0)), color-stop(1%,rgba(252,252,252,0)), color-stop(40%,rgba(153,153,153,0)), color-stop(100%,rgba(0,0,0,0.75))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(252,252,252,0) 1%,rgba(153,153,153,0) 40%,rgba(0,0,0,0.75) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(252,252,252,0) 1%,rgba(153,153,153,0) 40%,rgba(0,0,0,0.75) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(252,252,252,0) 1%,rgba(153,153,153,0) 40%,rgba(0,0,0,0.75) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(255,255,255,0) 0%,rgba(252,252,252,0) 1%,rgba(153,153,153,0) 40%,rgba(0,0,0,0.75) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#bf000000',GradientType=0 ); /* IE6-8 */}
.selctnTool .selctnToolInner .slctnGird span{ display: block; position:absolute; bottom:0px; left: 0px; right: 0px; padding:10px 10px; } 
.selctnTool .selctnToolInner .slctnGird h2{ font-size: 12px; color: #ffffff;  font-weight: bold;margin-bottom: 6px;
height: 12px;
overflow: hidden;}
.selctnTool .selctnToolInner .slctnGird h3{ font-size: 11px; font-weight: normal; color: #d2d2d2;height: 11px;}
#homepage_college_review_widget .content-wrap {
	margin: 0px 0px 10px 0px;
-moz-box-shadow: none;
-webkit-box-shadow:none;
box-shadow: none;
border-radius: 0px;
-moz-border-radius: 0px;
-webkit-border-radius: 0px;
}

.iimg0{ background-color: #000; background-repeat:  no-repeat; }
.iimg1{ background-color: : #000 ;background-repeat: no-repeat; }
.iimg2{ background-color: #000 background-repeat:no-repeat; }
.iimg3{ background-color: #000 background-repeat:no-repeat;background-attachment: scroll 0% 0%;}

/*college selection widget end*/
    
</style>
	<!--college selection tool-->
	<div data-enhance="false" id='homepage_college_review_widget'>  

            <section class="clearfix content-wrap">
               <?php 
                  $display = "none";
                  $height = "135px";
                  if($widgetForPage == "HOMEPAGE_MOBILE")
                  {  
                     $display = "block";
                     $height = "157px";
                  }
                  else if($widgetForPage == "CATEGORYPAGE_MOBILE" || $widgetForPage == "ARTICLEPAGE_MOBILE" || $widgetForPage == "RANKING_MOBILE"){
                     $display = "block";                     
                  }

               ?>
            	<header class="content-inner content-header clearfix" style='display:<?=$display?>'>
					<h2 class="title-txt" style="">Tools to decide your MBA College</h2>
                </header>
               <article class="selctnTool" style="position:relative;height:<?=$height?>">
               		<div class="selctnToolInner" style='position:absolute;left:-486px;'>

                        <div class="slctnGird iimg1" data-var="1" onclick="trackEventByGAMobile('<!--pageName-->_CAMPUS_CONNECT_WIDGET');window.location='<?php echo base_url() ?>mba/resources/campus-connect-program-1'">
                           <img style="height: 128px;width: 246px;" data-original="https://www.shiksha.com/public/mobile5/images/interlnk1.jpg" class="lazy"/>
                        <i></i>
                           <span><h2>Ask questions to current MBA students</h2>
                           <h3>Get honest answers on placement, faculty etc</h3></span>
                        </div>
                        <div class="slctnGird iimg2" onclick="trackEventByGAMobile('<!--pageName-->_CAREER_COMPASS_WIDGET');window.location='<?php echo SHIKSHA_HOME;?>/mba/resources/mba-alumni-data'">
                           <img style="height: 128px;width: 246px;" src="" data-original="https://www.shiksha.com/public/mobile5/images/interlnk3.jpg" class="lazy">
                        <i></i>
                           <span><h2>Want your Dream Job after MBA?</h2>
                           <h3>Find colleges to help you get into top cos.</h3></span>
                        </div>

               			<div class="slctnGird iimg0" onclick="trackEventByGAMobile('<!--pageName-->_COLLEGE_REVIEW_WIDGET');window.location='<?php echo base_url()?><?= MBA_COLLEGE_REVIEW ?>'">
                           <img style="height: 128px;width: 246px;" class="lazy" data-original="https://www.shiksha.com/public/mobile5/images/interlnk2.jpg"/>
               			<i></i>
               				<span><h2>Read thousands of MBA college reviews</h2>
               				<h3>For over 500+ colleges across India</h3></span>
               			</div>   

                        <div class="slctnGird iimg1" onclick="trackEventByGAMobile('<!--pageName-->_CAMPUS_CONNECT_WIDGET');window.location='<?php echo base_url() ?>mba/resources/ask-current-mba-students'">
                           <img style="height: 128px;width: 246px;" data-original="https://www.shiksha.com/public/mobile5/images/interlnk1.jpg" class="lazy"/>
                        <i></i>
                           <span><h2>Ask questions to current MBA students</h2>
                           <h3>Get honest answers on placement, faculty etc</h3></span>
                        </div>
                        <div class="slctnGird iimg2" onclick="trackEventByGAMobile('<!--pageName-->_CAREER_COMPASS_WIDGET');window.location='<?php echo SHIKSHA_HOME;?>/mba/resources/mba-alumni-data'">
                           <img style="height: 128px;width: 246px;" src="" data-original="https://www.shiksha.com/public/mobile5/images/interlnk3.jpg" class="lazy"/>
                
                        <i></i>
                           <span><h2>Want your Dream Job after MBA?</h2>
                           <h3>Find colleges to help you get into top cos.</h3></span>
                        </div>

                        <div class="slctnGird iimg0" onclick="trackEventByGAMobile('<!--pageName-->_COLLEGE_REVIEW_WIDGET');window.location='<?php echo base_url()?><?= MBA_COLLEGE_REVIEW ?>'">
                           <img style="height: 128px;width: 246px;" data-original="https://www.shiksha.com/public/mobile5/images/interlnk2.jpg" class="lazy" src="">
                        <i></i>
                           <span><h2>Read thousands of MBA college reviews</h2>
                           <h3>For over 500+ colleges across India</h3></span>
                        </div>
                       
               		</div>
               		<p class="clr"></p>
               </article> 
            </section>
	</div>	
	<!--college  selection tool ends-->
