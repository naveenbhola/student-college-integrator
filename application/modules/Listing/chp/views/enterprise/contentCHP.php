<div class="abroad-cms-rt-box">
     <div class="flRt">
        <a href="/chp/ChpCMS/viewList" class="orange-btn" style="padding:6px 7px 8px">View All CHP</a>
         <a href="/chp/ChpCMS/addCHP" class="orange-btn" style="padding:6px 7px 8px"> +Add CHP</a>
         <a href="/chp/ChpCMS/manageSeoCHP" class="orange-btn" style="padding:6px 7px 8px"> Manage SEO </a>        </div>
</div>

<div class="cms-form-wrapper clear-width">

<form id ="form_addCHPContent" name="addCHPContent" action=""  method="POST" enctype="multipart/form-data">

<div class="clear-width" id="chp_basic_section">

	<div class="abroad-cms-head" style="overflow: visible;">
        <h1 class="abroad-title">Manage CHP Content</h1>
		<div class="last-uploaded-detail">
			<p><br>*Mandatory<br>
		<span><input style="cursor: pointer;" id="check_po" type="checkbox" name="check_po"><label style="display: inline-block;top: -2px;position: relative;">Collapse all sections</label></span>
			</p>
		</div>
    </div>

    <div id="basicInfo_addCHPPage" class="cms-form-wrap">
    	<div style="float: right;"><a class="orange-btn" href="/chp/ChpCMS/viewList">Back</a></div>
	    <ul>
			<li>
			        <label>CHP Name : </label>
			        <div class="cms-fields">
			        	<?php echo $content['name'];?>
			        	<input type="hidden" name="chpId" id="chpId" value="<?php echo $content['chpId'];?>">
			        	<input type="hidden" name="chpName" id="chpName" value="<?php echo $content['name'];?>">
			        </div>
			</li>
			<li>
				 <label>CHP Display Name* : </label>
			        <div class="cms-fields">
						  <span id="edit_displayName"><?php echo $content['displayName'];?></span>
						  <input type="hidden" style="width:50%" name="chp_displayName" id="chp_displayName" value="<?php echo $content['displayName'];?>">
			            <div id="chpDisplayName_error" class="errorMsg err" style="display:none;">Please enter CHP display Name / Special characters not allowed.</div>
			            <a style="margin-left: 50px;" id="edit_displayNameBtn" href="javascript:void(0);">Edit</a>
			            <span style="margin-left: 75px;"> <strong>Allowed Characters :</strong>  [0-9a-zA-Z\s/()&amp;.,#+] </span>
			        </div>
			</li>
			<li>
				 <label>Upload Header Image : </label>
			        <div class="cms-fields">
						<input name="chpImage" type="file" id="chpImage" onchange="uploadHeaderImage(chpImage)">
						<input type="hidden" id="imageUrl" value="<?php echo $content['imageUrl']?>">
			            <div id="chpImage_error" class="errorMsg" style="display:none;"></div>
			            <?php if($content['imageUrl']){
			            	$imgUrl = (ENVIRONMENT !='production') ? 'https://shikshatest02.infoedge.com'.$content['imageUrl'] : 'https://images.shiksha.com'.$content['imageUrl'];?>
			            	<div id="imgContainer" style="margin-top: 10px;">Image URL : <span id="displayImg"><a target="_blank" href="<?php echo $imgUrl;?>" title="View Image"><?php echo $imgUrl;?></a></span><a href="javascript:void(0);" style="margin-left: 10px; color: #000;font-weight: bold;" title="Remove image" onclick="removeHeaderImage()">X</a></div>
			        	<?php }?>
			        </div>
			</li>
		</ul>
	</div>
</div>	             
	<?php foreach ($content['sectionMapping']['homePage']['wikiData'] as $key => $value) {
		if(in_array($value['labelName'], array('OverView','Eligibility'))){
			$homePageData[$value['labelName']] = $value['labelValue'];
		}else{
			$customLable[$value['labelName']] = $value['labelValue'];
		}
	}?>
<div class="clear-width">
	<div class="clear-width" id="chp_homepage_section">
	    <select id="homePage_sort" class="cms-dropdown sort" name="position[]" onchange="changePosition(1,'homePage_sort','section')">
	            <option value="1" selected="selected">1</option>
	    </select>
	    <h3 class="section-title" style="cursor:pointer;" id="hompepageTitle"><i class="abroad-cms-sprite minus-icon"></i>HomePage</h3>
	    <div id="chp_homepage" class="cms-form-wrap cms-accordion-div" style="display: block;">
	        <ul>
	            <li>
	                <label><span>OverView</span> :</label>
	                <div class="cms-fields">
                    <textarea id="chp_OverView" name="wikiField[]" class="cms-textarea tinymce-textarea" caption="CHP <?php echo $wiki['labelName'];?>" validationType="html" required="true"><?php echo $homePageData['OverView'];?></textarea>
                    <div id="chp_<?php echo $wiki['labelName'];?>_error" class="errorMsg err" style="display:none;">Please enter OverView.</div>
                	<div style="width:120px;margin-left: -5px;margin-top:15px;position: relative;"><input type="checkbox" value="on" id="updatedOn_OverView"> Update Date</div><br>
                	</div>  	
	            </li>
	            <?php $lableId = 1;foreach ($customLable as $labelName => $wiki) {?>
	            	<li class="custom-wiki homepage-custom-wiki-section1">
	                <label id="cstmlbl_<?php echo $lableId;?>"><span data-labelId="cstmlbl_<?php echo $lableId;?>"><?php echo $labelName?></span> :</label>
	                <div class="cms-fields">
                    <textarea id="customWikiData1_<?php echo $lableId;?>" name="wikiField[]" class="cms-textarea tinymce-textarea custom-wiki-data" validationType="html" required="true"><?php echo $wiki;?></textarea>
                     <a href="javascript:void(0);" style= "margin:10px 0 0 0;" class="remove-link-2" onclick="removeHomePageWiki(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                	</div>  	
	            </li>

	            <?php $lableId++;}?>
	            <li>
	            	<div class="cms-fields">
	            		<a href="javascript:void(0);" id="addMoreSectionLink_1" onclick="addMoreHomePageSection(this, 1);" style="margin-bottom:0;">[+] Add More</a>
		            </div><br>
	            </li>
	           <li>
	            	<label><span>Eligibility</span>:</label>
	                <div class="cms-fields">
                    	<textarea id="chpEligibility" name="wikiField[]" class="cms-textarea tinymce-textarea" caption="CHP Info" validationType="html" required="true"><?php echo $homePageData['Eligibility'];?></textarea>
                    	<div id="chpEligibility_error" class="errorMsg" style="display:none;"></div>
                		<div style="width:120px;margin-left: -5px;margin-top:15px;position: relative;"><input type="checkbox" value="on" id="updatedOn_Eligibility"> Update Date</div><br>
                	</div>

	            </li>
	            <li class="homepage-custom-wiki custom-wiki" style="display:none;">
                    <div class="add-more-sec2 clear-width">
                        <ul>
                            <li>
                                <label>Label : </label>
                                <div class="cms-fields">
                                  <input class="universal-txt-field cms-text-field custom-label-field" type="text" style="98% !important;" maxlength="100" onkeyup="isDuplicateLable(this);" />
                                  <div class="errorMsg cstmlbl_error" style="display:none;">Sorry, this custom lable is already exists.</div>
                                </div>
                             </li>
                            <li>
                                <label>Wiki Data :</label>
                                <div class="cms-fields">
                                    <textarea class="cms-textarea tinymce-textarea custom-wiki-data" validationType="html"></textarea>
                                    <a href="javascript:void(0);" style= "margin:10px 0 0 0;" class="remove-link-2" onclick="removeHomePageWiki(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
	        
	        </ul>
	    </div>
	</div>

   <?php 
        $i=2;
   		foreach ($content['sectionOrder'] as $key => $sectionName) { if($sectionName == 'homePage'){continue;}
   		$sectionNameId = str_replace(' ', '', $sectionName);

   		if($sectionName == 'salary' && count($content['sectionMapping'][$sectionName]['wikiData'])>0){
   			foreach ($content['sectionMapping'][$sectionName]['wikiData'] as $key => $value) {
   				if($value['labelName'] != 'salary'){
   					$customSalaryData[] = array('labelName'=>$value['labelName'],'labelValue'=>$value['labelValue']);
   				}
   			}
   		}

   		?>
			<div class="clear-width <?php if($sectionName == 'salary'){?> chp-salary-section <?php }else{?> chp-section <?php }?>">
			    <select id="<?php echo $sectionNameId.'_sort';?>" class="cms-dropdown sort" name="position[]" onchange="changePosition(<?php echo $i;?>,'<?php echo $sectionNameId.'_sort';?>','section')">

		    		<?php for($j=2;$j<=(count($content['sectionOrder']));$j++){?>
		            	<option value="<?php echo $j?>"  <?php if($i == $j){?> selected="selected" <?php } ?> ><?php echo $j?></option>
		    		<?php } $i++;?>

			    </select>

			    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i><?php echo $secionMapping[$sectionName];?></h3>
			    <div class="cms-form-wrap cms-accordion-div" style="display: block;">
			        <ul>
			            <li>
			            	<!-- <label><span><?php echo $value?></span>* :</label> -->
			                <div class="cms-fields">
		                    <textarea id="<?php echo $sectionNameId?>" name="wikiField[]" class="cms-textarea tinymce-textarea" caption="CHP Info" validationType="html" required="true">
		                    	<?php if($sectionName == 'salary' && $content['sectionMapping'][$sectionName]['wikiData'][0]['labelName'] == 'salary'){ echo $content['sectionMapping'][$sectionName]['wikiData'][0]['labelValue'];}else if($sectionName !='salary'){
		                    			echo $content['sectionMapping'][$sectionName]['wikiData'][0]['labelValue'];
			                    	}?></textarea>
		                	<div style="width:120px;margin-left: -5px;margin-top:15px;position: relative;"><input type="checkbox" value="on" name="updatedOn[]" id="updatedOn_<?php echo $sectionNameId;?>"> Update Date</div><br>
		                	</div>
			            </li>
			            <?php $label = 1;foreach ($customSalaryData as $key => $v) {?> 
			            <li class="custom-wiki homepage-custom-wiki-section2">
			            	<label><span><?php echo $v['labelName']?></span> :</label>
			                <div class="cms-fields">
		                    
		                    <textarea id="customWikiData2_<?php echo $label;?>" name="wikiField[]" class="cms-textarea tinymce-textarea custom-wiki-data" caption="CHP Info" validationType="html" required="true"><?php echo $v['labelValue'];?></textarea>

                     		<a href="javascript:void(0);" style= "margin:10px 0 0 0;" class="remove-link-2" onclick="removeHomePageWiki(this);"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
		                	</div>
			            </li>
			            <?php $label++; } if($sectionName == 'salary'){?>
                        <li>
                            <div class="cms-fields">
                                <a href="javascript:void(0);" id="addMoreSectionLink_2" onclick="addMoreHomePageSection(this, 2);" style="margin-bottom:0;">[+] Add More</a>
                            </div>
                        </li>   
                        <?php }?>
			            
			        </ul>
			    </div>
			</div>
	<?php }?>		
           

</div>
</form>
</div>

<div class="clear-width" id="chp_pc_section">
    <div class="cms-form-wrap" style="display: block;">
        <ul>
            <li>
                <label><span>User Comment</span>* :</label>
                <div class="cms-fields">
                <textarea id="chp_comment"class="cms-textarea" caption="CHP Info" validationType="html" required="true"></textarea>
                <div id="comment_error" class="errorMsg err" style="display:none;">Please enter comment.</div>
                <div id="wiki_error" class="errorMsg err" style="display:none;">Custom Lable/Wiki field couldn't be blank.</div>
            	</div>
            </li>
            <li><div class="cms-fields" id="msg"></div></li>
        </ul>
    </div>
    <?php if(!empty($errorMsg)){?>
    	<div class="button-wrap" style="margin-top: -20px;color:red">
			<?php echo $errorMsg?>
		</div>	
    <?php }else{?>
    	<div class="button-wrap" style="margin-top: -20px;">
			<a href="JavaScript:void(0);" onclick="saveContent();" class="orange-btn" id="chpSaveBtn">Save</a>
			<a href="JavaScript:void(0);" class="cancel-btn" onclick="unlockCHPContent(<?php echo $chpId?>)">Cancel</a>
		</div>	
	<?php }?>

</div>

<script type="text/javascript">
	var formname = "addCHPContent";
</script>

