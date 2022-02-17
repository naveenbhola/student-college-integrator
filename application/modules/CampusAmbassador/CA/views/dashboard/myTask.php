<?php
$data['headerTitle'] = 'Campus Connect - My Task - '.ucwords($taskType);
$this->load->view('campusRepHeader',$data);?>
<script>var makeAnswerAjax = true;</script>
    <div id="campus-dashboard-wrap">
	<div class="confirm-layer clearfix" style=display:none;"" id="popupBasic">
	    <div class="title">Confirm!</div>
	    <a href="javascript:void(0);" class="close-layer" id="confirmLayerCloseMark">&times;</a>
	    <p>You will be not able to make any more submissions for this task. Do you want to end this task?</p>
	    <div class="clear-width">
	    <a class="submit-btn" href="javascript:void();" id="confirmLayerYesButton">Yes</a> &nbsp; 
	    <a class="submit-btn reply-btn" href="javascript:void();" id="confirmLayerNoButton">No</a>
	    </div>
	</div>
	<div id="popupBasicBack">	
	</div>
    	<div class="dashboard-header">
		<i class="icons ic_logo"></i>
	</div>
        
	
	<div id="dashboard-left">
	<?php
	echo Modules::run('CA/CRDashboard/getProfile');
	// $this->load->view('dashboard/mywalletWidget');
	?>
	<!------------------------>
	</div>
        
	
        <div id="dashboard-right">
        
	<!----------Notification---------->
        <?php echo Modules::run('CA/CRDashboard/getNotificationWidget');?>
        <!-------------------------------->
            
        <div class="dashboard-content clearfix" id="mainPage">
            	
		
	<!---------Page-Tab------->
        <?php 
        echo Modules::run('CA/CRDashboard/getPageTabWidget',$pageName);
	?>
        <!-------------------------------->
                

        <!-----------Task-Page-------->
        <?php
        $this->load->view('dashboard/myTaskInnerPage');?>
        <!-------------------------------->

	  <?php if(isset($totalQuestion) && $totalQuestion >10){?>
	  <div class="load-more-sec clear-width" style="text-align: center; font-size:14px;">
	    <a href="javascript:void(0);" id="loadMoreCRBtn" class="loadMoreCRBtn">Show More Questions</a>
	  </div>
	  <?php }?>
	  
        </div>
     </div>
 </div>
 <?php $this->load->view('common/footer');?>   
<script>
$j('#footer').hide();

$j('#content-wrapper').css({'background':'none'});
$j('body').css({'background':'#dfe7ec'});
$j('.wrapperFxd').css({'background':'#dfe7ec'});


var mainPage = $j('#notification').offset().top;
$j(window).scroll(function(){
var maxh = $j(this).scrollTop();
if(maxh >= mainPage) { 
	$j('#dashboard-left').css({'position':'fixed','top':'0px'});
}else{ 
	$j('#dashboard-left').css({'position':'relative','top':'0px'});
}
});
</script>

</body>
</html>
