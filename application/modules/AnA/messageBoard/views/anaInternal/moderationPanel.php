<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html >
<head>
				<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="content-language" content="EN" />
				<meta name="author" content="www.Shiksha.com" />
				<meta name="resource-type" content="document" />
				<meta name="distribution" content="GLOBAL" />
				<meta name="rating" content="general" />
				<meta name="pragma" content="no-cache" />
				<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
				<title>Shiksha ANA Moderation Tool</title>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
				
				<!-- LABJs utility loaded in parallel-->
<script type="text/javascript">
  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
  function f(){ loadJsFilesInParallel();}
  H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
  a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);
  if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
</script>

				<script type="text/javascript">
  function loadJsFilesInParallel(){
	$LAB
	.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>")
	.wait(function(){
		if(SHOW_AUTOSUGGESTOR_JS){
		autosuggestorInstanceCheck = setInterval(function(){
			var fileLoaded = false;
			try{
				var aso = new AutoSuggestor();
				fileLoaded = true;
			} catch(e) {
				fileLoaded = false;
			}
			if(fileLoaded){
				clearInterval(autosuggestorInstanceCheck);
				if(typeof(initializeAutoSuggestorInstance) == 'function') {
                    initializeAutoSuggestorInstance();
                }
				if(typeof(initializeAutoSuggestorInstanceAlt) == 'function') {
                    initializeAutoSuggestorInstanceAlt();
                }
			}
		},1000);
	  }
	});
  }
