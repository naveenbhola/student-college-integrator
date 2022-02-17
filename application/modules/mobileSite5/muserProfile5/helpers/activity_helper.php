<?php

	

	

	
	function getEntityTypeMapping($typeOfStat){

		if(empty($typeOfStat)){
			return false;
		}

		$entityTypeMapping = array('Answer Later'=>'Question',
										'Questions Asked'=>'Question',
										'Questions Followed'=>'Question',
										'Upvotes on Answers'=>'Question',
										'HQ Answers'=>'Question',
										'Answers'=>'Question',
										'Comment Later'=>'Discussion',
										'Discussions Started'=>'Discussion',
										'Discussion Comments'=>'Discussion',
										'Discussions Followed'=>'Discussion',
										'Upvotes on Comments'=>'Discussion',
										'HQ Comments'=>'Discussion',
										'Followers'=>'User',
										'Following'=>'User',
										'Tags Followed'=>'Tag'
									);

		return $entityTypeMapping[$typeOfStat];
	}

	function getEntityCategoryMapping($typeOfStat){

		if(empty($typeOfStat)){
			return false;
		}

		$entityCategoryMapping = array('Answer Later'=>'answerLater',
									'Questions Asked'=>'questionsAsked',
									'Questions Followed'=>'questionsFollowed',
									'Upvotes on Answers'=>'answerUpvotedQuestions',
									'HQ Answers'=>'HQAnswerQuestions',
									'Answers'=>'answers',
									'Comment Later'=>'commentLater',
									'Discussions Started'=>'discussionsPosted',
									'Discussion Comments'=>'comments',
									'Discussions Followed'=>'discussionsFollowed',
									'Upvotes on Comments'=>'commentUpvotedDiscussions',
									'HQ Comments'=>'HQCommentDiscussions'
									);

		return $entityCategoryMapping[$typeOfStat];
	}

	function getAPIForANA($entityCategory){

		if(empty($entityCategory)){
			return false;
		}

		$baseURLsForAnAAPI = array('answerLater'=>'UserProfile/getQuestionsByCategory',
									'questionsAsked'=>'UserProfile/getQuestionsByCategory',
									'questionsFollowed'=>'UserProfile/getQuestionsByCategory',
									'answerUpvotedQuestions'=>'UserProfile/getQuestionsByCategory',
									'HQAnswerQuestions'=>'UserProfile/getQuestionsByCategory',
									'answers'=>'UserProfile/getQuestionsByCategory',
									'commentLater'=>'UserProfile/getDiscussionsByCategory',
									'discussionsPosted'=>'UserProfile/getDiscussionsByCategory',
									'comments'=>'UserProfile/getDiscussionsByCategory',
									'discussionsFollowed'=>'UserProfile/getDiscussionsByCategory',
									'commentUpvotedDiscussions'=>'UserProfile/getDiscussionsByCategory',
									'HQCommentDiscussions'=>'UserProfile/getDiscussionsByCategory',
									'Followers'=>'UserProfile/getUsersFollowingMe',
									'Following'=>'UserProfile/getUsersIAmFollowing',
									'Tags Followed'=>'UserProfile/getTagsFollowed'
								);

		return $baseURLsForAnAAPI[$entityCategory];
	}

?>