<?php
$entityType = (isset($entityType))?$entityType:'user';
$minLength = 5;
$maxLength = 100;
switch ($entityType){
  case 'user': $buttonText = 'Update';
		    $titleWord = "Question";
		    $fromOthers = "user";
		    $titleHeading = "Question";
                    $postHeading = "Description";
		    $minLength = 2;
		    $maxLength = 300;
		   break;
  case 'discussion': $buttonText = 'Update';
		    $titleWord = "Discussion";
		    $fromOthers = "discussion";
		    $titleHeading = "Topic";
		    $postHeading = "Post";
		   break;
  case 'announcement': $buttonText = 'Update';
		    $titleWord = "Announcement";
		    $fromOthers = "announcement";
		    $titleHeading = "Title";
		    $postHeading = "Detail";
		   break;
  case 'review': $buttonText = 'Post Review';
		    $titleWord = "Discussion";
		    $fromOthers = "review";
		   break;
  case 'eventAnA': $buttonText = 'Post Event';
		    $titleWord = "Event";
		    $fromOthers = "eventAnA";
		   break;
}
$topicCountryId = ($topicDetailEdit[0]['catCounData'][0]['countryId']!='')?$topicDetailEdit[0]['catCounData'][0]['countryId']:2;
$indiaRadio = "checked";
/*
if($topicCountryId == 2){
  $indiaRadio = "checked";
  $abroadRadio = "";
}
else if($topicCountryId > 2){
  $indiaRadio = "";
  $abroadRadio = "checked";
}
*/

$selectedcategoryId = $topicDetailEdit[0]['catCounData'][0]['categoryId'];
$selectedsubcategoryId = $topicDetailEdit[0]['catCounData'][1]['categoryId'];
//Code to switch the Cateogyr Id and Sub category Id in case the CategoryId is greater than 20
if($selectedcategoryId > 20 && $selectedcategoryId != 149){
    $tempCat = $selectedcategoryId;
    $selectedcategoryId = $selectedsubcategoryId;
    $selectedsubcategoryId = $tempCat;
}
$categoryClient = new Category_list_client();
$categoryListIndia = $categoryClient->getCategoryTree($appId,'','national');
$categoryListAbroad = $categoryClient->getCategoryTree($appId,'','studyabroad');
if($abroadRadio == "checked"){
  $categoryList = $categoryListAbroad;
}else{
  $categoryList = $categoryListIndia;
}
?>

<div id="category_page_ana_question_post_display_count" class="txt_align_r"></div>
<form id="askQuestionFormPost" autocomplete="off"
 onsubmit="return false;" method="post" action="/messageBoard/MsgBoard/createTopic"  novalidate="novalidate">
<input type="hidden" name="topicDesc" value="" id="topicDesc1" />
<input type="hidden" name="editit" value="<?php echo $topicDetailEdit[0]['topicData'][0]['threadId'];?>" id="editit" />
<input type="hidden" name="listingType" value="<?php echo $listingType; ?>" id="listingType" />
<input type="hidden" name="listingTypeId" value="<?php echo $listingTypeId; ?>" id="listingTypeId" />
<input type="hidden" name="fromOthers" value="<?php echo $fromOthers;?>" id="fromOthers" />
<input type="hidden" name="entityType" value="<?php echo $entityType;?>" id="entityType" />
<input type="hidden" name="topicDescription" value="" id="topicDescription" />

