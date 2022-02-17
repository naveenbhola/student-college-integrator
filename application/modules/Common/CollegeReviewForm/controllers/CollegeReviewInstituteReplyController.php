<?php
/*

   Copyright 2015-16 Info Edge India Ltd

   $Author: Bharat Issar

   $Id: College Review Institute Reply Controller

 */

class CollegeReviewInstituteReplyController extends MX_Controller
{

        function init(){
		
		$this->load->model('collegereviewmodel');
		$this->crmodel = new CollegeReviewModel();
                $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
        }
        
        function saveInstituteReply(){
            $this->init();
            $reviewId = isset($_POST['reviewId'])?$this->input->post('reviewId'):'';
            $courseId = isset($_POST['courseId'])?$this->input->post('courseId'):'';
            $userId = isset($_POST['userId'])?$this->input->post('userId'):'';
            $replyTxt = isset($_POST['replyTxt'])?$this->input->post('replyTxt'):'';
            $this->crmodel->insertReplyinTable($reviewId, $courseId, $userId, $replyTxt);
            $this->national_course_lib->getCourseReviewsData(array($courseId), true);
        }
}
