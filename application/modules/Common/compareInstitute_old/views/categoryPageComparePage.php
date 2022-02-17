	<div class="compare-top-sticky" id="comparePageTop" style="display: none;">
	        <table class="compare-table" width="100%" border="1" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="165" align="center" valign="middle" >
                        <div class="compare-items">
			    <?php if($validateuser == 'false') { if(count($institutes)<=1){ ?>
                            <a  href="javascript:void(0)" class="email-cmpr-disabled" style="cursor:default;">
                                <i class="compare-sprite email-icon"></i>
                                <span>Email this Comparison</span>
                            </a>
			    <?php }else{ ?>
                            <a href="javascript:void(0)" class="email-cmpr" onclick="trackEventByGA('LinkClick','COMPARE_PAGE_EMAIL_STICKY'); emailMeCompareLayer(); return false;">
                            	<i class="compare-sprite email-icon"></i>
                            	<span>Email this Comparison</span>
                            </a>
			    <?php }} ?>
                        </div>
                    </td>
			<?php
			$j = 0;$isSAComparePage = 0;
			$filled_compares = 0;
			$subcatIdArray = array();
			foreach($institutes as $institute){
				$filled_compares ++ ;
				$j++;
				$course = $institute->getFlagshipCourse();
				$courseArray[]= $course->getId;
				$dominantSubCatArray = $course->getDominantSubcategory();
				$subcatIdArray[] = $dominantSubCatArray->getId();
				$instituteName[] = $institute->getName();
				$courseNameArray[] = $course->getName();
				$course->setCurrentLocations($request);
				if(strlen($institute->getName()) > 100){
					$instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($institute->getName()));
					$instStr .= "...";
				}else{
					$instStr = html_escape($institute->getName());
				}
				if($_COOKIE["applied_".$course->getId()] == 1){
					$className = "requested-e-bro";
				}else{
					$className = "";
				}
				$classLast = "";
				if($j==4){
					$classLast = "last";
				}
			?>
                    <td width="165" valign="top" class="<?=$className?> <?=$classLast?>">
                        <div class="compare-items">
                        <div class="details">
                            <p class="remove-inst"><a href="javascript:void(0);" onClick="trackEventByGA('LinkClick','COMPARE_PAGE_REMOVE_COLLEGE_STICKY'); removeCollege('<?=$j?>');">remove college<span>&times;</span></a></p>
                            <p class="compare-inst-title">
                            	<a href="<?=$course->getURL()?>" target="_blank" title="<?=htmlspecialchars($institute->getName())?>"><?=$instStr?></a>
                            	<span><?=$course->getCurrentMainLocation()->getCity()->getName()?></span>
                            </p>
                        </div>				

				<?php if($brochureURL->getCourseBrochure($course->getId())){ ?>			    
				<?php
				if($className == "requested-e-bro"){
				?>
					<p style="color: #ff1a15;font-size: 17px;line-height: 22px;">E-brochure Sent</p>
				<?php
				}else{
				?>
					<div id="reb_button_<?=$course->getId()?>_other"><input type="button" title="Download E-brochure" value="Download E-brochure" class="comp-orange-btn" onclick="trackEventByGA('LinkClick','COMPARE_PAGE_EBROCHURE_STICKY'); ApplyNowCourse('<?php echo $institute->getId(); ?>','<?php echo base64_encode(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()));?>','<?php echo $course->getId(); ?>','<?php echo base64_encode(htmlspecialchars($course->getName())); ?>','<?=$course->getURL()?>',210);" /></div>
				<?php
				}
				?>
				<?php } ?>
                        </div>
                    </td>
		    <?php } ?>
		    
		    <?php
		    $allowAutoSuggest = true;
		    if($j<4){
			while ($j < 4){
				$j++;
			?>
                    <td width="165" valign="top" style="border:0px;" <?php if($allowAutoSuggest){ ?>id="newInstituteSectionSticky"<?php } ?>>
                        <div class="compare-items">
                            <div class="inst-numb"><?=$j?></div>
                            <div class="similar-college" style="position: relative;">
				<?php if($allowAutoSuggest){ ?>
					<input type="text" class="name-txtfield" value="Enter College Name" default="Enter College Name" id="keywordSuggest1" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');"/>
					<div id="suggestions_container1" class="suggestion-box" style="position:absolute; left:0px; top:32px;background-color:#fff;width:280px;z-index:20;display:none;"></div>
					<?php
					if(isset($institutesRecommended) && count($institutesRecommended)>0 ){
					?>
					<p class="or-sep">or</p>
					<div style="position:relative" id="recommendationDivLinkSticky">
						<a href="javascript:void(0)" onClick="$j('#recommendationDivSticky').show();">Select from similar colleges</a>
					</div>
					<?php $this->load->view('receommendations', array('keyVal'=>'Sticky')); ?>
					<?php } ?>
				<?php }else{ ?>
					<input type="text" class="name-txtfield" value="Enter College Name" default="Enter College Name" id="keywordSuggest1" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" disabled/>
					<div id="suggestions_container1" style="position:absolute; left:7px; top:44px;background-color:#fff;display:none;"></div>
				<?php } ?>
                            </div>
                        </div>
                    </td>
			<?php
				$allowAutoSuggest = false;
			}
		    }
		    ?>	    		    
		    
                </tr>
	    </table>
	</div>


        <div id="compare-cont">
        
        
		<!-- Breadcrumb -->
        	<div class="back-inst">
        		<a href="<?=SHIKSHA_HOME?>">Home </a>
			<span class="breadcrumb-arrow" style="margin-right:0px;">&gt;</span>
			<?php if(!$isStaticPage){ ?>Compare Colleges<?php }else{ ?>
			<a href="<?=SHIKSHA_HOME?>/compare-colleges">Compare Colleges </a>
			<span class="breadcrumb-arrow" style="margin-right:0px;">&gt;</span>
			<?=$seoDetails['breadcrumb']?>
			<?php } ?>
		</div>

		<div id="confirmation-box-wrapper"></div>
        
		<!-- Header of the page -->
        	<div>
			<?php
			$heading = (isset($seoDetails['heading']))?$seoDetails['heading']:"Compare Colleges";
			?>
			<div class="comp-title">
				<h1 style="line-height: 28px;"><?=$heading?></h1>
				<?php if(!($institutes && count($institutes)>0)){ ?>
				<p>Confused between colleges? Compare them here to make the right choice.<br/>
				You can compare a maximum of 4 colleges at a time.</p>
				<?php } ?>
			</div>
			<div class="flRt" style="margin-top:6px; <?php if(!($institutes && count($institutes)>0)){ echo "display:none;";} ?>" id="shareWidgetDiv">
				<?php $this->load->view('shareWidget');?>
			</div>
		</div>
		

	    
	    
            <table cellpadding="0" cellspacing="0" width="100%" border="1" class="compare-table">
                <tr>
                    <td width="165" align="center" valign="middle" >
                        <div class="compare-items" id="email-compare">
			    <?php if($validateuser == 'false') { if(count($institutes)<=1){ ?>
                            <a  href="javascript:void(0)" class="email-cmpr-disabled" style="cursor:default;">
                                <i class="compare-sprite email-icon"></i>
                                <span>Email this Comparison</span>
                            </a>
			    <?php }else{ ?>
                            <a href="javascript:void(0)" class="email-cmpr" onclick="trackEventByGA('LinkClick','COMPARE_PAGE_EMAIL_CLICK'); emailMeCompareLayer('<?=$emailTrackingPageKeyId;?>'); return false;">
                            	<i class="compare-sprite email-icon"></i>
                            	<span>Email this Comparison</span>
                            </a>
			    <?php }} ?>
                        </div>
                    </td>
		    <script>
			var emailDataArray = new Array();
			var cookieDataArray = new Array();
		    </script>
			<?php
			$j = 0;$isSAComparePage = 0;
			//$localityArray = array();
			foreach($institutes as $institute){
				$j++;
				$course = $institute->getFlagshipCourse();
				$course->setCurrentLocations($request);
				//$localityArray[$course->getId()] = getLocationsCityWise($course->getCurrentLocations());
				if(strlen($institute->getName()) > 100){
					$instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($institute->getName()));
					$instStr .= "...";
				}else{
					$instStr = html_escape($institute->getName());
				}
				if($_COOKIE["applied_".$course->getId()] == 1){
					$className = "requested-e-bro";
				}else{
					$className = "";
				}
				$classLast = "";
				if($j==4){
					$classLast = "last";
				}
			?>
		    <script>
			var stringTemp = '<?=$course->getInstId()?>::<?=$course->getId()?>::<?=html_escape($institute->getName())?>::<?php echo $course->getCurrentMainLocation()->getCity()->getId();?>::<?=$course->getCurrentMainLocation()->getLocality()->getId()?>';
			emailDataArray.push(stringTemp);
			var cookieValTemp = "<?=$institute->getId().'::'.$course->getId().'::'.($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'').'::'.html_escape($institute->getName()).', '.$course->getCurrentMainLocation()->getCity()->getName().'::'.$course->getId().'::'.$course->getURL()?>";
			cookieDataArray.push(cookieValTemp);
			updateTrackingPageKey('<?=$course->getId();?>');
		    </script>
                    <td width="165" valign="top"  class="<?=$className?> <?=$classLast?>">
                        <div class="compare-items">
                        <div class="details">
                            <p class="remove-inst"><a href="javascript:void(0);" onClick="trackEventByGA('LinkClick','COMPARE_PAGE_REMOVE_COLLEGE_CLICK'); removeCollege('<?=$j?>');">remove college<span>&times;</span></a></p>
                            <p class="compare-inst-title">
                            	<a href="<?=$course->getURL()?>" target="_blank" title="<?=htmlspecialchars($institute->getName())?>"><?=$instStr?></a>
                            	<span><?=$course->getCurrentMainLocation()->getCity()->getName()?></span>
                            </p>
                            <p class="year-color"><?php if($institute->getEstablishedYear()){ echo "Year of Establishment: ".$institute->getEstablishedYear();}?></p>
                        </div>				
			    
				<?php if($brochureURL->getCourseBrochure($course->getId())){ ?>			    
				<?php
				if($className == "requested-e-bro"){
				?>
					<p style="color: #ff1a15;font-size: 17px;line-height: 22px;">E-brochure Sent</p>
				<?php
				}else{
				?>
					<div id="reb_button_<?=$course->getId()?>"><input type="button" title="Download E-brochure" value="Download E-brochure" class="button-style" onclick="trackEventByGA('LinkClick','COMPARE_PAGE_EBROCHURE_CLICK'); ApplyNowCourse('<?php echo $institute->getId(); ?>','<?php echo base64_encode(htmlspecialchars($institute->getName().", ".$course->getCurrentMainLocation()->getCity()->getName()));?>','<?php echo $course->getId(); ?>','<?php echo base64_encode(htmlspecialchars($course->getName())); ?>','<?=$course->getURL()?>',210);" /></div>
				<?php
				}
				?>
				<?php } ?>
			    
                        </div>
                    </td>
		    <?php } ?>
		    
		    <?php
		    $allowAutoSuggest = true;
		    if($j<4){
			while ($j < 4){
				$j++;
			?>
                    <td width="165" valign="top"  style="border:0px;" <?php if($allowAutoSuggest){ ?>id="newInstituteSection"<?php } ?> >
                        <div class="compare-items">
                            <div class="inst-numb"><?=$j?></div>
                            <div class="similar-college" style="position: relative;">
				<?php if($allowAutoSuggest){ ?>
					<input type="text" class="name-txtfield" value="Enter College Name" default="Enter College Name" id="keywordSuggest" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');"/>
					<div id="suggestions_container" class="suggestion-box" style="position:absolute; left:0px; top:32px;background-color:#fff;width:280px;z-index:20;display:none;"></div>
					<?php
					if(isset($institutesRecommended) && count($institutesRecommended)>0 ){
					?>
					<p class="or-sep">or</p>
					<div style="position:relative" id="recommendationDivLinkNormal">
						<a href="javascript:void(0)" onClick="$j('#recommendationDivNormal').show();">Select from similar colleges</a>
					</div>
					<?php $this->load->view('receommendations',array('keyVal'=>'Normal')); ?>
					<?php } ?>
				<?php }else{ ?>
	                                <input type="text" class="name-txtfield" value="Enter College Name" default="Enter College Name" id="keywordSuggest" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" disabled/>
					<div id="suggestions_container" style="position:absolute; left:7px; top:44px;background-color:#fff;display:none;"></div>
				<?php } ?>
                            </div>
                        </div>
                    </td>
			<?php
				$allowAutoSuggest = false;
			}
		    }
		    ?>	    		    
		    
                </tr>
		
                <tr id="importantInfoDiv" <?php if(!($institutes && count($institutes)>0)){ echo "style='display:none;'";} ?> >
                	<td class="compare-title last" colspan="5"><h2>Important Information</h2></td>
                </tr>

		<tr id="courseDisplayDiv" <?php if(!($institutes && count($institutes)>0)){ echo "style='display:none;'";} ?>>
			<td width="165" valign="top">
			    <div class="compare-items"><label>Course Name</label>
			    </div>
			</td>
			<?php
			$j = 0;$k = 0;
			foreach($institutes as $institute){
				$k++;
				$course = $institute->getFlagshipCourse();
				$courseId = $course->getId();
				if( isset( $courseLists[$courseId] ) && count($courseLists[$courseId])>0 ){
					$j++;
			?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <div class="compare-items">
				<select class="custom-select" style="width: 174px;" id="courseSelect<?=$j?>" onChange="resetComparePage();">
					<?php
						foreach ($courseLists[$courseId] as $courseD){
							$selected = "";
							if($courseD['course_id']==$courseId){
								$selected = "selected='selected'";
							}
					?>
						<option title="<?=$courseD['courseTitle']?>" value='<?=$courseD['course_id']?>' <?=$selected?> ><?=$courseD['courseTitle']?></option>
					<?php	}
					?>
				</select>
			    </div>
			</td>
			<?php }else{ ?>
			<td align="center" valign="top" <?php if($k==4){echo "class='last'";} ?>>
			    <strong style="font-size:22px; color:#828282">-</strong>
			</td>
			<?php } }
			$showId = true;
			if($j<4){	//Case when Compare tool has less than 4 courses to compare
				for ($x = $k+1; $x <=4; $x++){
					echo '<td width="165" align="center" valign="top" style="border:0px;" ';
					if($x==4){echo "class='last'";}
					if($showId){ echo " id='newCourseSection' ";}
					echo '>&nbsp;</td>';
					$showId = false;
				}
			}
			?>
		</tr>
               		
                
		<?php
		//We will have to check the Category of the First course. If it is MBA, we will load another view
		if($showMBA){
		    $this->load->view('compareFieldsMBA');
		}
		else{
		    $this->load->view('compareFieldsDefault');
		}
		?>
                
		<?php if($institutes && count($institutes)>0){ 
			$campusRepExists = 'false';
			for($i = 0; $i < count($institutes) ;$i++){
				if($campusRepList[$i]['caInfo'])
					$campusRepExists = 'true';
			}
			if($campusRepExists == 'true'){
				$this->load->view('compareInstitute/campusRepWidget');
			}
                         
		        $this->load->view('compareInstitute/comparePageVoteButton');
		 
		  } ?>
	               
               </table>
            
		<?php if($institutes && count($institutes)>0){
                        $this->load->view('common/comparisionFeedback');
			if(isset($mainCourseSubCatId) && $mainCourseSubCatId>0){
				echo Modules::run('compareInstitute/compareInstitutes/getPopularCoursesForComparision',$mainCourseSubCatId);
			}
		}else{ ?>
		<div style="margin-top: 60px;">
		</div>
		<?php
			echo Modules::run('compareInstitute/compareInstitutes/getPopularCoursesForComparision',23);
			echo Modules::run('compareInstitute/compareInstitutes/getPopularCoursesForComparision',56);		
		} ?>
            
        </div>
	
	<img id = 'tracking_img' src="/public/images/blankImg.gif" width=1 height=1 >

