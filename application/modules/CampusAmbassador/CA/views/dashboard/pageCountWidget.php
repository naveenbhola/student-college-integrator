<div class="view-sorting clear-width">
<?php if($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer==0 && $totalApprovedAnswer[0]->totalAnswer == 0){?>		
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)

<?php }elseif($totalQuestionRows ==0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer == 0){?>
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)

<?php }elseif($totalQuestionRows ==0 && $totalDisapprovedAnswer[0]->totalAnswer==0 && $totalApprovedAnswer[0]->totalAnswer != 0){?>
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)

<?php }elseif($totalQuestionRows ==0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer != 0 && $pageName == 'answerPage' ){?>
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getDisapprovedAnswer">Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a>)

<?php }elseif($totalQuestionRows ==0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer != 0 && $pageName == 'disapprovedAnswerPage' ){?>
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>) <!-- | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getAnswersComment">Approved/Pending (<?php if($totalApprovedAnswer[0]->totalAnswer){echo $totalApprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a> -->

<?php }elseif($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer==0 && $totalApprovedAnswer[0]->totalAnswer != 0 && $pageName == 'questionPage'){?>
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>) <!-- | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getAnswersComment">Approved/Pending (<?php if($totalApprovedAnswer[0]->totalAnswer){echo $totalApprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a> -->

<?php }elseif($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer==0 && $totalApprovedAnswer[0]->totalAnswer != 0 && $pageName == 'answerPage'){?>
View:  <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getCRUnansweredTab">Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>)</a> | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)


<?php }elseif($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer == 0 && $pageName == 'disapprovedAnswerPage'){?>
View:  <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getCRUnansweredTab">Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>)</a> | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)

<?php }elseif($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer == 0 && $pageName == 'questionPage'){?>
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getDisapprovedAnswer">Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a>)

<?php }elseif($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer != 0 && $pageName == 'questionPage'){?>
View:  Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>) | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getDisapprovedAnswer">Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a><!--  | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getAnswersComment">Approved/Pending (<?php if($totalApprovedAnswer[0]->totalAnswer){echo $totalApprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a> -->

<?php }elseif($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer != 0 && $pageName == 'answerPage'){?>
View:  <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getCRUnansweredTab">Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>)</a> | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getDisapprovedAnswer">Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a>

<?php }elseif($totalQuestionRows !=0 && $totalDisapprovedAnswer[0]->totalAnswer!=0 && $totalApprovedAnswer[0]->totalAnswer != 0 && $pageName == 'disapprovedAnswerPage'){?>
View:  <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getCRUnansweredTab">Unanswered (<?php if($totalQuestionRows){echo $totalQuestionRows;}else{echo 0;}?>)</a> | Disapproved (<?php if($totalDisapprovedAnswer[0]->totalAnswer){echo $totalDisapprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)<!--  | <a href="<?php echo SHIKSHA_HOME;?>/CA/CRDashboard/getAnswersComment">Approved/Pending (<?php if($totalApprovedAnswer[0]->totalAnswer){echo $totalApprovedAnswer[0]->totalAnswer;}else{echo 0;}?>)</a> -->

<?php }?>
</div>