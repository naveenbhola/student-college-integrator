<?php
	$this->load->view('mcommon5/header');
?>


<div id="wrapper" data-role="page"  >
<?php foreach ($results as $cat_id=>$courses):
	$temp_course = reset($results);
	if($cat_id != $categorySelected){
		continue;
	}
?>
	<header id="page-header" class="clearfix">
	    <div class="head-group">
		<a href="<?php echo $referral_url;?>" ><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
		<h1>
		    <div class="left-align" style="margin-right:40px">
			Courses in <span id="cat_name"><?php echo $cat_name?$cat_name:"Others";?></span> - <?php echo getPlural(count($courses), 'course');?>
		    </div>
		</h1>
	    </div>
	</header>

	<section class="content-wrap2 tb-space">
	<div class="inst-detail-list" id="courseTabDesc" data-enhance="false" >

	<?php 
	$i=0;$j=0;
	foreach($courses as $course):
		$cat_name = $course['cat_name'];
		echo "<script>$('#cat_name').html('$cat_name');</script>";
			if($i>0){
				$hideDivStyling = "display:none";
				$classIcon="icon-arrow-up";
			}
			else{
				$classIcon="icon-arrow-dwn";
			//	$openClass = "icon-arrow-dwn";
			}
	?>	
	
		<dt id="course<?=$course['id']?>_count_<?=$i?>">
		   <a onClick="setAccordion('<?=$i?>');" href="javascript:void(0);">
		   <h2>
		   		<p><?=$course['name'];?></p>
		   	</h2>
		   		<i id="desc<?=$i?>" class="<?=$classIcon;?>"></i>
		   </a>
		</dt>
		
		
		<dd style="height:auto;<?=$hideDivStyling?>" id="desc_count_<?=$i?>_desc">
		 <div class="notify-details" style="cursor: pointer;">
			<div onclick="window.location.href='<?php echo $course["url"];?>'">
			<p>
			    <?php
				    echo $course['courseduration'] ? $course['courseduration'] : "";
				    echo ( $course['courseduration']  && $course['coursetype']  ) ? ", " . $course['coursetype'] : ( $course['coursetype'] ? $course['coursetype'] : "" );
				    echo ( $course['courselevel'] && ($course['coursetype'] || $course['courseduration'])) ? ", ".  $course['courselevel'] : (  $course['courselevel'] ?  $course['courselevel'] : "");
			    ?>			 
			</p>
	
			<em>
			    <?php
				      $approvalsAndAffiliations = array();
				      $approvals = $course['approvals'];
				      foreach($approvals as $approval) {
					      $approvalsAndAffiliations[] = langStr('approval_'.$approval);
				      }
				      $affiliations = $course['affiliations'];
				      foreach($affiliations as $affiliation) {
					      $approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
				      }
				      echo implode(', ',$approvalsAndAffiliations);
			    ?>
			</em>
			
			<?php if($course['coursefeesvalue'] && $course['coursefeesvalue']!=''):?>
			<p>Fees: <?=$course['coursefeesunit']." ".$course['coursefeesvalue']?></p>
			<?php endif;?>
			</div>

			<div id= "thanksMsg<?php echo $course['id'];?>" class="thnx-msg" <?php if(!in_array($course['id'],$applied_courses)){?>style="display:none"<?php } ?>>
						<i class="icon-tick"></i>
						<p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
			</div>
			
		    
		<?php
			$institute_id = $course['instituteId'];
			$course['current_url'] = $course['referral_url'];
			if($course['ispaid']=="TRUE"){?>
				<p>
					<?php if(in_array($course['id'],$applied_courses)){?>
						<a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$course['id'];?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
						<?php }else{ ?>
						<a class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$course['id'];?>" onClick="trackReqEbrochureClick('<?=$course['id'];?>');validateRequestEBrochureFormData('<?=$institute_id;?>','<?=$course['rebLocallityId'];?>','<?=$course['rebCityId'];?>','<?=$course['isMultiLocation'];?>','<?php echo $course['id'];?>');"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
						<?php } ?>
				</p>
			   <form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course['id'];?>">
						<input type="hidden" name="courseAttr" value = "<?php echo $course['addReqInfoVars']; ?>" />
						<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($current_url); ?>" />
                        <input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($referral_url); ?>" />
						<input type="hidden" name="selected_course" value = "<?php echo $course['id']; ?>" />
						<input type="hidden" name="list" value="<?php echo $course['id']; ?>" />
						<input type="hidden" name="institute_id" value="<?php echo $institute_id; ?>" />
						<input type="hidden" name="pageName" value="SEARCH_PAGE" />
						<input type="hidden" name="from_where" value="MOBILE5_SEARCH_PAGE" />
				</form>
			     <?php } ?>
		    </div>
		</dd>
	
	<?php $j++; $i++; ?>
	
	<?php endforeach;?>	
	</div>
	</section>

<?php endforeach;?>	
<?php $this->load->view('mcommon5/footerLinks');?>
</div>

<?php 
	$this->load->view('mcommon5/footer');
?>

<script>
var totalCount = <?=$j?>;
function setAccordion(indexVal){
	if(totalCount>1){
		//Hide all the other courses
		$("dd[id*='_count_']").hide();
	
		//Change the Class of all the other courses
		for(i=0;i<totalCount;i++)	{
			$('#desc'+i).attr('class', 'icon-arrow-up');		
		}
	
		//Display the clicked Course
		id="count_"+indexVal + '_';
		 $("dd[id*='"+id+"']").show();
		
		//Change the Class of the Clicked Course
		$('#desc'+indexVal).attr('class', 'icon-arrow-dwn');
		
		//Set the clicked CourMBA - Financial Management
		courseToBeInFocus = parseInt(indexVal)-1;	
		if($("dt[id*='count_"+courseToBeInFocus+"']")[0]){
			$("dt[id*='count_"+courseToBeInFocus+"']")[0].scrollIntoView();
		}
		else{
			$("dt[id*='count_"+parseInt(indexVal)+"']")[0].scrollIntoView();
		}
	}
}
function trackReqEbrochureClick(courseId){
		try{
		_gaq.push(['_trackEvent', 'HTML5_Search_More_Courses_Request_Ebrochure', 'click', courseId]);
		}catch(e){}
}


</script>