<div style="width:520px">
        <div>
            <div style="width:100%">
		<div>
		    <div class="wdh100 mb5">
			<div style="margin-bottom:5px;"><b>Enter <?php echo $titleHeading;?></b></div>
            
			<?php if($entityType != 'user' && $entityType != 'question' && $entityType=='discussion'){ ?>
			  <input type="text" style="width:468px;padding:5px 5px;" class="inptBxSty" name="questionTextD" id="questionTextD" autocomplete="off" onkeyup="checkForNameMention(event,this,'questionTextD');" profanity="true" validate="validateStr" caption="<?php echo $titleHeading;?>" maxlength="<?php echo $maxLength;?>" minlength="<?php echo $minLength;?>" required="true" onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter <?php echo $titleHeading;?>" value="<?php echo $topicDetailEdit[0]['topicData'][0]['msgTxt'];?>"/>
              <input type="hidden" name="mentionedUsersList" value="" id="mentionedUsers"/>
			<?php }else if($entityType != 'user' && $entityType != 'question' && $entityType=='announcement'){ ?>
              <input type="text" style="width:468px;padding:5px 5px;" class="inptBxSty" name="questionTextD" id="questionTextD" autocomplete="off" onkeyup="try{ $('questionTextD_error').parentNode.style.display='none';textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="<?php echo $titleHeading;?>" maxlength="<?php echo $maxLength;?>" minlength="<?php echo $minLength;?>" required="true" onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter <?php echo $titleHeading;?>" value="<?php echo $topicDetailEdit[0]['topicData'][0]['msgTxt'];?>"/>
            <?php }else{ ?>
<textarea type="text" style="width:468px;padding:5px 5px;height:80px;" class="inptBxSty" name="questionTextD" id="questionTextD" autocomplete="off" onkeyup="try{ $('questionTextD_error').parentNode.style.display='none';textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="<?php echo $titleHeading;?>" maxlength="140" minlength="2" required="true" caption ="Question" onblur="if($('questionDescD'))checkForTips('questionTextD');checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter <?php echo $titleHeading;?>" value="<?php echo $topicDetailEdit[0]['topicData'][0]['msgTxt'];?>" validateSingleChar='true'><?php echo $topicDetailEdit[0]['topicData'][0]['msgTxt'];?></textarea>
                          <div id="questionTextD_tips" style="width: 269px; height: 0px; display: block; position: absolute; top: 68px; right: 67px;"></div>
			<?php } ?>
		    </div>
		    <div class="row errorPlace"><span id="questionTextD_error" class="errorMsg">&nbsp;</span></div>
		    <?php if($entityType != 'user' && $entityType != 'question' && $entityType=='discussion'){ ?>
		    <div class="wdh100 mb5">
			<div style="margin-bottom:5px;margin-top:10px;"><b>Enter <?php echo $postHeading;?></b></div>
			<textarea class="anaTxtArea_2" style="" name="questionDescD" id="questionDescD" autocomplete="off" onkeyup="try{ $('questionDescD_error').parentNode.style.display='none'; checkForNameMention(event,this,'questionDescD'); return false;} catch (e){}" profanity="true" validate="validateStr" caption="<?php echo $postHeading;?>" maxlength="2500" minlength="5" required="true"  onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter <?php echo $postHeading;?>" value="<?php echo $topicDetailEdit[0]['topicData'][0]['description'];?>" validateSingleChar='true'><?php echo $topicDetailEdit[0]['topicData'][0]['description'];?></textarea>
		    </div>
		    <div class="row errorPlace"><span id="questionDescD_error" class="errorMsg">&nbsp;</span></div>
            <?php }else if($entityType != 'user' && $entityType != 'question'){ ?>
            <div class="wdh100 mb5">
                        <div style="margin-bottom:5px;margin-top:10px;"><b>Enter <?php echo $postHeading;?></b></div>
                                    <textarea class="anaTxtArea_2" style="" name="questionDescD" id="questionDescD" autocomplete="off" onkeyup="try{ $('questionDescD_error').parentNode.style.display='none';textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="<?php echo $postHeading;?>" maxlength="2500" minlength="5" required="true"  onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter <?php echo $postHeading;?>" value="<?php echo $topicDetailEdit[0]['topicData'][0]['description'];?>" validateSingleChar='true'><?php echo $topicDetailEdit[0]['topicData'][0]['description'];?></textarea>
                        </div>
            <div class="row errorPlace"><span id="questionDescD_error" class="errorMsg">&nbsp;</span></div>

		    <?php }elseif(isset($topicDetailEdit[0]['description'])){ //echo "<pre>";print_r($topicDetailEdit[0]['description']);echo "</pre>";?>
                    <div class="wdh100 mb5">
			<div style="margin-bottom:5px;margin-top:10px;"><b>Enter <?php echo $postHeading;?></b></div>
			<textarea class="anaTxtArea_2" style="" name="questionDescD" id="questionDescD" autocomplete="off" onkeyup="try{ $('questionDescD_error').parentNode.style.display='none';textKey(this); } catch (e){}" profanity="true" validate="validateStr" caption="<?php echo $postHeading;?>" maxlength="300" minlength="2" caption="Description" onblur="checkTextElementOnTransition(this,'blur');enterEnabled=false;" onfocus="checkTextElementOnTransition(this,'focus');try{ enterEnabled=true; }catch (e){}" default="Enter <?php echo $postHeading;?>" value="<?php echo $topicDetailEdit[0]['topicData'][0]['description'];?>" validateSingleChar='true'><?php echo $topicDetailEdit[0]['description'][0]['description'];?></textarea>
                        <div class="row errorPlace"><span id="questionDescD_error" class="errorMsg">&nbsp;</span></div>
		    </div>
                    <?php }?>
		</div>
		<!--country id will be 2 for all articles-->
		<div style="display: none;">
			<span><b><?php echo $titleWord;?> is related to : </b></span>
			<span style="padding-left:5px"><input type="radio" name="siI" onclick="showHideCountry(); " id="study_india" value="study_india" style="position:relative;top:2px" <?php echo $indiaRadio; ?>/> Study in India</span>
			<span style="padding-left:5px"><input type="radio" onclick="showHideCountry();" id="study_abroad" value="study_abroad"  name="siI" style="position:relative;top:2px" <?php echo $abroadRadio; ?> /> Study Abroad</span>

		</div>
		
		<div style="width:100%">
			<div id="country_combo" style="display:none;">
			<select name="countryListForCreateTopic[]" id="countryListForCreateTopic" tip="question_country" style="width:139px;" caption="country" validate="validateSelect" onChange="checkDropDown(this,''); ">
			<option value="">Select Country</option>
			<?php
			foreach($countryList as $country => $value) {
				$countryId = $country; $countryName = $value;
				if (($countryId != '1') && ($countryId != '2')) {
				  $selectCountry = "";
				  if($countryId==$topicCountryId){$selectCountry="selected";}
				?>
				<option value="<?php echo $countryId; ?>" <?php echo $selectCountry; ?>  >
				<?php echo $countryName; ?></option>
				<?php
				}
				}
				?>
			</select>
			</div>
			<div class="row" style="display:none;">
			<div id="countryListForCreateTopic_error" class="errorMsg"></div>
			</div>
		</div>
                <div>
                    <div style="float:left;width:250px;margin-right:16px" id="category-subcategory-div-ana">
                        <div style="width:100%">
                            <div style="padding-top:10px"><b>Category</b></div>
                            <div style="padding-top:3px">
								<select size="1" required="true" validate="validateSelect" name="catselect" style="width:240px;" caption="category" minlength="1" id = "catselect" onChange = "changeSelection('category',this.value);">
                                <option value = '' selected="selected">Select a Category</option>
                                <?php foreach($categoryList as $value) {
								if($value['parentId'] == 1){
                                if($value['categoryID'] == $selectedcategoryId){
                                $selectedPage = $value['page'];
                                ?>
                                <option selected = 'true' value = "<?php echo $value['categoryID']?>"><?php echo $value['categoryName']?></option>
                                <?php $selectedCategoryName = $key;} else { ?>
                                <option value = "<?php echo $value['categoryID']?>" ><?php echo $value['categoryName']?></option>
                                <?php }
                                }
								}
                                ?>
                                </select>

                                <div style="margin-top:2px;display:none;">
                                    <div class="errorMsg" id="catselect_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="float:left;width:225px">
                        <div style="width:100%">
                            <div style="padding-top:10px"><b>Sub-Category</b></div>
                            <div style="padding-top:3px">
                                <select size="1" required="true" validate="validateSelect" style="width:220px;"
                                 id = "subcatselect" name="selectCategory" caption="sub category" minlength="1" onClick = "checkDropDown(this,'','editSubCategory');" onChange = "changeSelection('subcategory',this.value);"><option value = '' selected="selected">Select a sub category</option>
                                 <?php
                                    for($i = 0;$i < count($categoryList);$i++) {      if($categoryList[$i]['parentId'] == $selectedcategoryId) {  if($categoryList[$i]['categoryID'] == $selectedsubcategoryId) { ?>
                                    <option selected = 'true' value = "<?php echo $categoryList[$i]['categoryID']?>"><?php echo $categoryList[$i]['categoryName']?></option>
                                    <?php $selectedSubCategoryName = $categoryList[$i]['categoryName'];
                                    } else { ?>
                                    <option value = "<?php echo $categoryList[$i]['categoryID']?>"><?php echo $categoryList[$i]['categoryName']?></option>
                                    <?php }}} ?>
                                    </select>
                                    <div style="margin-top:2px;display:none;">
                                        <div class="errorMsg" id= "subcatselect_error"></div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
		    <div>
		<?php if($isCmsUser==1 && $main_message['fromOthers'] == 'user'){?>
                    <?php if($main_message['listingTypeId']==0 || $main_message['listingType']==''){?>
                        <span><input type="checkbox" id="questionMoveToIns" name="questionMoveToIns" onClick="showHidecourseInsDiv('questionMoveToIns');"/><strong>Move to Listings</strong></span>
                   <?php }else{ ?>
                        <span><input type="checkbox" id="questionMoveToCafe" name="questionMoveToCafe" /><strong>Move to Cafe</strong></span>
                   <?php } ?>
                   <?php } ?>
                    </div>
                    <div id="courseInsIdDiv" style="display:none">
                        <div>
                        <strong>Course Id :</strong><input type="text" id="courseIdDiv" name="courseId" validate="validateInteger" caption="Course Id" maxlength="11" minlength="1"/>
                                <div style="margin-top: 2px; display: none;">
                                    <div id="courseIdDiv_error" class="errorMsg">Please Enter Course Id</div>
                                </div>
