<?php
	if(!isset($product)){
		$product = 'entire';
	}

	$questionText = '';
	if(isset($_COOKIE['commentContent']) && ($questionText == '')){
		$questionText = $_COOKIE['commentContent'];
		if((stripos($questionText,'@$#@#$$') !== false) || (stripos($questionText,'@#@!@%@') !== false)){
			$questionText = '';
		}
	}
	$questionText = str_replace('"','&quot;',$questionText);
?>

<script>
function MM_showHideLayers1() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers1.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
  if(v=='hidden')
  {
    document.getElementById('iframe_div').style.display = 'none';
  }
}
function addQuestionToCookie(){
	setCookie('commentContent',document.getElementById('questionText').value,0);
	return;
}
</script>

<?php 
		$questionText = (isset($questionText)&&($questionText!=''))?$questionText:'Ask your education related question here and get answers from Shiksha experts.';
		$questionTextLength = strlen($questionText);
		$base64url = base64_encode(site_url("'".$_SERVER['REQUEST_URI']."'"));
?>

<!--Start_Ask_Question-->
<div class="wdh100">
	<div class="raised_11">
		<b class="b2"></b><b class="b3" style="background:#fff"></b><b class="b4" style="background:#fff"></b>
		<div class="boxcontent_11" id="askQuestionFormDiv" style="background:#fff url(/public/images/midAnA.jpg) repeat-x left bottom">
			<form id="askQuestionForm" name="askQuestionForm" method="get" action="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/questionPostLandingPage" onsubmit="try{return validateQuestionForm_old(this,'<?php echo $postQuestionKey; ?>','formsubmit','askQuestionForm');}catch (e){ return false;}" style="margin:0;padding:0">
			<div class="lineSpace_5">&nbsp;</div>
			<div class="mlr10">
				<div class="aqImg Fnt14 bld">Ask a Question</div>
				<div class="aqTxBx" id="questionTextDiv">
					<div >
						<textarea class="tbx" name="questionText" id="questionText" autocomplete="off" value="<?php echo $questionText; ?>" onblur="enterEnabled=false;" onfocus="try{ showAskQuestionForm();enterEnabled=true; }catch (e){}" onkeyup="try{ textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="Question" maxlength="300" minlength="2" required="true" style="height:15px;color:#a8a7ac;"><?php echo $questionText; ?></textarea>
						<input name="referalUrlForAskQuestionFromHeader" id="referalUrlForAskQuestionFromHeader" type="hidden" value="<?php echo $base64url; ?>"/>
					</div>
				</div>
				<div class="clear_B">&nbsp;</div>
				<div style="display:none" id="askQuestionFormHidden" >
					<div class="float_L">
					  <div class="Fnt10 pdl140 fcdGya"><span id="questionText_counter">0</span> out of 300 characters</div>
					  <div class="pdl140 row errorPlace"><span id="questionText_error" class="errorMsg">&nbsp;</span></div>
					  <div class="pdl140 lineSpace_20">Average time to get an answer is <span style="padding:0 3px;border:1px solid #e2e2e2;background:#fff"><?php echo $averageTimeToAnswer; ?>:00 Mins</span></div>
					</div>
					<div class="float_R">
						<div class="mtb5">
							<input type="submit" class="anaBn" value="&nbsp;" style="cursor:pointer;"/>
						</div>
					</div>
					<div class="clear_B">&nbsp;</div>       
				</div>
			</div>
			</form>
		</div>
		<b class="b4b" style="background:#fff3bf"></b><b class="b3b" style="background:#fff3bf"></b><b class="b2b" style="background:#fff3bf"></b><b class="b1b"></b>
	</div>
</div>
<!--End_Ask_Question-->
<div class="lineSpace_10">&nbsp;</div>
