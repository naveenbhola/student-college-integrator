<?php $ownerImageURL = ($discussionDetails['avtarimageurl']=='')?'/public/images/photoNotAvailable.gif':$discussionDetails['avtarimageurl']; ?>
<div class="find-inst-cont" id="floatingRegistrationOpen" style="display:none;z-index:100;background-image: none;width:310px;">
	<!--STARTS : Current Student Widget-->
	<div id="current-student-widget">
		<div class="flRt"><a href="javascript:void(0)" onClick="hideFloatingRegistration();" class="close-widget">&nbsp;</a></div>
		<h5><?php echo $headingText;?></h5>
		<div class="clearFix"></div>
		<div class="current-stdnt-picBg">
		    <div class="student-pic"><img src="<?php echo getSmallImage($ownerImageURL);?>" alt="" /></div>
		    <div class="current-student-patch">Current Student</div>
		</div>
		<div class="widget-details">Want to know more about this institute?<br/>Talk to a current student who will help you in clearing your doubts.</div>
		<div class="spacer10 clearFix"></div>
		<div class="tac">
			<input type="button" value="Participate in the discussion" class="orange-button" onClick="trackEventByGA('LinkClick','LISTING_FLOATING_PARTICIPATE_DISCUSSION_CLICK'); window.open('<?php echo $discussionDetails['url'].'#questionMainDiv';?>');"/>
		</div>
	</div>
	<!--ENDS : Current Student Widget-->
</div>

<div class="closed-wid"  onClick="$('floatingRegistrationOpen').style.display = '';$('floatingRegistrationClosed').style.display = 'none';currentShown = floatingDivOpen;setCookie('floatingRegisterWidgetClosed','false',0);" id="floatingRegistrationClosed" style="display:none;z-index:100;">
	<div class="flLt" id="current-student-widget">
	   <h5 style="width:84%"><span id="closeHeading"><?php echo $closeHeading;?></span></h5>
           <div class="closed-wid-icn"></div>
        </div>
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

var isLDBUserFloating = '0';

</script>

