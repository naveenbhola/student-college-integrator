<?php
class AnACommentLib {
	
	private $CI;
	public function __construct() {
        $this->CI =& get_instance();
	}

	// Desc- Get comment and replies
	function getCommentEntity($topicId, $start=0, $limit=5){
		$closeDiscussion = 0;
		if($topicId>0 && $topicId!=''){	
			$this->CI->load->helper(array('image','shikshautility'));
			$this->qnaModelObj = $this->CI->load->model('QnAModel');
			$ResultOfDetails = $this->qnaModelObj->getCommentsOnTopic($topicId, $start, $limit);
		}
		
		$topic_reply = isset($ResultOfDetails['MsgTree'])?$ResultOfDetails['MsgTree']:array();
		$answerReplies = isset($ResultOfDetails['Replies'])?$ResultOfDetails['Replies']:array();
		$displayData['topicId'] = $topicId;
		if(is_array($topic_reply) && count($topic_reply) > 0)
		{
			$topic_messages = array();
			$topic_replies = array();
			
			foreach($topic_reply as $topicComment)
			{
				if($topicComment['parentId']!=0)
				{
					//$found = 0;
					if(substr_count($topicComment['path'],'.') == 1)
					{
						$topicComment['userStatus'] = getUserStatus($topicComment['lastlogintime']);
						$topicComment['creationDate'] = makeRelativeTime($topicComment['creationDate']);
						$answerId = $topicComment['msgId'];
						
						$topic_messages[] = $topicComment;
						
						//$topic_replies[$answerId] = array();
						//array_push($topic_messages[$i],$topicComment);
						$comparison_string = $topicComment['path'].'.';
						$topic_replyInner = $answerReplies;
						foreach($topic_replyInner as $keyInner => $tempInner){
							if(strstr($tempInner['path'],$comparison_string)){
								//$j++;
								//$topic_replies[$answerId][$j] = array();
								$tempInner['userStatus'] = getUserStatus($topic_reply[$i]['lastlogintime']);
								$tempInner['creationDate'] = makeRelativeTime($tempInner['creationDate']);
								$topic_replies[$answerId][] = $tempInner;
								//array_push($topic_replies[$answerId][$j],$tempInner);
							}
						}
					}
				}
			 }
		if($topic_reply[0]['blogStatus'] == 'closed')
			$closeDiscussion = 1;
	   }
	   $displayData['commentCountForTopic']=isset($ResultOfDetails['totalRows'])?($ResultOfDetails['totalRows']):0;
	   $displayData['closeDiscussion'] = $closeDiscussion;
	   $displayData['topic_messages'] = $topic_messages;
	   $displayData['topic_replies'] = $topic_replies;
	   return $displayData;
	}
}