<script>
var filled_compares = <?php echo $filled_compares; ?>;
	
	<?php if(count($institutes)<4){ ?>
      
        //Event listener for hiding dropdown suggestions when user clicks outside the suggestion container
        if(typeof(handleClickForAutoSuggestor) == "function") {
            if(window.addEventListener){
                document.addEventListener('click', handleClickForAutoSuggestor, false);
            } else if (window.attachEvent){
                document.attachEvent('onclick', handleClickForAutoSuggestor);
            }
        }
	<?php } ?>
	
	window.onscroll = floatCompareWidgetScroll;

	//Set the Cookie for the Compare widget
	if (cookieDataArray.length>0) {
		setCookie("compare-global-categoryPage",cookieDataArray.join("|||"));
	}
	else{
		setCookie("compare-global-categoryPage","");	
	}
	var listings_with_localities = <?php echo $listings_with_localities; ?>;
	var localityArray = <?=json_encode($localityArray)?>;

	window.onload=function(){

		if(filled_compares != 0){
			getCollegeReviewsForCourse("<?=implode(',',$courseArray)?>",0,1,"<?=implode(',',$subcatIdArray)?>",'compare','<?php echo json_encode($instituteName);?>','<?php echo json_encode($courseNameArray);?>',0);
		}


		$j.each(localityArray,function(index,element){
			custom_localities[index] = element;
		});
		
		//Track the courses, static/dynamic page, source
		<?php   if(isset($isStaticPage) && $isStaticPage){
				echo "var pageType = 'static';";
			}
			else{
				echo "var pageType = 'dynamic';";
			}
		?>
		var source = 'desktop';
		var courseString = '<?=implode(',',$courseArray)?>';
		var trackCookiename = "compare-tracking";
		if(getCookie(trackCookiename)){
		    var trackeyStr = getCookie(trackCookiename);
		}
		if(courseString !=''){
			var randNum = Math.floor(Math.random()*Math.pow(10,16));
			$('tracking_img').src = '/compareInstitute/compareInstitutes/trackComparePage/'+randNum+'/'+pageType+'/'+source+'/'+courseString+'/'+trackeyStr+'/'+compareHomePageKeyId;
		}
};
</script>
