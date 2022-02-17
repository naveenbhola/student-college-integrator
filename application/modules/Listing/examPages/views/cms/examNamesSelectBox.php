<?php

$ExamListOptionHtml = "";
foreach ($examList as $examId =>$examDetais) {
    $checked = "";
    if ($examDetais['is_featured']) {
        $checked = "checked='checked'";
    }
    
    $ExamListOptionHtml .= "<li class='section-title' style='cursor:pointer;'>"
            . "{$examDetais['exam_name']}"
            . "<input type='hidden' name='examData[examId][]' value='{$examId}'/>"
            . "<div class='flRt'> Featured: <input type='checkbox' $checked name='examData[featured][{$examId}]'/></div>"
            . "</li>";
}
if (empty($ExamListOptionHtml)) {
    $ExamListOptionHtml = "NO EXAM FOUND";
}

echo $ExamListOptionHtml;
?>