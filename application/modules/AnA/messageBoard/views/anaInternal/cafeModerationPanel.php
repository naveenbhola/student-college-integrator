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
				<title>Shiksha Cafe Moderation Tool</title>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>

<script type="text/javascript">
  function loadJsFilesInParallel(){
	$LAB
	.script("//js.shiksha.ws/public/js/AutoSuggestor20150930.js")
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
				if(typeof(initializeAutoSuggestorInstancesForCollgeReviews) == 'function') {
			initializeAutoSuggestorInstancesForCollgeReviews();
		}
			
			if(typeof(initializeAutoSuggestorInstancesForCampusConnect) == 'function') {
			initializeAutoSuggestorInstancesForCampusConnect();
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
				<!-- LABJs utility loaded in parallel-->
<script type="text/javascript">
  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
  function f(){ loadJsFilesInParallel();}
  H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
  a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);
  if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
</script>

				
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('campusAmbassador'); ?>"></script>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('CAValidations'); ?>"></script>
				<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
				<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
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

				<div style="font-size:20px;margin-bottom: 20px;"><strong>ANA Moderation Panel</strong>
				<input type="button" value = "Get User Point History" style="float:right" onclick="location.href = '/messageBoard/MessageBoardInternal/userPointHistory'">
				</div>
				
<form  id="dataForm" method="post" autocomplete="off" accept-charset="utf-8" action="/messageBoard/MessageBoardInternal/cafeModerationPanel" onsubmit="return false;">
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
<div style="margin: 10px;">Enter Question/Discussion Id: </td><td><input type="text" style="width:200px;" name="entity" id="entity" value="<?=$_POST['entity']?>" />&nbsp;&nbsp;</div></td></tr>

<tr><td align="right">

<div style="margin: 10px;">Enter Tag: </td><td>
<input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field" style="width:350px;" name="tag_search" id="tag_search" value="<?=$_POST['tag_search']?>" />

<Input type="hidden" name="tagValue" id="tagValue" value="<?=$_POST['tagValue']?>">&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;</div></td></tr>

<tr><td align="right">
<div style="margin: 10px;">Select Category:</td><td>
<select name='categorySelect' id='categorySelect' style="width:200px;">
<option value=''>All</option>
<?php
$cat = $_POST['categorySelect'];
foreach($topCategories as $category)
{
        if($category[0] == $cat)
	{
?>
		<option value='<?=$category[0]?>' selected="selected"><?=$category[1]?></option>		
<?php
	}
	else
	{
?>
                <option value='<?=$category[0]?>'><?=$category[1]?></option>
<?php
	}
}
?>
</select>
</div>
</td></tr><tr><td align="right">
<div style="margin: 10px;">Select Entity Type:</td><td>
<select name='entityType' id='entityType' style="width:200px;">
<option value=''>All</option>
<option value='user'>Question</option>
<option value='discussion'>Discussion</option>
<option value='answer'>Answer</option>
<option value='answercomment'>Answer Comment</option>
<option value='discussioncomment'>Discussion Comment</option>
<option value='discussionreply'>Discussion Reply</option>
<option value='Unansweredquestion'>Unanswered Question</option>
</select>
</div></td></tr><tr><td align="right">
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

				
				<?php if(is_array($anaBasicInfo) && count($anaBasicInfo)>0){
					$dataArray = $anaBasicInfo;
					$totalRecords = count($dataArray)+ $start;
				?>
				<div style="font-size:20px; margin-top:20px;"><strong>Found Below Data</strong></div>
				<?php
				echo '<p>Showing '.$totalRecords.' of '.$totalSearchRecords.' records.</p>';
				?>
<table border=0 cellpadding=10 width="98%" align="center" style="margin: 20px;">
<tr bgcolor="#F3E2A9">
<td width='5%'><strong>Id</strong></td>
<td width='5%'><strong>Type</strong></td>
<td width='30%'><strong>Text</strong></td>
<td width='10%'><strong>User</strong></td>
<td width='10%'><strong>INSTITUTE/CAFE</strong></td>
<td width='10%'><strong>Creation Time</strong></td>
<td width='12%'><strong>Action</strong></td>
</tr>

<?php
$greenFlag = '<div style="text-align:center;"><img src="'.SHIKSHA_HOME.'/public/images/flag-green.gif"/></div>';
$greyFlag = '<div style="text-align:center;"><img src="'.SHIKSHA_HOME.'/public/images/flag-grey.gif"/></div>';

//_p($dataArray);die;
for($i=0;$i<count($dataArray);$i++){
				if( $dataArray[$i]['fromOthers'] == 'discussion' && $dataArray[$i]['parentId']==0 ){
								continue;
				}
				if($dataArray[$i]['fromOthers']=='user' && $dataArray[$i]['parentId']==0) $type='Question';
				if($dataArray[$i]['fromOthers']=='user' && $dataArray[$i]['parentId']==$dataArray[$i]['threadId'])$type='Answer';
				if($dataArray[$i]['fromOthers']=='user' && $dataArray[$i]['mainAnswerId']==$dataArray[$i]['parentId'] && $dataArray[$i]['mainAnswerId']>0) $type='Answer Comment';
				if($dataArray[$i]['fromOthers']=='user' && $dataArray[$i]['mainAnswerId']!=$dataArray[$i]['parentId'] && $dataArray[$i]['mainAnswerId']>0) $type='Comment Reply';
				if($dataArray[$i]['fromOthers']=='discussion' && $dataArray[$i]['mainAnswerId']==0) $type='Discussion';
				if($dataArray[$i]['fromOthers']=='discussion' && $dataArray[$i]['mainAnswerId']==$dataArray[$i]['parentId']) $type='Discussion Comment';
				if($dataArray[$i]['fromOthers']=='discussion' && $dataArray[$i]['mainAnswerId']>0 && $dataArray[$i]['mainAnswerId']!=$dataArray[$i]['parentId'] ) $type='Discussion Reply';
				
				if($i%2==0){
								$trColor = "bgcolor='#eee'";
								echo "<tr ".$trColor.">";
								
				}
				else{
								$trColor = "bgcolor='#E6E6FA'";
								echo "<tr ".$trColor.">";
								
				}
								
				echo "<td>";
				if($type=='Discussion') echo $dataArray[$i]['threadId'];
				else echo $dataArray[$i]['msgId'];
				echo "</td>";
				echo "<td>".$type."</td>";
				
				
				echo "<td><p id='CRAns_".$dataArray[$i]['msgId']."'>".$dataArray[$i]['msgTxt'].'</p>';
				if($dataArray[$i]['mainAnswerId']!=-1)
				{
				      if($type=='Answer Comment'){
				           $minLength = 5;
					   $entityType = "comment";
				      }else if($type=='Discussion Comment'){
				           $minLength = 15;
					   $entityType = "comment";
				      }else if($type=='Discussion Reply' || $type=='Comment Reply'){
                                           $minLength = 5;
					   $entityType = "reply";
				      }else{
                                           $minLength = 15;
					   $entityType = "answer";
				      }
				      
					echo '<div>';
					?>
					<div>
					<form id="editFormToBeSubmitted<?php echo $dataArray[$i]['msgId'];?>" method="post" onsubmit="return false;" action="" novalidate="" style="display:none;">
                          <div class="submit-ans clear-width 
cmnt_box_all" id="comment_box_<?php echo $dataArray[$i]['msgId'];?>">
                          <div class="submit-ans-child clear-width">

                         <textarea validatesinglechar="true" required="true" minlength="<?php echo $minLength?>" maxlength="2500" caption='<?php echo $type;?>' 
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
                           <input type="hidden" value="<?php echo $dataArray[$i]['fromOthers'] ?>" name="fromOthers<?php echo $dataArray[$i]['msgId'];?>">
                           <input type="hidden" id="actionPerformed<?php echo $dataArray[$i]['msgId'];?>0" value="editAnswer" 
name="actionPerformed<?php echo $dataArray[$i]['msgId'];?>">
                           <input type="hidden" id="mentionedUsers<?php echo $dataArray[$i]['msgId'];?>" value="" name="mentionedUsers<?php echo $dataArray[$i]['msgId'];?>">
			   <input type="hidden" value="<?php echo $entityType;?>" name="entityType">


                           </div>
                          </div>
                     </form>
					</div>
					

					<?php if(($dataArray[$i]['fromOthers'] == 'discussion' && $dataArray[$i]['mainAnswerId']!=0) || $dataArray[$i]['fromOthers'] == 'user'){?>
								<a href="javascript:void(0);" id="editLink<?=$dataArray[$i]['msgId']?>" onclick='editCRAnswer("<?=$dataArray[$i]['msgId']?>")'>Edit this <?=$entityType?>.</a>

					<?php } echo '</div>';

					
					if($dataArray[$i]['mainAnswerId']!=-1 && $dataArray[$i]['fromOthers']!='discussion')
					echo '<br><b>Question Text:</b><br>'.$dataArray[$i]['questionTxt'].'<br>';
					if($dataArray[$i]['mainAnswerId']>0){
				                
				                if(!empty($dataArray[$i]['answerTxt'])){
								if($dataArray[$i]['fromOthers'] == 'discussion'){
									echo '<p><b>Discussion Text:</b><br>'.$dataArray[$i]['answerTxt'].'</p>';			
								}else{
									echo '<p><b>Answer Text:</b><br>'.$dataArray[$i]['answerTxt'].'</p>';			
								}
				                      	
				                 }
						 
						 if(!empty($dataArray[$i]['commentTxt'])){
								echo '<p><b>Comment Text:</b><br>'.$dataArray[$i]['commentTxt'].'</p>';
						 }
						 	
					}	
				}
				
				if($dataArray[$i]['parentId']==0 || ($dataArray[$i]['fromOthers']=='discussion' && $dataArray[$i]['mainAnswerId']==0)){
						if(empty($associatedTags[$dataArray[$i]['threadId']])){
								$action = 'Add Tags';
								
						}else{
								$action = 'Edit Tags';
						}
								
								
								
								?>
				
				<div>
					<form id="editTagsFormToBeSubmitted<?php echo $dataArray[$i]['threadId'];?>" method="post" onsubmit="return false;" action="/Tagging/TaggingCMS/editTagsFromModeration" novalidate="" style="display:none;">
                       
                         <div class="cms-fields tag_parent">
                                    <div>
                                        <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name_<?php echo $dataArray[$i]['msgId'];?>' id='tag_name_<?php echo $dataArray[$i]['msgId'];?>'>
                                         &nbsp;&nbsp;&nbsp; 
                                         <div class="empty-message"></div>
                                     </div>

                                </div>
			 
			 <div id="tagContainer_<?=$dataArray[$i]['threadId'];?>">
				<?php
				    $counter=0;
				    while($counter<count($finalTags[$dataArray[$i]['threadId']])){			
				
				?>
				    
				                 <Input type = 'Checkbox' name='tagsName_<?php echo $dataArray[$i]['threadId'];?>[]' id='tagsName_<?php echo $dataArray[$i]['threadId'];?>' value ='<?php echo $finalTags[$dataArray[$i]['threadId']][$counter]['tagId']; ?>' checked><?php echo $finalTags[$dataArray[$i]['threadId']][$counter]['tagName']; ?>
						 <Input type='hidden' name='tagsClass_<?php echo $finalTags[$dataArray[$i]['threadId']][$counter]['tagId']; ?>' id='tagsClass_<?php echo $dataArray[$i]['threadId'];?>' value ='<?php echo $finalTags[$dataArray[$i]['threadId']][$counter]['classification']; ?>' >
						 <?php
						 $counter++;
				    }?>
				</div>
                         <div class="info-text clear-width">
                         
                         <a class="flRt submit-btn" id="submitButton<?php echo $dataArray[$i]['msgId'];?>" 
href="javascript:void(0);" onclick="submitEditTagForm('<?=$dataArray[$i]['msgId']?>','<?=$dataArray[$i]['threadId']?>','<?=strtolower($type)?>','<?=$action?>')">Submit</a>

&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" id="cancelButton<?php echo $dataArray[$i]['msgId'];?>" onclick="hideEditTagForm('<?php echo $dataArray[$i]['threadId'];?>','<?php echo $action;?>')">Cancel</a>

                         </div>
                      
                     </form>
					</div>
				
				<?php  if(!empty($associatedTags[$dataArray[$i]['threadId']])){
								
								echo '<a href="javascript:void(0);" id="editTagLink'.$dataArray[$i]['threadId'].'" onclick="editEntityTags(\''.$dataArray[$i]['threadId'].'\',\''.$action.'\');bindAutoSuggestor(\'cafeModeration\',\'\',\''.$dataArray[$i]['threadId'].'\');" style="display:block; margin-bottom:10px;" >Edit Tags</a>';
								
					              echo '<p id="tagBlock'.$dataArray[$i]['threadId'].'"><b>Tags:</b><br>'.$associatedTags[$dataArray[$i]['threadId']].'</p>';
				         }else{
								echo '<a href="javascript:void(0);" id="editTagLink'.$dataArray[$i]['threadId'].'" onclick="editEntityTags(\''.$dataArray[$i]['threadId'].'\',\''.$action.'\');bindAutoSuggestor(\'cafeModeration\',\'\',\''.$dataArray[$i]['threadId'].'\');" style="display:block; margin-bottom:10px;" >Add Tags</a>';
					 }
				}
				
				
				if($dataArray[$i]['parentId']==0){
								$countryId = $dataArray[$i]['countryId'];
								$categoryId = $dataArray[$i]['categoryId'];
								$parentId = $categoryList[$categoryId][1];
								$countryName = $countryList[$countryId];
								$subCatName = $categoryList[$categoryId][0];
								$catName = $categoryList[$parentId][0];
								
								if(isset($catName) && $catName != '')
								                 echo "<div style='margin-top:20px;'><u><i>$catName - $subCatName In $countryName</i></u></div>";
				}
				
				echo "</td>";
				
				
				echo "<td><a href='/getUserProfile/".$dataArray[$i]['displayname']."' target='_blank'>".$dataArray[$i]['displayname']."</a><bR/>";
				echo "<p>".$dataArray[$i]['levelName']."</p></td>";
				
								echo "<td>CAFE</td>";
				
				echo "<td>".$dataArray[$i]['creationDate']."</td>";?>
				
				<td>
				<?php if(($dataArray[$i]['mainAnswerId']==0 && $dataArray[$i]['fromOthers']=='user') || ($dataArray[$i]['mainAnswerId']==$dataArray[$i]['parentId'] && $dataArray[$i]['fromOthers']=='discussion')){
				     if($dataArray[$i]['fromOthers']=='discussion'){
				          $entityType = 'discussion';
		                     }else{
				          $entityType = 'question';
		                     }
				     $shareUrl = getSeoUrl($dataArray[$i]['threadId'], $entityType, $dataArray[$i]['questionTxt']).'?referenceEntityId='.$dataArray[$i]['msgId'];
				?>
				     
				     <a href='<?=$shareUrl?>' target='_blank' style='display:block'>View Entity</a>&nbsp;&nbsp;
				<?php }else{?>
				
								
								
								<a href='/getTopicDetail/<?=$dataArray[$i]['threadId']?>/' target='_blank' style='display:block'>View Entity</a>&nbsp;&nbsp;
				<?php }?>
								<span id='deleteLink<?=$dataArray[$i]['msgId']?>'><a href='javascript:void(0);' onClick='deleteCommentFromCMS("<?=$dataArray[$i]['msgId']?>","<?=$dataArray[$i]['threadId']?>","<?=$dataArray[$i]['userId']?>","<?=$type?>","<?=$dataArray[$i]['fromOthers']?>");'>Delete</a></span>&nbsp;&nbsp;
								<?php if($type=='Question' || $type=='Discussion'){ ?>
								<br/><a href="javascript:void(0);" onClick="openPage('<?=$dataArray[$i]['threadId']?>');">Edit <?=$type?></a>
								<?php } ?>
				</td>
				
				
				<?php
				echo "</tr>";
				
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
		document.getElementById('start').value = '0';
		//AIM.submit(document.getElementById('dataForm'), {'onStart' : startCallback, 'onComplete' : test});
                //document.getElementById('dataForm').submit();
		document.getElementById('dataForm').submit();
	}
}

var entityToBeDeleted = '';
var globalMsgId = 0;
function deleteCommentFromCMS(msgId,threadId,userId,entityToDeleted,fromOthers)
{
    entityToBeDeleted = (typeof(entityToDeleted)=='undefined')?'answer':entityToDeleted;
    globalMsgId = msgId;
    if(confirm("Are you sure you want to delete this "+entityToBeDeleted+"? If you do this, all its child entities will also get deleted.")){
    if(entityToDeleted=='Discussion Comment'){
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
     }
 }

 xmlhttp.open("POST", url, true);
 xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
 xmlhttp.send(paraString);
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
	if(diffDays > 365){
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


function resultOfAnsStatusUpdate(response)
{
    alert('The answer is marked '+response+'.')
}

function editCRAnswer(tupleId)
{
	document.getElementById('editFormToBeSubmitted'+tupleId).style.display = 'inline';
	document.getElementById('replyText'+tupleId).value = $j('#CRAns_'+tupleId).text();
	document.getElementById('editLink'+tupleId).style.display = 'none';
	document.getElementById('CRAns_'+tupleId).style.display = 'none';
}
function hideCRAnswerEditForm(tupleId)
{
        document.getElementById('replyText'+tupleId+'_error').style.display = 'none';
	document.getElementById('editFormToBeSubmitted'+tupleId).style.display = 'none';
	document.getElementById('editLink'+tupleId).style.display = 'inline';
	document.getElementById('CRAns_'+tupleId).style.display = 'inline';
}

function editEntityTags(entityId,action)
{
	document.getElementById('editTagsFormToBeSubmitted'+entityId).style.display = 'inline';
	document.getElementById('editTagLink'+entityId).style.display = 'none';
	if (action == 'Edit Tags') {
		document.getElementById('tagBlock'+entityId).style.display = 'none';
	}
}
function hideEditTagForm(entityId,action)
{
	document.getElementById('editTagsFormToBeSubmitted'+entityId).style.display = 'none';
	document.getElementById('editTagLink'+entityId).style.display = 'block';
	if (action == 'Edit Tags') {
		document.getElementById('tagBlock'+entityId).style.display = 'block';
	}
}

function add_more(entityId,page){
            html = "<br />";
            html += " <div><input type='text' autocomplete='off' class='tags universal-txt-field cms-text-field'   style='color:#565656;width:275px;' name='tag_name[]'><input type='hidden' name='tag_id[]'><div class='empty-message'></div></div>";
            $j(".tag_name_"+entityId).append(html);   
            bindAutoSuggestor(page); 
    }
    
function submitEditTagForm(EntityId,editEntityId,entityType,action){
        var tagString=$j('#editTagsFormToBeSubmitted'+editEntityId).serialize();

	jQuery.ajax({
			url: "/Tagging/TaggingCMS/editTagsFromModeration",
			type: "POST",
			data: tagString+'&editEntityId='+editEntityId+'&entityType='+entityType+'&action='+action,
			success: function(result)
			{
				alert('Tags have been updated successfully');
				document.getElementById('editTagsFormToBeSubmitted'+editEntityId).style.display = 'none';
	                        document.getElementById('editTagLink'+editEntityId).style.display = 'inline';
				location.reload();
			},
                   })
}
    
    
</script>

<script>try{ overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay')); }catch(e){ }</script>
                   <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('footer'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('user'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('lazyload'); ?>"></script>
                <script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('myShiksha'); ?>"></script>
		
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
				
		<script language="javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('taggingcms'); ?>"></script>
		
<script>
       bindAutoSuggestor('cafeModeration');
</script>

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
 
 
</body>

</html>
