<div id="cpSubHeader" class="<?php if(!$resultPageFlag){ echo " hid";}?>">
	<div class="head-group  " data-enhance="false">
       <div class="title-text" id="headingDiv" style="margin: 0; display: table-cell">
	   <div style="margin-right:10px;margin-left: 12px;">
	       <h1 style="text-align:left;padding: 0.2em 0" title="<?=$pageHeading?>">
		   <?php echo displayTextAsPerMobileResolution($pageHeading,2,true);?>
	       </h1>
	       <p id="totalResultsSection" style="display:none; color: #A07F13; font-size: 0.9em;"></p>
	   </div>
       </div>
       
       
	<!---------------mylists----------------->
       
       <?php //$this->load->view('/mcommon5/mobileMyList');?>
       
       <!------------end-mylists---------------->
       
       
       <div class="head-filter" id="showFilterButton" style="display: none;margin:6px 1px 0 0; position:static;float: right">
		       <a id="examFilterOverlayOpen" href="#examsFilterDiv" data-inline="true" data-rel="dialog" data-transition="slide" onclick="searchCall = 'false';hideEmail();trackEventByGAMobile('HTML5_JEECollegePredictor_Filter');">
			       <i class="icon-busy" aria-hidden="true"></i>
			       <p>Filter</p>
		       </a>
	       </div>
       </div>
</div>       