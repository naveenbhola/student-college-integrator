
<div class="" id="floatingRegistrationOpen" style="display:none;z-index:100; position:fixed;">
    <div class="connect-widget">
	<h4 class="connect-widget-title">Connect with current students<br /> of this college
    <a href="javascript:void(0);" onclick="hidePopup()" class="close-widget">&times;</a>
    </h4>
    <?php $defaultInsImage = '/public/images/avatar.gif';?>
	    
    	<?php if(count($data['data']) > 0): 	?>
	    	<?php 
	    			$caData = $data['data'][0];
	    	?>
	    	<?php 
	    	     
	    		  $image = $caData['imageURL'];
	    		  $displayname = $caData['displayName'];
	    		  if($caData['badge']=='CurrentStudent'){
	    			$badge = 'Current Student';
	    		  }
	    		 else if($caData['badge']=='Alumni'){
	    			$badge = 'Alumni';
	    			}
	    		else{
	    			$badge = 'Official';
	    		}
	
	    		if($image==''){
	    			$image = '/public/images/photoNotAvailable.gif';
	    		}
	    		
	    	?>
		<?php elseif($data['instituteRep'] == 'true') :?>
		<?php 
				$image = $institute->getMainHeaderImage()->getThumbURL();
				$badge = 'Official';
				$locations = $course->getLocations();
				$location = $locations[$currentLocationId];
				$contactDetail = $location->getContactDetail();
				if($contactDetail->getContactPerson()){
					$displayname = $contactDetail->getContactPerson();
				}else {
					$locations = $institute->getLocations();
					$location = $locations[$currentLocationId];
					$contactDetail = $location->getContactDetail();
					if($contactDetail) {
						$displayname = $contactDetail->getContactPerson();
					}else {
						$displayname = "Academic Counsellor";
					}
						
				}				
				
				if($image==''){
					$image = $defaultInsImage;
				}				

		?>
		<?php else:?>
		<?php 
					$image = $institute->getMainHeaderImage()->getThumbURL();
					$displayname = 'Academic Counsellor';
					$badge = 'Official';
					if($image==''){
						$image = $defaultInsImage;
					}					
		?>
		<?php endif;?>		        

	    <div class="widget-content clear-width">
	    	<div class="figure-cloud"><img src="<?php echo $image;?>" height="52" width="58" alt="" /></div>
		 <p style="margin-bottom: 5px; font-weight: bold"><?php echo $displayname;?></p>
	        <p><span class="blue-btn" style="margin-bottom: 8px;"><?php echo $badge;?></span><br/>Just share your queries about this college and get all your answers from our panel of current students</p>
	    </div>  
	      <div class="tac" style="margin:0px"><button class="orange-button" onclick="participate('<?php echo $courseId?>')" >Ask your Question<span class="btn-arrow"></span></button></div>
		
</div>
</div>
	
<div class="connect-widget"  onClick="showFloatingRegistration();" id="floatingRegistrationClosed" style="display:none;z-index:100; position:fixed;">

	<div class="flLt" style="width:250px;padding-top:10px;">
    	<strong><span id="closeHeading" style="width:300px; word-wrap:break-word;">Talk to a current student of this college</span></strong>
        <p><span id="closeLine"><?php echo $closeLine;?></span></p>
    </div>
    <div class="closed-wid-icn"></div>
    <div class="clearFix"></div>
</div>

<script>
var showAsk = '<?=$showAsk?>';
var a = $j('#<?php echo $rightColId;?>');
var isLefthandled = true;
if(window.innerWidth){
        if(window.innerWidth<=1024){
		$('floatingRegistrationOpen').style.right = '0px';
		$('floatingRegistrationClosed').style.right = '0px';
		isLefthandled = false;
	        //var leftMargin = (a.offset().left-213);
	}
        else{
        var leftMargin = a.offset().left;
       	if(leftMargin == '863') {
			leftMargin = '850';
        }
		$('floatingRegistrationOpen').style.left = leftMargin+'px';
		$('floatingRegistrationClosed').style.left = leftMargin+'px';
	}
}
else{
        if(screen.availWidth<=1024){
                //var leftMargin = (a.offset().left-213);
		$('floatingRegistrationOpen').style.right = '0px';
		$('floatingRegistrationClosed').style.right = '0px';
		isLefthandled = false;
	}
        else{
                var leftMargin = a.offset().left;
		$('floatingRegistrationOpen').style.left = leftMargin+'px';
		$('floatingRegistrationClosed').style.left = leftMargin+'px';
	}
}

