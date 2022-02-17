<?php

class NotificationController extends MX_Controller {

        private $validationObj;

        function __construct() {
            parent::__construct();    
        }

        private function init(){
            $this->load->config('NotificationConfig');
        }

        public function notificationInAppCron(){
            $redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');     
            getAllMembersOfHashWithValue('notifications:inAPP:');
        }


        public function generateContributionBasedNotifications($type='',$threadId='',$threadType='',$actioner='-1',$action_item='',$action_item_id=0,$action_item_2='',$action_item_id_2=0){

            $this->init();
            $notificationLib = $this->load->library('NotificationContributionLib');
            $notificationModel = $this->load->model('NotificationModel');
            switch ($type) {
                            case NEW_ANSWER_ON_QUESTION:
                                if($actioner == -1){
                                    $actioner = $notificationModel->fetchEntityOwner($action_item_id);
                                }
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2);
                                break;
                            case NEW_FOLLOWER_ON_QUESTION:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case EDIT_QUESTION_BY_MODERATOR:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case CLOSE_QUESTION:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case UPVOTE_ANSWER:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;  
                            case EDIT_ANSWER:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case COMMENT_ON_ANSWER:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case LINK_QUESTION:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                           case NEW_COMMENT_ON_DISCUSSION:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case NEW_FOLLOWER_ON_DISCUSSION:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case EDIT_DISCUSSION_BY_MODERATOR:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case UPVOTE_ON_COMMENT:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;                          
                            case REPLY_ON_COMMENT:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case EDIT_COMMENT:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            case LINK_DISCUSSION:
                                $notificationLib->notificationForQuestions($type,$threadId,$threadType,$actioner,$action_item,$action_item_id,$action_item_2,$action_item_id_2,$actioner);
                                break;
                            
                        }            
        }

        public function generateAchievementBasedNotifications(){

        }

       
        
}

