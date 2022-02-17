<?php
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:'';
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	if($userId != '')
		$loggedIn = 1;
	else
		$loggedIn = 0;

?>

<div id="blogTabContainer">
<div id="blogNavigationTab" style="width:100%">
	<ul>								
		<li container="forum" tabName="forumModerateAnATab" class="selected"><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showAbuseAnA() \" title=\"Ask & Ans\">Ask & Ans</a>";  ?></li>
		<li container="forum" tabName="forumModerateArticleTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showAbuseArticles() \" title=\"Article\">Articles</a>";  ?></li>
		<li container="forum" tabName="forumModerateEventTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showAbuseEvents() \" title=\"Event\">Events</a>";  ?></li>
		<li container="forum" tabName="forumModerateDiscussionTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showAbuseDiscussion() \" title=\"Discussion\">Discussion</a>";  ?></li>
		<!--<li container="forum" tabName="forumModerateReviewTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showAbuseReview() \" title=\"College Review\">College Review</a>";  ?></li>-->
		<li container="forum" tabName="forumModerateAnnouncementTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showAbuseAnnouncement() \" title=\"Announcements\">Announcements</a>";  ?></li>
                <li container="forum" tabName="forumModerateEditTitleTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showQuestionsTitleWithDesc() \" title=\"Title Moderation\">Title Moderation</a>";  ?></li>
		<li container="forum" tabName="forumModerateLinkedQuestionTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showLinkedAndOrignalDiscussionsAndQuestionsTitle('ques') \" title=\"Linked Questions\">Linked Questions</a>";  ?></li>
                <li container="forum" tabName="forumModerateLinkedDiscssionTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showLinkedAndOrignalDiscussionsAndQuestionsTitle('discussion') \" title=\"Linked Discussions\">Linked Discussions</a>";  ?></li>
		<li container="forum" tabName="forumModerateExpertTab" class=""><?php    echo "<a href=\"javascript:void(0)\" onClick=\"showExperts() \" title=\"Expert Moderation\">Expert Moderation</a>";  ?></li>
	</ul>
</div>
</div>
<div style="margin-left:0px;">			
	<!--BlogNavigation-->
	<div id="abuseReportContainer" style="display:block;">
							<?php 

							$this->load->view('enterprise/abuseListing',array('userAbuse'=>$userAbuse,'abuseDetails'=>$abuseDetails)); 
							?>
	</div>
	<!--BlogNavigation-->
</div>

<!--End_Mid_Panel-->
<br clear="all" />
<!--End_Center-->
