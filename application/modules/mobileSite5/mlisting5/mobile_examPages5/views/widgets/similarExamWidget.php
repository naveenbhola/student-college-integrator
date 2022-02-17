<div class="similar-exam-wrap">
        <h2 class="similar-exam-title">View Similar Exam</h2>
    <div class="update-form clearfix">
        <ul class="exam-list">
        <?php 
        $i   = 0;
        $len = count($similarExams);
        foreach($similarExams as $exam){ 
        if ($i == $len - 1) {
        $class = "class='last'";
        }else{
        $class = "";
        }
        ?>
        <li <?php echo $class ?>>
                <div class="exam-col">
                        <div class="exam-col-img"><i class="exam-mini-sprite viewedExam-icn"></i></div>
                </div>
                <div class="exam-title"><a href="<?php echo $exam['exam_url'] ?>" title="<?php echo $exam['exam_name'] ?> Exam" onclick="trackEventByGAMobile('HTML5_EXAM_SIMILAR_WIDGET_<?php echo $exam['exam_name'];?>');"><?php echo $exam['exam_name']; ?></a></div>
                <script>//trackEventByGAMobile('HTML5_LOAD_EXAM_SIMILAR_WIDGET_<?php echo $exam['exam_name'];?>');</script>
        </li>
        <?php $i++; } ?>
        </ul>
    </div>
</div>
