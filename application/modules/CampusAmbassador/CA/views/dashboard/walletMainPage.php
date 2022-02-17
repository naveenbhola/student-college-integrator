<?php $data['headerTitle'] = 'Campus Connect - Mywallet';
$this->load->view('campusRepHeader',$data);?>
<?php

if($earning['count']['approvedCount']>0){
	$aprCount = $earning['count']['approvedCount'];
	}else{
	$aprCount = 0;
	}
if($earning['count']['featuredCount']>0){
	$ftrCount = $earning['count']['featuredCount'];
	}else{
	$ftrCount = 0;
	}	
$approvedGraph = (($aprCount*100)/$totalCRQuestion);
$featuredGraph = (($ftrCount*100)/$totalCRQuestion);
?>

<script src="http://cdn.crunchify.com/wp-content/uploads/code/knob.js"></script>
    <div id="campus-dashboard-wrap">
    	<div class="dashboard-header">
		<i class="icons ic_logo"></i>
	</div>
        
	
	<div id="dashboard-left">
	<!-----------Dashboard-Left----------->
	<?php
	echo Modules::run('CA/CRDashboard/getProfile');
	$this->load->view('dashboard/mywalletWidget');
	?>
	<!------------------------>
	</div>
        
	
        <div id="dashboard-right">
        
	<!----------Notification---------->
        <?php echo Modules::run('CA/CRDashboard/getNotificationWidget');?>
        <!-------------------------------->
       
       <div class="dashboard-content clearfix">
            	
              <div class="wallet-head clear-width">
              	<span class="flLt">MY WALLET</span>
              	<span class="flRt">TOTAL AMOUNT PAID : &#8377; <?php if($totaPaid>0){echo $totaPaid;}else{echo 0;}?></span>
              </div>  
              <div class="earning-section clear-width">
              	<div style="padding-left:0" class="earning-col">
                	<p>CURRENT  EARNINGS</p>
                    <p class="wallet-amount">&#8377; <strong><?php if($earning['totalEarn']>0){echo ($earning['totalEarn']-$totaPaid);}else{echo 0;}?></strong></p>
                </div>
                <div class="earning-col">
                	<p>PENDING APPROVAL</p>
                    <p class="wallet-amount">&#8377; <strong><?php if($earning['totalPending']>0){echo $earning['totalPending'];}else{echo 0;}?></strong></p>
                </div>
                <div class="earning-col last">
                	<p>POTENTIAL EARNINGS UPTO</p>
                    <p class="wallet-amount">&#8377; <strong><?php if($potentialEarn>0){echo $potentialEarn;}else{echo 0;}?></strong> <span>MORE FROM (<span style="color:#ee7a55;"><?php if($totalUnans){echo $totalUnans;}else{echo 0;}?></span>) UNANSWERED </span></p>
                </div>
              </div> 
              <div class="wallet-widget clear-width">
              	<p>TOTAL EARNINGS FROM ANSWER</p>
                
              <div class="widget-graph">
			<div class="widget-col flLt">
			<div style="width:125px; float:left"><input class="knob  animated" value="0" rel="<?php echo $approvedGraph;?>" data-width="125" data-height="125" data-displayInput="false" data-readOnly="true" data-thickness=.2 data-fgColor="#ff6666"></div>
			<div class="earning-details">
			<p><i class="campus-sprite ans-icon2"></i></p>
			<p><strong><?php echo $aprCount;?></strong> APPROVED ANSWERS OUT OF </p>
			<p style="margin-bottom:0"><strong><?php echo $totalCRQuestion;?></strong> QUESTIONS</p>
			<p class="wallet-amount" style="font-size:36px; line-height:30px">&#8377; <?php if($earning['totalApproved']>0){echo $earning['totalApproved'];}else{echo 0;}?></p>
			</div>
			</div>
			<div class="widget-col flRt">
			<div style="width:125px; float:left"><input class="knob  animated" value="0" rel="<?php echo $featuredGraph;?>" data-width="125" data-height="125" data-displayInput="false" data-readOnly="true" data-thickness=.2 data-fgColor="#50c382"></div>
			<div class="feature-details">
			<p><i class="campus-sprite featured-icon2"></i></p>
			<p><strong><?php echo $ftrCount;?></strong> FEATURED ANSWERS OUT OF </p>
			<p style="margin-bottom:0"><strong><?php echo $totalCRQuestion;?></strong> QUESTIONS</p>
			<p class="wallet-amount" style="font-size:36px; line-height:30px">&#8377; <?php if($earning['totalFeatured']>0){echo $earning['totalFeatured'];}else{echo 0;}?></p>
			</div>
			</div>
	      </div>
              </div>
	      <div style="font-size:13px; color:#666; margin-bottom:15px;" class="clear-width">Your Wallet will be activated and you will start earning money for questions asked Sept 3rd onwards or your date of onboarding, whichever is later. </div>
	      
	      <!---------Task-View--------->
	      
	      <?php if(count($task)>0){
		
		?>
	      <div style="margin-bottom:0" class="wallet-widget clear-width">
              	<p class="mb15">TOTAL EARNINGS FROM TASKS</p>
                <div class="task-graph">
                <ul>
                	
		
		<?php
		$t=1;
		$bigEarn = $task[0]->reward;
		foreach((object)$task as $key=>$task){
		$taskGraph = (($task->reward*100)/$bigEarn).'%';
		  ?>
			
		<li id="task_<?php echo $task->id;?>">
		    <div class="task-label flLt"><?php echo $t;?>. <?php echo ucwords($task->name);?></div>
		    <div class="task-track">
			    <div style="width:<?php echo $taskGraph;?>;" class="task-percent"></div>
		    </div>
		    <div class="task-amount flLt">&#8377; <?php echo $task->reward;?></div>
		</li>
		    
		<?php $t++;}?>  
                </ul>
                </div>
              </div> 
	      <?php }?>
	      
              <!------------------------------------->
	      
           </div>   
     </div>
 </div>
<script>
  $j('.knob').each(function () {

     var $jthis = $j(this);
     var myVal = $jthis.attr("rel");
     $jthis.knob({

     });
     $j({
         value: 0
     }).animate({

         value: myVal
     }, {
         duration: 2000,
         easing: 'swing',
         step: function () {
             $jthis.val(Math.ceil(this.value)).trigger('change');

         }
     })

});
</script>

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

