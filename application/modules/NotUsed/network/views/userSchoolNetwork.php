<!--Start_Mid_Panel-->
	<div id="mid_Panel_network">
		<div style="display:inline; float:left; width:100%">
				<div class="raised_lgraynoBG">

				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial" id = "Noofstudents">
							</div>
						</div>
					  	<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_full_10p lineSpace_22">

								<div class="pagingID float_L" id="paginataionPlace1">
                                </div>

							</div>
                                <div class ="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
<input type="hidden" id="startOffSet1" value="0"/>
<input type="hidden" id="countOffset1" value="12"/>	
<input type="hidden" id="methodName1" value="showfacultyNetwork"/>
						<div id = "gray1">
                        <div class="grayLine"></div>

		<div class="lineSpace_10">&nbsp;</div>
        </div>
<div id = "collegeNetwork">
</div>
			<div id = "gray2">
            <div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
                        </div>
<div id = "nomembersmsg" align = "center" class = "fontSize_12p Organge">
</div>
						<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_full_10p lineSpace_22">
                            								
				<div class="pagingID float_L" id="paginataionPlace2"></div>

							</div>
                            <div class = "clear_L"></div>
				<div class="lineSpace_10">&nbsp;</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<!--End_Student_Network-->
				
				<!--Teacher_Network-->
<!--				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial" id = "facultynumber"></div>
						</div>
					  <div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="left">
								<div class="pagingID" id="paginataionPlace3"></div>
							</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
<div id = "facultyCollegeNetwork">
</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="left">
								<div class="pagingID" id="paginataionPlace4"></div>
							</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>-->
				<!--End_Teacher_Network-->
				
<!-- User Comments-->

<?php	echo "<script>"; 
				echo "var COLLEGEID = '".$schoolId."';";			
                            echo "</script>";?>
				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial" id = "">
                            <span class="mar_left_10p bld">School Discussion Board</span>
                            </div>
						</div>
					  <div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="left">
								<div class="pagingID" id="paginataionPlace3"></div>
							</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="lineSpace_10">&nbsp;</div>

<?php $topicUrl =
site_url('messageBoard/MsgBoard/topicDetails').'/'.$categoryId.'/'.$topicId;
$url = site_url("messageBoard/MsgBoard/replyMsg");
$isCmsUser =0;

if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms')
== 0))
        $isCmsUser = 1;?>


<div id = "userCollegeComments">
<div id = "topicContainer" class = "mar_full_10p">
<div class="mar_full_10p">
<?php
$commentData['url'] = $url;
$commentData['threadId'] = $threadId;
$commentData['isCmsUser'] = $isCmsUser;
$commentData['topicUrl'] = $topicUrl;
$commentData['userProfile'] = site_url('getUserProfile').'/'; 
$commentData['group'] = 1;
$commentData['fromOthers'] = 'school';
$userProfile = '';
$commentData['userProfile'] = $userProfile;
$commentData['maximumCommentAllowed'] = 4;
$commentData['pageKeySuffixForDetail'] = 'GROUPS_SCHOOLGROUPDETAIL_MIDDLEPANEL_';
$this->load->view('messageBoard/topicPage',$commentData);?>
</div>
<?php $this->load->view('common/inviteMail'); 
if(!(isset($topic_messages) && is_array($topic_messages)))
{
?>
<div id = "nomessagemsg" class = "fontSize_12p Organge" align = "center"></div>
<?php } ?>
</div>
<div style="display:none; margin:0 10px; border:1px solid #C0C0C0" id="globalReplyFormPlace"></div>						
</div>
<?php
$comment['flag'] = 'school';
$comment['fromOthers'] = 'school';
$this->load->view('network/userComment',$comment);?>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="left">
								<div class="pagingID" id="paginataionPlace4"></div>
							</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
                <!-- End User Comment -->
				
			</div>
			<!--End_courses_category_Box-->
		<div class="lineSpace_10">&nbsp;</div>
	</div>
	<!--End_Mid_Panel-->

