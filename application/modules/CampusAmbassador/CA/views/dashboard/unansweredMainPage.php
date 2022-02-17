<?php
$data['headerTitle'] = 'Campus Connect - Answer - Unanswered';
$this->load->view('campusRepHeader',$data);?>
    <div id="campus-dashboard-wrap">
    	<div class="dashboard-header">
		<i class="icons ic_logo"></i>
	</div>
        
	
	<div id="dashboard-left">
	<?php
	   echo Modules::run('CA/CRDashboard/getProfile');
	// $this->load->view('dashboard/mywalletWidget');
	?>
	</div>
        
	
        <div id="dashboard-right">
        
	<!----------Notification---------->
        <?php echo Modules::run('CA/CRDashboard/getNotificationWidget',array('unansweredQCount'=>$result['total']));?>
        <!-------------------------------->
            
        <div class="dashboard-content clearfix" id="mainPage">
            	
		
	<!---------Page-Tab-and-Count-View------>
        <?php 
        echo Modules::run('CA/CRDashboard/getPageTabWidget',array('pageName'=>$pageName,'unansweredQCount'=>$result['total'],'unansweredQCount'=>$result['crData']['courseId']));
	     $this->load->view('dashboard/pageCountWidget');
	?>
        <!-------------------------------->
                
          <ol class="ans-earn-section" id="question_listing_main">

        <!-----------Question-Page-------->
        <?php $this->load->view('dashboard/questionPage');?>
        <!-------------------------------->

          </ol>
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
$j('.reply_all_btn:first').hide();
$j('.cmnt_box_all:first').show();
$j('#content-wrapper').css({'background':'none'});
$j('body').css({'background':'#dfe7ec'});
$j('.wrapperFxd').css({'background':'#dfe7ec'});
<?php if(isset($totalQuestion) && $totalQuestion < 11){?>
$j('#question_listing_main li:last').attr('class','last');
<?php }?>
var mainPage = $j('#notification').offset().top;
$j(window).scroll(function(){
var maxh = $j(this).scrollTop();
if(maxh >= mainPage) { 
	$j('#dashboard-left').css({'position':'fixed','top':'0px'});
}else{ 
	$j('#dashboard-left').css({'position':'relative','top':'0px'});
}
});

// show load more questions

<?php $total_pages = ceil($totalQuestion/$item_per_page);?>
var track_click = 1; //track user click on "load more" button, righ now it is 0 click

var total_pages = <?php echo $total_pages; ?>

$j("#loadMoreCRBtn").click(function (e) { 
    $j(this).hide(); 
    if(track_click <= total_pages) 
    {  
	    $j.post('/CA/CRDashboard/getCRUnansweredTab',{'page': track_click}, function(data) {
		if (data !='')
		{
		    track_click++;
		    $j("#loadMoreCRBtn").show(); 
		    $j("#question_listing_main").append(data); 
		    if (total_pages == track_click) {
			$j("#loadMoreCRBtn").hide();
			$j('#question_listing_main li:last').attr('class','last');
		    }
		}
	    }).fail(function(xhr, ajaxOptions, thrownError) { 
		    console.log(thrownError);
		    $j("#loadMoreCRBtn").show();
	    });
     }
});
</script>

</body>
</html>

