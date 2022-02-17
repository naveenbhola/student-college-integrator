<section class="detail-widget 0detail-info">
	<div class="detail-widegt-sec modifyImage">
        <h1 style="margin:0;padding:15px 15px 5px 15px; display:block; font-size:15px;"><?php echo htmlentities($examPageObj->getExamPageTitle());?></h1>
        <?php
        //if case of first subSection show the exam description
        if($sectionData['sectionId'] == '1')
        {
            echo "<div class='detail-info-sec sop-content exam-article-content dyanamic-content clearfix'><p> ".$examPageObj->getExamPageDescription()."</p></div>";            
        }
         foreach ($subSectionDetails as $key =>$value) {

            if($sectionData['sectionId'] == $key)
            {
              switch($sectionData['sectionId'])
                {
                    case '1' :  echo "<div class='mob-exam-sub-heading'>"."About the exam"."</div>";
                                break;
                    case '2' : echo "<div class='mob-exam-sub-heading'>"."Exam pattern"."</div>";
                                break;
                    case '3' : echo "<div class='mob-exam-sub-heading'>"."Scoring in ". $examPageObj->getExamName()."</div>";
                                break;
                    case '4' : echo "<div class='mob-exam-sub-heading'>"."Important dates"."</div>";
                                break;
                    case '5' : echo "<div class='mob-exam-sub-heading'>"."Preparation tips"."</div>";
                                break;
                    case '6' : echo "<div class='mob-exam-sub-heading'>"."Practice & sample papers"."</div>";
                                break;
                    case '7' : echo "<div class='mob-exam-sub-heading'>".$examPageObj->getExamName()." Syllabus"."</div>";
                                break;
                }
            }
            else
            {   echo "<div class='mob-exam-sub-heading'>".ucfirst($value['heading'])."</div>";    }
            
        	echo "<div class='detail-info-sec sop-content exam-article-content dyanamic-content clearfix'><p> ".$value['details']."</p></div>";
         } ?>
   </div>
</section>