$('floatingRegistrationOpen').style.bottom = '0px';
$('floatingRegistrationClosed').style.bottom = '0px';

if('<?=$showAsk?>'=='true'){
//	$('floatingRegistrationClosed').style.width = '272px';
//	$('floatingRegistrationOpen').style.width = '330px';
}

//Start: Code for animation on the FLoating registration widget
var animateOnce = true;
function animateWidget(){
	if(animateOnce){
		animateOnce = false;
		if(isLefthandled){
			var leftMarginOfWidget = currentShown.style.left;
			elemLeft = leftMarginOfWidget.split('px');
			finalLeft = elemLeft[0];
			currentShown.style.left = (Number(finalLeft)+Number(widthAnimated)) + 'px';
			marginNow = finalLeft;
			startAnimation();
		}
		else{
			var rightMarginOfWidget = currentShown.style.right;
                        elemRight = rightMarginOfWidget.split('px');
                        finalRight = elemRight[0];
                        currentShown.style.right = (finalRight-widthAnimated) + 'px';
			marginNow = finalRight;
			direction = 'right';
			startAnimation();
		}
	}
}

var widthAnimated = 200;
var totalMargin = 200;
var direction = 'left';
var marginNow = 0;

function startAnimation(){
	if(totalMargin<=0){
		return true;
	}
	if(direction=='left')
		currentShown.style.left = (Number(marginNow) + (Number(totalMargin)-5) ) + 'px';
	else
		currentShown.style.right = (Number(marginNow) - (Number(totalMargin)-5) ) + 'px';
	totalMargin = Number(totalMargin)-5;
	setTimeout('startAnimation()', 1);
}
//End: Code for animation on the FLoating registration widget

//Start: Code to Display the FLoating registration widget after some widget. This top limit will be decided by displayAfter
var displayAfter = <?php echo $displayAfter;?>;
var floatingDivClosed = $('floatingRegistrationClosed');
var floatingDivOpen = $('floatingRegistrationOpen');
if('<?=$floatingRegisterWidgetClosed?>'=='true'){
	var currentShown = floatingDivClosed;
}
else{
	var currentShown = floatingDivOpen;
}

function isScrolledIntoView() {
   var docViewTop = $j(window).scrollTop();
   if(docViewTop<displayAfter){
	return 'none';
   }
   else{
	return '';
   }
}

$j(window).scroll(function() {
	var displayVal = isScrolledIntoView();
	checkFloatingWidgetStatus(displayVal);
});

function checkFloatingWidgetStatus(displayValue){
        currentShown.style.display = displayValue;
        if( $('floatingRegistrationClosed').style.display == 'block') {
        	$('floatingRegistrationOpen').style.display = 'none';
        }
        if(displayValue==''){
                animateWidget();
        }
}

if( displayAfter == '' || displayAfter == 0 ){
	currentShown.style.display = '';
	animateWidget();
}
else if( displayAfter == '-1' ){
        currentShown.style.display = 'none';
}

var displayVal = isScrolledIntoView();
checkFloatingWidgetStatus(displayVal);

//End: Code to Display the FLoating registration widget after some widget.

function checkCaptchaDisplay(){
    if($('captchaDisplayFloatingRegistration'))
    $('captchaDisplayFloatingRegistration').style.display = 'block';
    if($('ask_question_<?=$widget?>'))
        $('ask_question_<?=$widget?>').style.height = '40px';
}

var isLDBUserFloating = '<?php echo $isLDBUser;?>';

function hidePopup() {
	$('floatingRegistrationOpen').style.display = 'none';
	$('floatingRegistrationClosed').style.display = 'block';
	
}
</script>

