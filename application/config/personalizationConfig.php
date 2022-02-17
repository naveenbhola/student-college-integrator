<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['threadQualityScoreParametersWithValue']	=	array(	'threadView'		=>	1.0,
																'threadComment'		=>	1.0,
																'threadAnswer'		=>	1.0,
																'threadShare'		=>	1.0,
																//'threadPost'		=>	1.0,
																'threadFollow'		=>	1.0,
																'threadUnfollow'	=>	-1.0,
																'threadUpvote'		=>	1.0,
																'threadDownvote'	=>	1.0,
																//'threadReportAbuse'	=>	1.0,
																//'tagView'			=>	1.0,
																//'tagFollow'			=>	1.0,
																//'tagUnfollow'		=>	1.0,
																'deleteComment'		=>	-1.0,
																'deleteAnswer'		=>	-1.0
															);
															
/* $config['userTagQualityScoreParametersWithValue']	=	array(	'threadView'		=>	1,
																'threadComment'		=>	2,
																'threadAnswer'		=>	3,
																'threadShare'		=>	4,
																'threadPost'		=>	5,
																'threadFollow'		=>	6,
																'threadUnfollow'	=>	7,
																'commentUpvote'		=>	8,
																'commentDownvote'	=>	9,
																'answerUpvote'		=>	10,
																'answerDownvote'	=>	11,
																'answerReportAbuse'	=>	12,
																'commentReportAbuse'=>	13,
																'tagView'			=>	14,
																'tagFollow'			=>	15,
																'tagUnfollow'		=>	16
														); */
$config['topTagsForUserCount']	=	25;
$config['threadQualityScoreParametersWithKeyName']	=	array(	'threadView'		=>	'vi',
																'threadComment'		=>	'co',
																'threadAnswer'		=>	'an',
																'threadShare'		=>	'sh',
																//'threadPost'		=>	'po',
																'threadFollow'		=>	'fo',
																'threadUnfollow'	=>	'fo',
																'threadUpvote'		=>	'up',
																'threadDownvote'	=>	'do',
																//'threadReportAbuse'	=>	'abus',
																//'tagView'			=>	'tgvi',
																//'tagFollow'			=>	'tgfo',
																//'tagUnfollow'		=>	'tgunfo',
																'deleteComment'		=>	'co',
																'deleteAnswer'		=>	'an'
															);
$config['personalizationNumberToStringMapping']		=	array(	1	=> 'answer',
																2	=> 'comment',
																3	=> 'post',
																4	=> 'share',
																5	=> 'upvote',
																6	=> 'follow',
																7	=> 'follower',
																8	=> 'freind',
																9	=> 'threadcontributor',
																10	=> 'threadfollower',
																11	=> 'tagfollower',
																12	=> 'userfollower',
																13	=> 'userfreind',
																14	=> 'topContent',
																15	=> 'threadowner'
															);
$config['maxHomeFeedItem']	= 200;
$config['inActiveDaysForUser'] = 13;
$config['inActiveDaysForVisitor'] = 3;
$config['highLevelTags']	= array(	'tags'		=> array('Career','Exam'),
										'tag_entity'=> array('Course Level','Colleges In location','Mode','Country','State','City')
									);
?>
