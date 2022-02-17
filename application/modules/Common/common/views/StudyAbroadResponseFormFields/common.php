<script>
function blurScore(scoreId)
{
    var scoreObj = $(scoreId);
    if(scoreObj.value == '') {
        scoreObj.value = 'Score';
    }
}
function blurExamName(scoreId)
{
    var scoreObj = $(scoreId);
    if(scoreObj.value == '') {
        scoreObj.value = 'Name';
    }
}
function showExamScore(sb,widget,i)
{
    $('examScore_'+i+'_'+widget).style.display = 'none';
    $('exam_score_'+i+'_'+widget).value = 'Score';
    $('exam_score_'+i+'_'+widget+'_error').style.display = $('exam_score_'+i+'_'+widget+'_error').parentNode.style.display = 'none';
    $('exam_score_'+i+'_'+widget+'_error').innerHTML = '';
    $('exam_name_'+i+'_'+widget).style.display =  $('exam_name_'+i+'_'+widget).parentNode.style.display = 'none';
    $('exam_name_'+i+'_'+widget+'_error').style.display = $('exam_name_'+i+'_'+widget+'_error').parentNode.style.display = 'none';
    $('exam_name_'+i+'_'+widget+'_error').innerHTML = '';
    
    var value = sb.options[sb.selectedIndex].value;
    if(value) {
        if(isExamAlreadySelected(widget,value,i)) {
            alert("You have already selected "+value+'.');
            sb.selectedIndex = 0;
            hideExamScore(widget,i);
        }
        else {
            $('examScore_'+i+'_'+widget).style.display = '';
            if(value == 'Other') {
                $('exam_name_'+i+'_'+widget).style.display = '';
                //$('exam_name_'+i+'_'+widget).focus();
                $('exam_name_'+i+'_'+widget).parentNode.style.display = '';
            }
            else {
                //$('exam_score_'+i+'_'+widget).focus();
            }
            $('examScore_'+i+'_'+widget).parentNode.style.display = '';
        }
    }
    else {
        hideExamScore(widget,i);
    }
    $('exam_score_'+i+'_'+widget).focus();
    sb.focus();
}
function hideExamScore(widget,i)
{
    $('examScore_'+i+'_'+widget).style.display = 'none';
    $('exam_score_'+i+'_'+widget).value = 'Score';
    $('exam_score_'+i+'_'+widget+'_error').style.display = 'none';
    $('exam_score_'+i+'_'+widget+'_error').parentNode.style.display = 'none';
    $('exam_score_'+i+'_'+widget+'_error').innerHTML = '';
}
function isExamAlreadySelected(widget,value,num)
{
    var exams = document.getElementsByName('exams_'+widget+'[]');
    for(i=1;i<=exams.length;i++) {
        var sb = $('exams_'+i+'_'+widget);
        var selectedValue = sb.options[sb.selectedIndex].value;
        if(selectedValue && selectedValue == value && num != i) {
            return true;
        }
    }
    return false;
}
function addMoreExam(widget)
{
    var exams = document.getElementsByName('exams_'+widget+'[]');
    for(i=1;i<=exams.length;i++) {
        if($('exam_block_'+i+'_'+widget).style.display == 'none') {
            $('exam_block_'+i+'_'+widget).style.display = 'block';
            if(i == exams.length) {
                $('add_exam_block_'+widget).style.display = 'none';
            }
            break;
        }
    }
    if($j('#compare-cont').length) {
        $j('#mainWrapper').height($j('#compare-cont').height()+70);
    }
}
function checkScore(widget)
{
    var scoreRangeMap = {"SAT":["600","2400"],"GMAT":["400","800"],"GRE":["500","1600"]};
    
    var exams = document.getElementsByName('exams_'+widget+'[]');
    for(i=1;i<=exams.length;i++) {
        $('exam_score_'+i+'_'+widget+'_error').style.display = $('exam_score_'+i+'_'+widget+'_error').parentNode.style.display = 'none';
        $('exam_score_'+i+'_'+widget+'_error').innerHTML = '';
        if($('examScore_'+i+'_'+widget).style.display != 'none') {
            scoreValue = trim($('exam_score_'+i+'_'+widget).value);
            examName = $('exams_'+i+'_'+widget).value;
            if(scoreValue == '' || scoreValue == 'Score') { 
                continue;
            }
            scoreValue = parseInt(scoreValue);
            if(isNaN(scoreValue)) {
                $('exam_score_'+i+'_'+widget+'_error').style.display = $('exam_score_'+i+'_'+widget+'_error').parentNode.style.display = 'inline';
                $('exam_score_'+i+'_'+widget+'_error').innerHTML = 'Please enter a numeric value for score.';
                error = true;
            }
            else {
                if(scoreRangeMap[examName]) {
                    if(scoreValue < parseInt(scoreRangeMap[examName][0]) || scoreValue > parseInt(scoreRangeMap[examName][1])) {
                        $('exam_score_'+i+'_'+widget+'_error').style.display = $('exam_score_'+i+'_'+widget+'_error').parentNode.style.display = 'inline';
                        $('exam_score_'+i+'_'+widget+'_error').innerHTML = 'Please enter a value between '+scoreRangeMap[examName][0]+' and '+scoreRangeMap[examName][1]+' for '+examName+' score.';
                        error = true;
                    }
                }
            }
        }
    }
    if($j('#compare-cont').length) {
        $j('#mainWrapper').height($j('#compare-cont').height()+70);
    }
}
function checkExamName(widget,i)
{
    var name = trim($('exam_name_'+i+'_'+widget).value);
    if(name == '') {
        $('exam_name_'+i+'_'+widget+'_error').style.display = $('exam_name_'+i+'_'+widget+'_error').parentNode.style.display = 'block';
        $('exam_name_'+i+'_'+widget+'_error').innerHTML = 'Please enter exam name.';
    }
    else {
        $('exam_name_'+i+'_'+widget+'_error').style.display = $('exam_name_'+i+'_'+widget+'_error').parentNode.style.display = 'none';
        $('exam_name_'+i+'_'+widget+'_error').innerHTML = '';
    }
    if($j('#compare-cont').length) {
        $j('#mainWrapper').height($j('#compare-cont').height()+70);
    }
}
</script>
