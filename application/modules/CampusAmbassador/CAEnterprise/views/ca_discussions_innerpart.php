<?php
//  $badgesCourse = array('None'=>'None','CurrentStudent'=>'Current Student','Alumni'=>'Alumni');
//  $badgesOfficial = array('None'=>'None','Official'=>'Official');
  $selectedFilter = isset($filterSel)?$filterSel:'All';
  $All = '';$history='';$live='';
switch($selectedFilter){
  case 'All': $All = "selected";
	      break;
  case 'history': $history = "selected";
	      break;
  case 'live': $live = "selected";
	      break;
}

  $reportsPerPage = isset($countOffset)?$countOffset:5;
  $number5 = '';$number10='';$number20='';$number30='';
  switch($reportsPerPage){
  case 5: $number5 = "selected";
	      break;
  case 10: $number10 = "selected";
	      break;
  case 20: $number20 = "selected";
	      break;
  case 30: $number30 = "selected";
	      break;
  }

//  if(isset($userNameFieldDataCA) && $userNameFieldDataCA!=''){
//      switch($filterTypeFieldDataCA){
//      case 'User Name': $userName = "selected";
//		  break;
//      //case 'Institute': $institute = "selected";
//	//	  break;
//      case 'Email': $email = "selected";
//		  break;
//      case 'Filter Type': $filterType = "selected";
//		  break;
//      default: $filterType = "selected";
//		  break;
//      }
//  }

