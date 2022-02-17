<section class="clearfix content-wrap" data-enhance="false" style="margin:0; box-shadow:none; border-radius:none;">
      <div class="dream-info-widget">
        <div class="flLt" style="width:80%;">
        	<p>Have a dream job?</p> 
			<p>Find colleges to help you get there!</p>
        </div>
         	<p class="flRt source-text">Data Source:
		<br/>
		<i class="msprite naukri-sml-logo2"></i>
		</p>
         <div class="clearfix"></div>
         <p style="margin-top:8px;">Select a job function/company by tapping on the chart below to find colleges whose alumni work there</p>
        </div>
        <?php $this->load->view('topDoughnutCharts');?>
        <div class="filter-widget">
	    <p id="default-load-msg">Updating the college list...</p>
	    <strong id="custom-load-msg" style="display: none; font-weight: normal;">We have found <span id="totalCount">0</span> colleges with Alumni Information</strong>
        </div>
	<div class="clearfix"></div>
	<div id="initial-colleges-list">
	  <div style="text-align: center; margin-top: 7px; margin-bottom: 10px;">
	    <img border="0" alt="" id="loadingImage" src="//<?php echo IMGURL; ?>/public/mobile5/images/ajax-loader.gif">
	  </div>
	</div>

	<?php if(!is_array($validateuser)){ ?>
        <section class="content-section" id="register-widget" style="padding-top:0">

        <div class="naukri-register-widget" onclick="registrationForm.showRegistrationForm({'trackingKeyId': '312'});">
        	<div class="naukri-register-head">
               <strong>Looking for the right college?</strong>
            	<span>Register on Shiksha.com to know your best options</span> 
            </div>
            <span class="for-text">For</span>
            <ul class="register-info-list">
            	<li><i class="msprite tick-icon"></i>Personalised college recommendations</li>
                <li><i class="msprite tick-icon"></i>College rankings</li>
                <li><i class="msprite tick-icon"></i>Alerts on exams & application deadlines</li>
            </ul>
            <a href="javascript:void(0);" class="register-btn">Register Now</a>
        </div>
        </section>
	<?php } ?>

	<div id="more-colleges-list"></div>
	<div style="text-align: center; margin-top: 7px; margin-bottom: 10px; display: none;" id="more-colleges-loading">
	  <img border="0" alt="" id="loadingImage" src="//<?php echo IMGURL; ?>/public/mobile5/images/ajax-loader.gif">
	</div>

	<!---<div id="naukri-widget-right-col" data-enhance="false"  data-role="page">
	</div>-->
	
