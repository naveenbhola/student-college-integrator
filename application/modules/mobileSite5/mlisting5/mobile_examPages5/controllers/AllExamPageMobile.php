<?php

class AllExamPageMobile extends ShikshaMobileWebSite_Controller {
    function __construct() {
        parent::__construct();
        
    }
    function getAllExamList($param1, $param2) {
        $examPageView =  modules::run('examPages/ExamPageMain/getAllExamList', $param1, $param2);
        echo $examPageView;
    }
}
?>
