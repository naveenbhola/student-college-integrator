<div style="margin-bottom: 10px;" id="popularExamBlockContainer-<?=$iteration?>">
<table id="rightSideMainLinks" border="0">
<tr>
  <td>Text</td><td>Exam</td>
</tr>
<?php
$popularExams = $configData['popularCoursesData'][$iteration]['popularExams'];
$examLoop = $limits['popularExams']['min'];
$examBlockCount = count($popularExams);
if($examBlockCount > $limits['popularExams']['min'] && $examBlockCount <= $limits['popularExams']['max'])
{
  $examLoop = $examBlockCount;
}
for($i=0; $i<$examLoop; $i++)
{
  $removeExamRow = '';
  if($i+1 > $limits['popularExams']['min'])
  {
    $removeExamRow = '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick=\'this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);numOfExamBlocks['.$iteration.']--; $j("#addMorePopularExamLinks-'.$iteration.'").show();\'>Remove</a>';
  }
?>
<tr>
  <td><input type="text" class="validateConfig" name="popularExam_text[<?=$iteration?>][]" value="<?=$popularExams[$i]['name']?>"/></td>
  <td><select name="popularExam_exam[<?=$iteration?>][]" class="validateConfig">
    <option value="">Select exam</option>
    <?php
    foreach($configData['examList'] as $exam)
    {
      echo '<option value="'.$exam['name'].'" '.((strtolower($popularExams[$i]['examName'])==strtolower($exam['name']))?'selected="selected"':'').'>'.$exam['name'].'</option>';
    }
    ?>
  </select><?=$removeExamRow?></td>
</tr>
<?php
}
?>
</table>
</div>
<div style="margin-bottom:10px;">
  <?php
  $doNotShowAddMoreExam = '';
  if($examBlockCount >= $limits['popularExams']['max'])
  {
    $doNotShowAddMoreExam = 'style="display:none;"';
  }
  ?>
    <a href="javascript:void(0);" <?=$doNotShowAddMoreExam?> id="addMorePopularExamLinks-<?=$iteration?>" onclick="addPopularExamBlock('<?=$iteration?>')">Add more exam</a>
</div>
<script>
  numOfExamBlocks[<?=$iteration?>] = '<?=$examLoop?>';
</script>