?>
<!--Pagination Related hidden fields Starts-->
<input type="hidden" autocomplete="off" id="caStartFrom" value="<?php echo $startFrom;?>"/>
<input type="hidden" autocomplete="off" id="caCountOffset" value="<?php echo $countOffset;?>"/>
<input type="hidden" autocomplete="off" id="caFilter" value=""/>
<input type="hidden" autocomplete="off" id="methodName" value="getAllCourseDiscussions"/>
        
        
<div style="float:left; width:100%;">
	<div class="raised_lgraynoBG">
	    <b class="b1"></b><b class="b2"></b><!--<b class="b3"></b><b class="b4"></b>-->
		<div class="lineSpace_10">&nbsp;</div>
                         <div>
						<table width="99%" cellspacing="0" cellpadding="0" border="0" height="25">
							<tr>
								<td width="20%">
									<table cellspacing="0" cellpadding="0" border="0" height="25" width="90%">
									<tr>
										  <td><span id="pagLabTop">Filter by Status</span>
											<select class="universal-select" name="filter" id="filter" class="normaltxt_11p_blk_arial" onChange="filterDiscussionData();">
												<option value="All" <?php echo $All;?>>All</option>
												<option value="live" <?php echo $live;?>>Live</option>
												<option value="history" <?php echo $history;?>>Archived</option>
						
											</select>					
										</td>
									</tr>
									</table>
								</td>
								<td valign="top">
								<span>&nbsp;</span>
						                                    <div id="anaAutoSuggestor" style="position: relative;">
                                                                                    <input type="text" name="keywordSuggest" id="keywordSuggest"  class="universal-txt-field" autocomplete="off" default="Search By Institute name..." value="<?php if(!empty($keywordSuggest)){ echo $keywordSuggest;}else{ ?>Search By Institute name...<?php } ?>" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" />
                                                                                     <div id="suggestions_container" style="position:absolute; left:7px; top:44px;background-color:#fff;"></div>
						                                     </div>
                                                                                   <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="<?php if(!empty($instituteId)){ echo $instituteId;}?>" />
								</td>
								<td align="right" style="padding-top: 15px">
									<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
										<td style="line-height:23px;display:none;"></span>
											<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'caStartFrom','caCountOffset');">
												<option value="5" <?php echo $number5;?>>5</option>
												<option value="10" <?php echo $number10;?>>10</option>
												<option value="20" <?php echo $number20;?>>20</option>
												<option value="30" <?php echo $number30;?>>30</option>
											</select>					
										</td>
									</tr>
									</table>
								</td>
							</tr>
						</table>
						
						
						      <?php 
							if(count($historyData) <= 0){
							?>
                                                        <div class="lineSpace_10p">&nbsp;</div><div class="fontSize_12p" align="center">No Campus Discussion matching your criteria were found.</div>
							<?php } else {
						      ?>
						  
                                                <?php $showHighlighted = false; ?>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="clear_L"></div>
						<div class="lineSpace_11">&nbsp;</div>
                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="discussion-table">
                                                            <tr>
                                                                <th>Institute name</th>
                                                                <th>Course name</th>
                                                                <th>Batch</th>
                                                                <th>Status</th>
                                                                <th>&nbsp;</th>                            
                                                            </tr>
                                                           <?php foreach($historyData as $data) { $showHighlighted = ($showHighlighted)?false:true;?>
                                                            <?php if($data['status'] == 'history' && $selectedFilter != 'live' ):?>
                                                            <tr  class="<?php if(!$showHighlighted) echo 'alt-row';?>">
                                                                <td><?php echo $data['institute_name']; ?></td>
                                                                <td><?php echo $data['course_name']; ?></td>
                                                                <td><?php if($data['session_year']!='')echo $data['session_year'].' - '.($data['session_year']+1); else echo 'All'; ?></th>
                                                                <td>Archived</td>
                                                                <td></td>
                                                            </tr>
                                                            <?php else:?>
                                                            <?php if($data['status'] != 'history'):?>
                                                            <tr  class="<?php if(!$showHighlighted) echo 'alt-row';?>">
                                                                <td><?php echo $data['institute_name']; ?></td>
                                                                <td><?php echo $data['course_name']; ?></td>
                                                                <td><?php if($data['session_year']!='')echo $data['session_year'].' - '.($data['session_year']+1); else echo 'All'; ?></th>
								<?php
								  $courseId = $data['course_id'];
								  $lastSessionYear = 2012;
								  if(isset($historyDataOfCourse[$courseId]) && $historyDataOfCourse[$courseId]!=''){
								      $lastSessionYear = $historyDataOfCourse[$courseId];
								  }
								  $instituteId = $data['institute_id'];
								  $instituteName = $data['institute_name'];
								  $courseId = $data['course_id'];
								  $courseName = $data['course_name'];
								  $sessionYear = $data['session_year'];
								?>
                                                                <td id="archiveStatus_<?=$courseId?>_<?=$sessionYear?>">Live</td>
                                                                <td id="archiveButton_<?=$courseId?>_<?=$sessionYear?>"><input type="button" value="Archive" class="orange-button" onClick="archiveDiscussion('<?=$instituteId?>','<?=$courseId?>','<?=base64_encode($instituteName)?>','<?=base64_encode($courseName)?>','<?=$sessionYear?>','<?=$lastSessionYear?>');"/></td>
                                                            </tr>
                                                            <?php endif;?>
                                                            <?php endif;?>
                                                            <?php } ?>
                                                        </table>
							<?php } ?>
                
						<!-- code for pagination start -->
						<div class="mar_full_10p">
							<div class="row" style="line-height:24px">
								<div class="normaltxt_11p_blk_arial" style="float:right;display:none;" align="right">
										<select name="countOffset" id="countOffset_DD2" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'caStartFrom','caCountOffset');">
										    <option value="5" <?php echo $number5;?>>5</option>
										    <option value="10" <?php echo $number10;?>>10</option>
										    <option value="20" <?php echo $number20;?>>20</option>
										    <option value="30" <?php echo $number30;?>>30</option>
										</select>
								</div>
								<div class="pagingID" id="paginataionPlace2" align="right"></div> 
							</div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<!-- code for pagination ends -->
						</div>
			</div>
			<div class="lineSpace_11">&nbsp;</div>
		</div>   
 <?php
		echo "<script> 
                       $('filter').options[0].selected = 'selected';
                       $('countOffset_DD1').options[0].selected = 'selected';
                       $('countOffset_DD2').options[0].selected = 'selected';
			setStartOffset(0,'caStartFrom','caCountOffset');
			doPagination(".$totalDiscussionCount.",'caStartFrom','caCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
			</script>";
?>