</script>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('campusAmbassador'); ?>"></script>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('CAValidations'); ?>"></script>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
				<script>
					$j = jQuery.noConflict();
				</script>
				<style>
				ul.suggestion-box{ border:1px solid #b3b3b3; position:absolute;background:#fff; z-index:10; top:36px; box-sizing:border-box;-moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
				ul.suggestion-box li{margin-bottom:0; border-bottom:1px solid #e5e5e5;color:#121212; padding:8px 0 2px 6px; font-size: 0.8em}
				ul.suggestion-box li:last-child{border-bottom:none;}
				.suggestion-box{-webkit-box-shadow: 0px 0px 2px 0px #b3b3b3; -moz-box-shadow: 0px 0px 2px 0px #b3b3b3; box-shadow: 0px 0px 2px 0px #b3b3b3; padding:10px; font-family:Tahoma, Geneva, sans-serif; font-size:12px; border:1px solid #dfdfdf; background:#FFF; text-align:left}
				.suggestion-box-active-option , .suggestion-box-normal-option  {padding:4px 5px; background-color:#dff1ff; color:#000000; line-height:17px}
				.suggestion-box-normal-option {background:#FFF;}
				
				
				ul.approve-list{float:left; padding-left:0; width:100%;}
				ul.approve-list li{list-style:none; width: 100%; float: left;}
				ul.approve-list li span{float:left;}
				</style>
</head>


<body id="mainWrapper">
<div id="appsFormWrapper">

				<div style="font-size:20px;margin-bottom: 20px;"><strong>ANA Moderation Panel</strong></div>
				
<form  id="dataForm" method="post" autocomplete="off" accept-charset="utf-8" action="/messageBoard/MessageBoardInternal/moderationPanel" onsubmit="return false;">
<table border=0 width="98%" align="center" style="border:1px solid;">
<tr>
<th style="text-align:left" colspan="100">
<div style="margin: 10px;"><strong>Please fill atleast one of the below fields:</strong></div>
</th></tr>
<tr>
<td align="right" width="30%">
<div style="margin: 10px;">From Date (yyyy-mm-dd): </td><td><input type="text" style="width:200px;" name="date" id="date" value="<?=$_POST['date']?>"/>&nbsp;&nbsp;</div></td></tr><tr><td align="right">
<div style="margin: 10px;">To Date (yyyy-mm-dd): </td><td><input type="text" style="width:200px;" name="dateTo" id="dateTo" value="<?=$_POST['dateTo']?>"/>&nbsp;&nbsp;</div></td></tr><tr><td align="right">
<div style="margin: 10px;">Enter Email: </td><td><input type="text" style="width:200px;" name="email" id="email" value="<?=$_POST['email']?>"/>&nbsp;&nbsp;</div></td></tr><tr><td align="right">
<div style="margin: 10px;">Enter Display name: </td><td><input type="text" style="width:200px;" name="name" id="name" value="<?=$_POST['name']?>"/>&nbsp;&nbsp;</div></td></tr><tr><td align="right">
<div style="margin: 10px;">Enter Question Id: </td><td><input type="text" style="width:200px;" name="entity" id="entity" value="<?=$_POST['entity']?>" />&nbsp;&nbsp;</div></td></tr>
<tr><td align="right">
<div style="margin: 10px;">Enter Institute Name: </div></td>
<td><div style="position:relative;"><input type="hidden" style="" name="instituteName" id="instituteName" value="<?=$_POST['instituteName'];?>" />
<input id="keywordSuggest" type="search" name="keyword" value="<?=$_POST['keyword']?>"  minlength="1" style="width:200px;"/>
<ul id="suggestions_container" class="suggestion-box" style="display: none;position:absolute;top:15px;width:auto;left:0px;"></ul></div>
</td>
</tr>
<tr><td align="right">
<div style="margin: 10px;">Enter Institute Id: </td><td><input type="text" style="width:200px;" name="instituteId" id="instituteId" value="<?=$_POST['instituteId']?>" />&nbsp;&nbsp; (You can enter comma separated values like 751,24211)</div></td></tr><tr><td align="right">
<div style="margin: 10px;">Select Program:</td><td>
<select name='categorySelect' id='categorySelect' style="width:200px;">
<?php
$cat = $_POST['categorySelect'];       
foreach($topCategories as $key=>$value)
{?>
	<option value='<?=$value['programId']?>' <?php if($value['programId'] == $cat){ echo "selected"; } ?>><?php echo $value['programName']?></option>		
<?php } ?>
</select>
</div>
</td></tr><tr><td align="right">
<div style="margin: 10px;">Select Entity Type:</td><td>
<select name='entityType' id='entityType' style="width:200px;">
<option value='user' selected="selected">Question</option>
<option value='answer'>Answer</option>
<option value='answercomment'>Answer Comment</option>
<option value='allccanswers'>All CC Answers</option>
</select>
</div></td></tr>
<tr id="entStsTr" class="entStsTr" style="<?php echo ($_POST['entityType']!='allccanswers' && $_POST['entityType']!='answer')?'display:none':''?>">
	<td align="right">
		<div style="margin: 10px;">Select Entity Status:</td><td>
			<select name='entityModStatus' id='entityModStatus' style="width:200px;">
				<option value='' <?php echo ($_POST['entityModStatus']=='')?'selected="selected"':''?> >All answers</option>
				<option value='notModerated' <?php echo ($_POST['entityModStatus']=='notModerated')?'selected="selected"':''?> >Not Moderated</option>
				<option value='approved' <?php echo ($_POST['entityModStatus']=='approved')?'selected="selected"':''?> >Approved</option>
				<option value='disapproved' <?php echo ($_POST['entityModStatus']=='disapproved')?'selected="selected"':''?> >Disapproved</option>
			</select>
		</div>
	</td>
</tr>
<tr><td align="right">
<input type="hidden" id="start" name="start" value="<?=isset($_POST['start'])?$_POST['start']:0?>"/>
<input type="hidden" id="count" name="count" value="<?=isset($_POST['count'])?$_POST['count']:50 ?>"/>
<div style="margin: 10px;"><input type="button" value="Get Data" onClick="getData();"></div>

</td>
</tr>
</table>
</form>

                                <?php
				  if(isset($_POST['entityType']) && $_POST['entityType']!=""){ ?>
	                              <script>
        	                          var selObj = document.getElementById("entityType");
                	                  var A= selObj.options, L= A.length;
                        	          while(L){
                                	      if (A[--L].value== "<?php echo $_POST['entityType'];?>"){
                                        	  selObj.selectedIndex= L;
	                                          L= 0;
        	                              }
                	                  }
                        	      </script>
	                        <?php } ?>

				
				<?php if(is_object($anaBasicInfo) && is_array($anaBasicInfo->result_array()) && count($anaBasicInfo->result_array())>0){
					$dataArray = $anaBasicInfo->result_array();
				?>
				<div style="font-size:20px; margin-top:20px;"><strong>Found Below Data</strong></div>
				<?php
				echo '<p>Showing '.count($dataArray).' of '.$totalSearchRecords.' records.</p>';
				?>
<table border=0 cellpadding=10 width="98%" align="center" style="margin: 20px;">
<tr bgcolor="#F3E2A9">
<td width='5%'><strong>Id</strong></td>
<td width='5%'><strong>Type</strong></td>
<td width='30%'><strong>Text</strong></td>
<td width='10%'><strong>User</strong></td>
<td width='10%'><strong>INSTITUTE/CAFE</strong></td>
<td width='10%'><strong>Creation Time</strong></td>
<td width='15%'><strong>Action</strong></td>
</tr>

<?php
$greenFlag = '<div style="text-align:center;"><img src="'.SHIKSHA_HOME.'/public/images/flag-green.gif"/></div>';
$greyFlag = '<div style="text-align:center;"><img src="'.SHIKSHA_HOME.'/public/images/flag-grey.gif"/></div>';

for($i=0;$i<count($dataArray);$i++){
				if( ($dataArray[$i]['fromOthers']=='announcement') && $dataArray[$i]['parentId']==0 ){
								continue;
				}
				if($dataArray[$i]['fromOthers']=='user' && $dataArray[$i]['parentId']==0) $type='Question';
				if($dataArray[$i]['fromOthers']=='user' && $dataArray[$i]['parentId']==$dataArray[$i]['threadId'])$type='Answer';
				if($dataArray[$i]['fromOthers']=='user' && $dataArray[$i]['mainAnswerId']>0) $type='Answer Comment';
				if($dataArray[$i]['fromOthers']=='announcement' && $dataArray[$i]['mainAnswerId']==0) $type='Announcement';
				if($dataArray[$i]['fromOthers']=='announcement' && $dataArray[$i]['mainAnswerId']>0) $type='Announcement Comment';
				
				if($i%2==0){
								$trColor = "bgcolor='#eee'";
								echo "<tr ".$trColor.">";
								
				}
				else{
								$trColor = "bgcolor='#E6E6FA'";
								echo "<tr ".$trColor.">";
								
				}
								
				echo "<td>";
				if($type=='Announcement') echo $dataArray[$i]['threadId'];
				else echo $dataArray[$i]['msgId'];
				echo "</td>";
				if($dataArray[$i]['mainAnswerId']==0 && $dataArray[$i]['isCampusRep'] == 1 && $dataArray[$i]['fromOthers']=='user')
				{
					echo "<td><a href='javascript:void(0);' onclick='makeAnswerModerated(\"".$dataArray[$i]['msgId']."\",\"".$dataArray[$i]['userId']."\")' id='Flag_".$dataArray[$i]['msgId']."'>".(($dataArray[$i]['isModDone']=='' || $dataArray[$i]['isModDone']==0)?$greyFlag:$greenFlag)."</a><input type='hidden' id='modFlag_".$dataArray[$i]['msgId']."' value='".(($dataArray[$i]['isModDone']=='' || $dataArray[$i]['isModDone']==0)?'1':'0')."'><br>".$type."</td>";
				}
				else
				{
					echo "<td>".$type."</td>";
				}
				
				echo "<td><p id='CRAns_".$dataArray[$i]['msgId']."'>".$dataArray[$i]['msgTxt'].'</p>';
				if($dataArray[$i]['mainAnswerId']==0 && $dataArray[$i]['fromOthers']=='user')
				{
					echo '<div>';
					?>
					<div>
					<form id="editFormToBeSubmitted<?php echo $dataArray[$i]['msgId'];?>" method="post" onsubmit="return false;" action="" novalidate="" style="display:none;">
                          <div class="submit-ans clear-width 
cmnt_box_all" id="comment_box_<?php echo $dataArray[$i]['msgId'];?>">
                          <div class="submit-ans-child clear-width">

                         <textarea validatesinglechar="true" required="true" minlength="15" maxlength="2500" caption="Answer" 
validate="validateStr" rows="5" style="width:100%" class="ftxArea" id="replyText<?php echo $dataArray[$i]['msgId'];?>" name="replyText<?php echo $dataArray[$i]['msgId'];?>"><?=$dataArray[$i]['msgTxt'];?></textarea>
                         <div style="display:none;font-size:12px;color:red;" class="errorPlace Fnt11"><div id="replyText<?php echo $dataArray[$i]['msgId'];?>_error" class="errorMsg"></div></div>
                         <div class="info-text clear-width">
                         
                         <a class="flRt submit-btn" id="submitButton<?php echo $dataArray[$i]['msgId'];?>" onclick=" 
if(validateFields(document.getElementById('editFormToBeSubmitted<?php echo $dataArray[$i]['msgId'];?>')) != true){ return false;}else{ new Ajax.Request('/messageBoard/MessageBoardInternal/editAnswerByModerator',{onSuccess:function(request){getData();}, evalScripts:true, parameters:Form.serialize($('editFormToBeSubmitted<?php echo $dataArray[$i]['msgId'];?>'))});};" 
href="javascript:void(0);">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $dataArray[$i]['msgId'];?>" onclick="hideCRAnswerEditForm('<?php echo $dataArray[$i]['msgId'];?>')">Cancel</a>

                         </div>
                           <input type="hidden" value="<?php echo $dataArray[$i]['threadId'];?>" name="threadid<?php echo $dataArray[$i]['msgId'];?>">
			   <input type="hidden" value="<?php echo $dataArray[$i]['msgId'];?>" name="answerId">
                           <input type="hidden" value="seccodeForInlineAnswer" name="secCodeIndex">
                           <input type="hidden" value="answer" name="entityType" />
                           <input type="hidden" value="user" name="fromOthers<?php echo $dataArray[$i]['msgId'];?>">
                           <input type="hidden" id="actionPerformed<?php echo $dataArray[$i]['msgId'];?>0" value="editAnswer" 
name="actionPerformed<?php echo $dataArray[$i]['msgId'];?>">
                           <input type="hidden" id="mentionedUsers<?php echo $dataArray[$i]['msgId'];?>" value="" name="mentionedUsers<?php echo $dataArray[$i]['msgId'];?>">


                           </div>
                          </div>
                     </form>
					</div>
					<?php
					echo '<a href="javascript:void(0);" id="editLink'.$dataArray[$i]['msgId'].'" onclick="editCRAnswer(\''.$dataArray[$i]['msgId'].'\')">Edit this answer.</a>';
					echo '</div>';
					if($dataArray[$i]['mainAnswerId']==0){
						echo "<div style='margin:20px 0;'>Total Number Of Characters : ".strlen($dataArray[$i]['msgTxt'])."</div>";
						echo '<b>Question Text:</b><br>'.$dataArray[$i]['questionTxt'];
					}
				}else if($dataArray[$i]['mainAnswerId'] == $dataArray[$i]['parentId'] && $dataArray[$i]['fromOthers']=='user'){
					echo '<div>';
					?>
					<div>
					<form id="commentEditFormToBeSubmitted<?php echo $dataArray[$i]['msgId'];?>" method="post" onsubmit="return false;" action="" novalidate="" style="display:none;">
                          <div class="submit-ans clear-width 
cmnt_box_all" id="comment_box_<?php echo $dataArray[$i]['msgId'];?>">
                          <div class="submit-ans-child clear-width">

                         <textarea validatesinglechar="true" required="true" minlength="15" maxlength="2500" caption="Comment" 
validate="validateStr" rows="5" style="width:100%" class="ftxArea" id="replyText<?php echo $dataArray[$i]['msgId'];?>" name="replyText<?php echo $dataArray[$i]['msgId'];?>"><?=$dataArray[$i]['msgTxt'];?></textarea>
                         <div style="display:none;font-size:12px;color:red;" class="errorPlace Fnt11"><div id="replyText<?php echo $dataArray[$i]['msgId'];?>_error" class="errorMsg"></div></div>
                         <div class="info-text clear-width">
                         
                         <a class="flRt submit-btn" id="submitButton<?php echo $dataArray[$i]['msgId'];?>" onclick=" 
if(validateFields(document.getElementById('commentEditFormToBeSubmitted<?php echo $dataArray[$i]['msgId'];?>')) != true){ return false;}else{ new Ajax.Request('/messageBoard/MessageBoardInternal/editCommentByModerator',{onSuccess:function(request){getData();}, evalScripts:true, parameters:Form.serialize($('commentEditFormToBeSubmitted<?php echo $dataArray[$i]['msgId'];?>'))});};" 
href="javascript:void(0);">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $dataArray[$i]['msgId'];?>" onclick="hideCRCommentEditForm('<?php echo $dataArray[$i]['msgId'];?>')">Cancel</a>

                         </div>
                           <input type="hidden" value="<?php echo $dataArray[$i]['threadId'];?>" name="threadid<?php echo $dataArray[$i]['msgId'];?>">
                           <input type="hidden" value="comment" name="entityType" />
			   				<input type="hidden" value="<?php echo $dataArray[$i]['msgId'];?>" name="commentId">
                           <input type="hidden" value="seccodeForInlineAnswer" name="secCodeIndex">
                           <input type="hidden" value="user" name="fromOthers<?php echo $dataArray[$i]['msgId'];?>">
                           <input type="hidden" id="actionPerformed<?php echo $dataArray[$i]['msgId'];?>0" value="editComment" 
name="actionPerformed<?php echo $dataArray[$i]['msgId'];?>">
                           <input type="hidden" id="mentionedUsers<?php echo $dataArray[$i]['msgId'];?>" value="" name="mentionedUsers<?php echo $dataArray[$i]['msgId'];?>">
                           </div>
                          </div>
                     </form>
					</div>
					<?php 
					echo '<a href="javascript:void(0);" id="commentEditLink'.$dataArray[$i]['msgId'].'" onclick="editCRComment(\''.$dataArray[$i]['msgId'].'\')">Edit this comment.</a>';
					echo '</div>';
					if($dataArray[$i]['mainAnswerId']==$dataArray[$i]['parentId']){
						echo "<div style='margin:20px 0;'>Total Number Of Characters : ".strlen($dataArray[$i]['msgTxt'])."</div>";
						echo '<b>Answer Text:</b><br>'.$dataArray[$i]['answerTxt'].'<br><br>';
						//_p($dataArray);die;
						echo '<b>Question Text:</b><br>'.$dataArray[$i]['questionTxt'];
					}
				}
				
				echo "</td>";
				echo "<td><a href='/getUserProfile/".$dataArray[$i]['displayname']."' target='_blank'>".$dataArray[$i]['displayname']."</a></td>";
				if($dataArray[$i]['listingTypeId']>0){
								echo "<td><a href='/-college-listinganatab-".$dataArray[$i]['listingTypeId']."' target='_blank'>".$dataArray[$i]['institute_name']."</a></td>";
				}
				else{
								echo "<td>CAFE</td>";
				}
				echo "<td>".$dataArray[$i]['creationDate']."</td>"; ?>
				<td>
								<a href='/getTopicDetail/<?=$dataArray[$i]['threadId']?>/' target='_blank'>View Entity</a>&nbsp;&nbsp;
								<span id='deleteLink<?=$dataArray[$i]['msgId']?>'><a href='javascript:void(0);' onClick='deleteCommentFromCMS("<?=$dataArray[$i]['msgId']?>","<?=$dataArray[$i]['threadId']?>","<?=$dataArray[$i]['userId']?>","<?=$type?>","<?=$dataArray[$i]['fromOthers']?>");'>Delete</a></span>&nbsp;&nbsp;
								<?php if($type=='Question' || $type=='Announcement'){ ?>
								<br/><a href="javascript:void(0);" onClick="openPage('<?=$dataArray[$i]['threadId']?>');">Edit <?=$type?></a>
								<?php } ?>
				</td>
				<?php
				   $userId = $dataArray[$i]['userId'];
				   if(isset($dataArray[$i]['courseId'])){
					$courseId = $dataArray[$i]['courseId'];
				   }
				   else{
					$messageId = $dataArray[$i]['threadId'];
					if(isset($courseIdArray[$messageId])){
						$courseId = $courseIdArray[$messageId];
					}
				   }
				   $isFeatured = $dataArray[$i]['isFeatured'];
				   $qtime = strtotime($dataArray[$i]['questionPostTime']);
				   $atime = strtotime($dataArray[$i]['creationDate']);
				?>
				<?php 
				if($dataArray[$i]['mainAnswerId']==0 && $dataArray[$i]['isCampusRep'] == 1 && $dataArray[$i]['fromOthers']=='user' && $atime > strtotime('2014-09-03 00:00:00')) 
				{
				echo '<tr '.$trColor.'>';
				echo '<td>&nbsp;</td><td>&nbsp;</td>';
				echo '<td colspan="100">';
				echo '<hr/>';
				$apprCheck = $disapprCheck = '';
				if($dataArray[$i]['answerSts'] == 'approved')
				    $apprCheck = 'checked="checked"';
				if($dataArray[$i]['answerSts'] == 'disapproved')
				    $disapprCheck = 'checked="checked"';
				?>
				<div class="statusChangeSection">
				<div id="status_<?=$dataArray[$i]['msgId']?>" style="display:block;">
				<ul class="approve-list">
					<li>
						<input type="hidden" id="check_<?=$dataArray[$i]['msgId']?>" value="0">
						<input type="radio" <?=$apprCheck?> <?php if($apprCheck!='' || $disapprCheck!=''):?> disabled <?php endif;?> name="answerStatus_<?=$dataArray[$i]['msgId']?>" id="radio_<?=$dataArray[$i]['msgId']?>_1" value="1" style="float: left;" /><span>Approved</span>
					</li>
					<li>
						<div style="float:left"><input type="radio" <?=$disapprCheck?> <?php if($apprCheck!='' || $disapprCheck!=''):?> disabled <?php endif;?> name="answerStatus_<?=$dataArray[$i]['msgId']?>" id="radio_<?=$dataArray[$i]['msgId']?>_0" value="0" style="float: left;" /><span>Disapproved</span></div>
						<div style="margin-left:170px">
							<label>* Disapproved Reason</label>
							<input type="text" id="disapp_reason_<?=$dataArray[$i]['msgId']?>" maxlength="100" value="<?=$dataArray[$i]['reason']?>" <?php if($apprCheck!='' || $disapprCheck!=''):?> readonly <?php endif;?>/>
							<?php
							if($apprCheck=='' && $disapprCheck=='')
							{
							?>
							<input type="button" value="Submit" onclick="updateAnswerStatus('<?=$dataArray[$i]['msgId']?>', '<?=$dataArray[$i]['userId']?>')">
							<?php
							}
							?>
							<div style="margin-left:190px">
								<span id="reasonMsg_<?=$dataArray[$i]['msgId']?>" style="display:none;color:red;font-size:11px;">Please enter the disapproval reason.</span>
							</div>
						</div>
					</li>
				</ul>
				</div>
				</div>
				<?php
				
				echo '</td></tr>';
				}
}
?>
</table>

				<!-- Pagination HTML -->
				<?php if(isset($_POST['start']) && $_POST['start']!=0 && $_POST['start']>=50){ ?><div style="margin: 20px;font-size:14px; float: left;"><a href="javascript:void(0);" onClick="prev();">PREV</a>&nbsp;&nbsp;</div><?php } ?>
				<?php if(count($dataArray)==50){ ?><div style="margin: 20px;font-size:14px; float: left;"><a href="javascript:void(0);" onClick="next();">NEXT</a></div><?php } ?>
				<div style="clear: both"></div>

				<?php }else{?>
				<div style="font-size:20px; margin-top:20px;"><strong>No Data Found!</strong></div>
				<?php } ?>
</div>

<script>
function next(){
	document.getElementById('start').value = parseInt(document.getElementById('start').value) + 50;
	document.getElementById('dataForm').submit();
}

function prev(){
	document.getElementById('start').value = parseInt(document.getElementById('start').value) - 50;
	document.getElementById('dataForm').submit();
}

function getData(){
        var validate = true;
	validate = validateModPanelForm();
	if (validate) {
		if ($('keywordSuggest').value=='') {
			$('instituteName').value='';
		}
		document.getElementById('start').value = '0';
		//AIM.submit(document.getElementById('dataForm'), {'onStart' : startCallback, 'onComplete' : test});
                //document.getElementById('dataForm').submit();
		document.getElementById('dataForm').submit();
	}
}
/*function test(response){
   $j('#mainWrapper').html('');
   $j('#mainWrapper').html(response);
   initializeAutoSuggestorInstancesCompare();
}*/
var entityToBeDeleted = '';
var globalMsgId = 0;
function deleteCommentFromCMS(msgId,threadId,userId,entityToDeleted,fromOthers)
{
    entityToBeDeleted = (typeof(entityToDeleted)=='undefined')?'answer':entityToDeleted;
    globalMsgId = msgId;
    if(confirm("Are you sure you want to delete this "+entityToBeDeleted+"? If you do this, all its child entities will also get deleted.")){
    if(entityToDeleted=='Announcement Comment'){
    paraString = "type="+fromOthers+"&msgId="+msgId;
    var url = '/messageBoard/MsgBoard/hideComment';			
    }
    else if(entityToDeleted!='Question'){
    paraString = "msgId="+msgId+"&threadId="+threadId+"&userId="+userId;
    var url = "/messageBoard/MsgBoard/deleteCommentFromCMS";
    }
    else{
    paraString = '';
    var url = '/messageBoard/MsgBoard/deleteDiscussionTopic/'+msgId;
    }
    makeAjaxCall(paraString,url,'deleteComment');
    return false;
}
}

function resultOfDeleteCommentFromCMS(response)
{
alert("This "+entityToBeDeleted+" has been deleted successfully");
document.getElementById('deleteLink'+globalMsgId).innerHTML = 'DELETED';
}

//document.getElementById('dataForm').onkeypress=function(e){ if(e.keyCode==13){getData();} };

function openPage(msgId){
			url = '/getTopicDetail/'+msgId+'/#openeditlayer';
			window.open(url,'_blank','titlebar=0,menubar=0,toolbar=0,scrollbars=1,height=700,width=1020');
}
function makeAjaxCall(paraString,url,functionCall){
  var xmlhttp;
  if (window.XMLHttpRequest) {
     // code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp = new XMLHttpRequest();
 } else {
     // code for IE6, IE5
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
 }

 xmlhttp.onreadystatechange = function() {
     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        if(functionCall=='deleteComment')
	 resultOfDeleteCommentFromCMS(xmlhttp.responseText);
        if(functionCall=='makeFeaturedAnswer')
	 resultOfFeaturedAnswer(xmlhttp.responseText);
	if(functionCall=='updateCRAnswerStatus')
	 resultOfAnsStatusUpdate(xmlhttp.responseText);
	if(functionCall=='makeAnswerModerated')
	 resultOfMakeAnswerModerated(xmlhttp.responseText);
	if(functionCall=='addCRAnswerEarning')
	 resultOfMakeApproveEarning(xmlhttp.responseText);
     }
 }

 xmlhttp.open("POST", url, true);
 xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
 xmlhttp.send(paraString);
}		

function featuredAnswer(msgId, uId){
    var flag = 0;
    var answer = confirm("Do you confirm?");
    if(answer){
	flag = 1;
	paraString = "msgId="+msgId+"&flag="+flag;
	var url = "/messageBoard/MessageBoardInternal/makeFeaturedAnswer";
	makeAjaxCall(paraString,url,'makeFeaturedAnswer');
       
	var earning = document.getElementById('featureEarning_'+msgId).value;
	params = "msgId="+msgId+"&userId="+uId+"&earning="+earning+"&type=featuredAnswer";
	url = "/messageBoard/MessageBoardInternal/addCRAnswerEarning";
	makeAjaxCall(params,url,'addCRAnswerEarning');
	return true;
    }
    else{
        /*if(document.getElementById(msgId).checked)
             document.getElementById(msgId).checked = false;
        else
             document.getElementById(msgId).checked = true;
        return false;*/
    }
}

function resultOfFeaturedAnswer(response){
    if(response =='1')
      alert("This answer has been marked as featured successfully.");
    else
      alert("Removed from featured list successfully.");
}
function validateModPanelForm()
{
    var validate = true;
    var fromDate = document.getElementById('date').value;
    var toDate = document.getElementById('dateTo').value;
    if ((toDate=='' && fromDate!='') || (toDate!='' && fromDate=='')) {
        validate = false;
	alert('Please enter both dates.');
    }
    if (toDate!='' && fromDate!='') {
	var date1 = new Date(fromDate);
	var date2 = new Date(toDate);
	var diffDays = parseInt((date2.getTime() - date1.getTime()) / (1000 * 60 * 60 * 24));
	if(diffDays > 14){
		validate = false;
		alert('Please enter a smaller date range.');
	}
	else if (diffDays < 0) {
		validate = false;
		alert('Please enter start date less than end date.');
	}
    }
    return validate;
}
/*function hideShowStatusSection(uid)
{
    var toggle = document.getElementById('check_'+uid).value;
    if (toggle == 0) {
	document.getElementById('check_'+uid).value = 1;
	document.getElementById('status_'+uid).style.display = 'block';
    }
    else{
	document.getElementById('check_'+uid).value = 0;
	document.getElementById('status_'+uid).style.display = 'none';
    }
}*/
function updateAnswerStatus(ansId, uId)
{
    var reason = '';
    var status = '';
    var approve = document.getElementById('radio_'+ansId+'_1').checked;
    var disappr = document.getElementById('radio_'+ansId+'_0').checked;
    if (disappr) {
	reason = document.getElementById('disapp_reason_'+ansId).value;
	if (reason == '') {
		document.getElementById('reasonMsg_'+ansId).style.display = 'inline';
	}
	else{
		status = 'disapproved';
	}
    }
    else if (approve) {
	status = 'approved';
    }
    else
    {
	alert('Please choose approve/disapprove.');			
    }
    if (status!='') {
	var params = "msgId="+ansId+"&userId="+uId+"&status="+status+"&reason="+reason;
	var url = "/messageBoard/MessageBoardInternal/updateCRAnswerStatus";
	makeAjaxCall(params,url,'updateCRAnswerStatus');
	document.getElementById('reasonMsg_'+ansId).style.display = 'none';
	
//	var earning = document.getElementById('approveEarning_'+ansId).value;
	params = "msgId="+ansId+"&userId="+uId+"&status="+status+"&type=approvedAnswer";
	url = "/messageBoard/MessageBoardInternal/addCRAnswerEarning";
	makeAjaxCall(params,url,'addCRAnswerEarning');
	
	
    }
}
function resultOfAnsStatusUpdate(response)
{
    alert('The answer is marked '+response+'.')
}
function makeAnswerModerated(msgId, userId)
{
	var flag = document.getElementById('modFlag_'+msgId).value;
	var params = "msgId="+msgId+"&userId="+userId+"&flag="+flag;
	var url = "/messageBoard/MessageBoardInternal/makeAnswerModerated";
	makeAjaxCall(params,url,'makeAnswerModerated')
	//alert(params)
	if (flag == '0') {
		document.getElementById('Flag_'+msgId).innerHTML = '<?=$greyFlag;?>';
		document.getElementById('modFlag_'+msgId).value = '1';
	}
	else
	{
		document.getElementById('Flag_'+msgId).innerHTML = '<?=$greenFlag;?>';
		document.getElementById('modFlag_'+msgId).value = '0';
	}
}
function resultOfMakeAnswerModerated(response)
{
	/*if (response == '0') {
		//alert('Check');
	}
	else{
		//alert('Checked');
	}*/
}
function resultOfMakeApproveEarning(response)
{
	setTimeout(function(){getData();}, 2000);
}
function editCRAnswer(tupleId)
{
	document.getElementById('editFormToBeSubmitted'+tupleId).style.display = 'inline';
	document.getElementById('editLink'+tupleId).style.display = 'none';
	document.getElementById('CRAns_'+tupleId).style.display = 'none';
	document.getElementById('replyText'+tupleId).focus();
}
function hideCRAnswerEditForm(tupleId)
{
	document.getElementById('editFormToBeSubmitted'+tupleId).style.display = 'none';
	document.getElementById('editLink'+tupleId).style.display = 'inline';
	document.getElementById('CRAns_'+tupleId).style.display = 'inline';
}
function editCRComment(tupleId)
{
	document.getElementById('commentEditFormToBeSubmitted'+tupleId).style.display = 'inline';
	document.getElementById('commentEditLink'+tupleId).style.display = 'none';
	document.getElementById('CRAns_'+tupleId).style.display = 'none';
	document.getElementById('replyText'+tupleId).focus();
}
function hideCRCommentEditForm(tupleId)
{
	document.getElementById('commentEditFormToBeSubmitted'+tupleId).style.display = 'none';
	document.getElementById('commentEditLink'+tupleId).style.display = 'inline';
	document.getElementById('CRAns_'+tupleId).style.display = 'inline';
}
$j(document).ready(function(){
	$j('#entityType').change(function(){
		if($j(this).val() == 'answer' || $j(this).val() == 'allccanswers'){
			$j('#entStsTr').show().find('select#entityModStatus').val('');
		}else{
			$j('#entStsTr').hide().find('select#entityModStatus').val('');
		}
	});
});
</script>

<script>try{ overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay')); }catch(e){ }</script>
                   <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('footer'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('user'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('lazyload'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('myShiksha'); ?>"></script>


<script>
 if(typeof(handleClickForAutoSuggestor) == "function") {
	if (window.addEventListener){
		document.addEventListener('click', handleClickForAutoSuggestor, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleClickForAutoSuggestor);
	}
 }
 
 if(typeof(handleClickForAutoSuggestorAlt) == "function") {
	if (window.addEventListener){
		document.addEventListener('click', handleClickForAutoSuggestorAlt, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleClickForAutoSuggestorAlt);
	}
 }
 
 

 
 
</script>


<script>
    /*
     For Institute Suggestor
    */

    var autoSuggestorInstanceCompare = null;
    //var isMobile = true;
    function initializeAutoSuggestorInstancesCompare(){
        
        if (window.addEventListener){ 
            var ele = document.getElementById("keywordSuggest"); 
            ele.addEventListener('keyup', handleInputKeysForInstituteSuggestorCompare, false); 
        } else if (window.attachEvent){
            var ele = document.getElementById("keywordSuggest"); 
            ele.attachEvent('onkeyup', handleInputKeysForInstituteSuggestorCompare); 
        }
        
        //autoSuggestorInstance = new AutoSuggestor("keywordSuggest" , "suggestions_container", false, 'institute_and_course',6);
        autoSuggestorInstanceCompare = new AutoSuggestor("keywordSuggest" , "suggestions_container", false, 'institute',6);
        
        autoSuggestorInstanceCompare.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClickCompare;
      
        autoSuggestorInstanceCompare.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressedCompare;
    
        autoSuggestorInstanceCompare.callBackFunctionOnRightKeyPressed = handleAutoSuggestorRightKeyPressedCompare;
       
    }
    
    var keywordEnteredByUserCompare = '';
    function handleInputKeysForInstituteSuggestorCompare(e){
       
        window.jQuery('#keywordSuggest').change(function(){
            keywordEnteredByUserCompare = $j('#keywordSuggest').val();        
        });
        
        if(autoSuggestorInstanceCompare ){
            autoSuggestorInstanceCompare.handleInputKeys(e);
        }
    }
    
    function handleClickForAutoSuggestorCompare(){
        if(autoSuggestorInstanceCompare){ 
            autoSuggestorInstanceCompare.hideSuggestionContainer();
        }
    }
               
    function handleAutoSuggestorMouseClickCompare(callBackData){
        if(autoSuggestorInstanceCompare){
            autoSuggestorInstanceCompare.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp']);
        }
    }
    
    function handleAutoSuggestorRightKeyPressedCompare(callBackData){
        //autoSuggestorInstance.hideSuggestionContainer(); 
    }
    
    function handleAutoSuggestorEnterPressedCompare(callBackData){
         if(autoSuggestorInstanceCompare){
            autoSuggestorInstanceCompare.hideSuggestionContainer();
            instituteSelected(callBackData['id'],callBackData['sp']);
        }
    }
    function instituteSelected(instId,instTitle){
	document.getElementById('instituteName').value = instId;
    }

window.onload=function(){
	if(typeof(initializeAutoSuggestorInstancesCompare) == "function") {
			initializeAutoSuggestorInstancesCompare(); //For initiating AutoSuggestor Instance
		}
		//Event listener for hiding dropdown suggestions when user clicks outside the suggestion container
		if(typeof(handleClickForAutoSuggestorCompare) == "function") {
		    if(window.addEventListener){
			document.addEventListener('click', handleClickForAutoSuggestorCompare, false);
		    } else if (window.attachEvent){
			document.attachEvent('onclick', handleClickForAutoSuggestorCompare);
		    }
		}
		if(typeof(handleClickForAutoSuggestorAlt) == "function") {
			if (window.addEventListener){
				document.addEventListener('click', handleClickForAutoSuggestorAlt, false); 
			} else if (window.attachEvent){
				document.attachEvent('onclick', handleClickForAutoSuggestorAlt);
			}
		}
};

</script>


<?php
//$this->load->view('common/footer');
?>
</body>

</html>
