<div class="no__pad mt__15 smle-exm-wdgt">
<h2 class="f20__clr3 mt__15">Exams similar to <?=$examName;?></h2>
<div class="mt__15">
   <ul class="similar__exams">
      <?php 
        $this->load->view('examPages/newExam/similarExamData');
        
        if($similarExams['isViewLink']) { ?>
                  <li>
                     <div class="txt__cntr">
                       <a class="pad__20 i__block ps__rl" id="viewExams" ga-attr="VIEW_ALL_SIMILAR_EXAMS">View All Similar Exams</a>
                     </div>
                 </li>
    <?php } ?>
   </ul>
</div>
</div>