</div>
<div>
                         <strong>Institute Id :</strong><input type="text" id="instIdDiv" name="instId" validate="validateInteger" caption="Institute Id" maxlength="11" minlength="1"/>
                                <div style="margin-top: 2px; display: none;">
                                    <div id="instIdDiv_error" class="errorMsg">Please Enter Institute Id</div>
                                </div>

</div>
                    </div>
                </div>
                <div style="line-height:10px;overflow:hidden">&nbsp;</div>
            </div>
        </div>
</div>
<script>
function showHidecourseInsDiv(id){
        if($(id).checked){
                $('courseInsIdDiv').style.display='';
                $('courseIdDiv').setAttribute("required","true");       
                $('instIdDiv').setAttribute("required","true");
        }else{
                $('courseInsIdDiv').style.display='none';
                $('courseIdDiv').removeAttribute("required");
                $('instIdDiv').removeAttribute("required");
        }
}
categoryList = eval(<?php echo json_encode($categoryList); ?>);
categoryListIndia = eval(<?php echo json_encode($categoryListIndia); ?>);
categoryListAbroad = eval(<?php echo json_encode($categoryListAbroad); ?>);

var topicCountryId = '<?php echo $topicCountryId?>';
if(topicCountryId>2) {
    $('country_combo').style.display = 'none';
}

