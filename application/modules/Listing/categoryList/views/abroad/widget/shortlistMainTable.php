<div class="shortlt-lt-tab flLt">
   <ul class="shortlt-list">
	  <li class="rmcTab active"><a href="Javascript:void(0);" onclick="switchShortlistTabs('rmcTuples',this);">Rate My Chances <span class="cntlist">(<?=($RMCCourseAndUnivObjs['totalCount']>0?$RMCCourseAndUnivObjs['totalCount']:0)?>)</span><i class="common-sprite shortlt-pointer"></i></a></li>
	  <li class="shortlistTab"><a href="Javascript:void(0);" onclick="switchShortlistTabs('shortlistTuples',this);">My Saved Courses <span class="cntlist">(<?=($courseAndUnivObjs['totalCount'])?>)</span><i class="common-sprite shortlt-pointer"></i></a></li>
	  <!--<li><a href="#">Recommendations <span class="cntlist">(6)</span><i class="common-sprite shortlt-pointer"></i></a></li>-->
   </ul>
</div>
<!-- shortlist right panel  -->
<div class="shortlt-tuple shortlistTuples clearwidth flRt" style="display:none;">
   <div id="course-title" style="margin-bottom:10px;">
	  <h1>My Saved Colleges</h1>
   </div>
   <div id ="zero-result-shrtlist">
   <?php
   if(empty($courseAndUnivObjs['courses'])) {?>
	  <div  class="zero-result clearwidth">
		 <h2></h2>
		 <p>You have not saved any college.</p>
		 <p>Create a personalized list of colleges that you are interested in by saving. Click the STAR button to add to my saved courses.</p>
	  </div>
   <?php
   }	?>
   </div>
   <?php $this->load->view("/categoryList/abroad/widget/categoryPageShortlistedListings"); ?>
</div><!-- end right panel-->

<div class="shortlt-tuple rmcTuples clearwidth flRt">
   <div id="course-title" style="margin-bottom:10px;">
	  <h1>Rate My Chance Colleges</h1>
   </div>
   <div id ="zero-result-rmc">
   <?php 
	  if(empty($RMCCourseAndUnivObjs['courses'])) {?>
		 <div  class="zero-result clearwidth">
			<h2></h2>
			<p>Your chances have not been rated on any college.</p>
			<p>Click the Rate my chance button next to a course to get started.</p>
		 </div>
   <?php
	  }	?>
   </div>
   <script>
		var rmcLimitOffset = 0;
   </script>
   <?php $this->load->view("/categoryList/abroad/widget/rateMyChanceTuples"); ?>
</div><!-- end right panel-->
   
   
