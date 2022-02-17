<?php

class questionClosure extends MX_Controller {

        
        function init(){
                //error_log(print_r(error_reporting(1)));
                $this->load->model('closequesmodel');
        }
        
        
      // this calls the model to perform the activity..
        function getQuestionsforClosure(){
                $this->init();
                $this->closequesmodel->updateQuestionClosure();
        }
}

?>