</script>

<div style="padding-top:10px;width:520px;">
    <div style="padding-left:0px">
            <input type="button" onclick="
		  $('mainCreateTopicButton').disabled = true;
		  if(!isValidatingPost){
                    if($('questionDescD')){
                        if($('questionDescD').value == 'Enter Description'){
                            $('questionDescD').value = '';
                        }
                    }
		    if(AnA_PQ_validateEditTopic(document.getElementById('askQuestionFormPost')))
			return true;
		    else
		    {
			$('mainCreateTopicButton').disabled = false;
			return false;
		    }
		  }" id="mainCreateTopicButton" class="fbBtn" value="Update"/>&nbsp; &nbsp;
	    <a onclick="hideOverlayAnA();" href="javascript:void(0);">Cancel</a>
    </div>
</div>
</form>
<script>
if($('questionDescD')){

validateForTips('questionTextD');
function checkForTips(id){
validateForTips(id);
return;
}

}
</script>

<?php
if($entityType=='discussion'){
$discussionTopicText = html_entity_decode(html_entity_decode($topicDetailEdit[0]['topicData'][0]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
$textT = formatQNAforQuestionDetailPage($discussionTopicText,32);
$discussionText = html_entity_decode(html_entity_decode($topicDetailEdit[0]['topicData'][0]['description'],ENT_NOQUOTES,'UTF-8'));
$textD = formatQNAforQuestionDetailPage($discussionText,600);
?>
<script>
var questionTextDVal = removeTagsFromText('<?php echo $textT;?>');
var questionDescDVal = removeTagsFromText('<?php echo $textD;?>');</script>
<?php
}
?>



