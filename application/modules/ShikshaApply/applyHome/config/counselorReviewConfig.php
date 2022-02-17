<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['counselorRelatedQuestions'] = array(
		array(
			'question'=>'On a scale of 1 to 10, how responsive do you think is the counselor?', 
            'subHead'=>'Indicates if the counselor got back to you as per your requirements.', 
			'minScale'=>'Least Responsive', 
			'maxScale'=>'Most Responsive'
			),
		array(
			'question'=>'On a scale of 1 to 10, how knowledgeable do you think is the counselor?', 
			'subHead'=>'Counselor understanding on country, courses, applications & visa procedure.', 
			'minScale'=>'Poor', 
			'maxScale'=>'Outstanding'
			),
		array(
			'question'=>'On a scale of 1 to 10, please rate the guidance given by the counselor?', 
            'subHead'=>'Indicates if counselor guided you as per your abroad education goals.',
			'minScale'=>'Poor', 
			'maxScale'=>'Outstanding'
			)
		);

$config['counsellingServiceQuestions'] = array(
		array(
			'question'=>'On a scale of 1 to 10, how would you rate your experience with studyabroad.shiksha.com so far?', 
			'subHead'=>'This is not a counselor specific question.',
			'minScale'=>'Poor', 
			'maxScale'=>'Outstanding'
			),
		array(
			'question'=>'How likely are you to recommend "Shiksha Apply Counseling Service" to a friend or family?', 
			'subHead'=>'This is not a counselor specific question.',
			'minScale'=>'Not Likely at all', 
			'maxScale'=>'Extremely likely'
			),
		);
global $examsPriorityOrder;
$examsPriorityOrder = array('SAT', 'GMAT', 'GRE', 'TOEFL', 'IELTS', 'PTE');
define('REVIEW_PER_PAGE', '10')
?>