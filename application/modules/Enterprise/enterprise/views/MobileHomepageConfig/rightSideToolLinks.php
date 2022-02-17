<div style="margin-bottom: 10px;" id="rightToolBlockContainer-<?=$iteration?>">
<table id="rightSideMainLinks" border="0">
  <tr>
  <td>Text</td><td>Link</td><td>Link type</td>
</tr>
<?php
$rightSideStudentToolLinks = $configData['popularCoursesData'][$iteration]['rightSideStudentToolLinks'];
$toolLinkLoop = $limits['toolLinks']['min'];
$toolLinkBlockCount = count($rightSideStudentToolLinks);
if($toolLinkBlockCount > $limits['toolLinks']['min'] && $toolLinkBlockCount <= $limits['toolLinks']['max'])
{
  $toolLinkLoop = $toolLinkBlockCount;
}
for($i=0; $i<$toolLinkLoop; $i++)
{
  $removeToolRow = '';
  if($i+1 > $limits['toolLinks']['min'])
  {
    $removeToolRow = '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick=\'this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);numOfRightToolBlocks['.$iteration.']--; totalSumOfRightLinksAndTools['.$iteration.']--; $j("#addMoreRightToolLinks-'.$iteration.'").show(); $j("#addMoreRightLink-'.$iteration.'").show(); \'>Remove</a>';
  }
?>
<tr>
  <td><input type="text" class="validateConfig" name="rightTool_text[<?=$iteration?>][]" value="<?=$rightSideStudentToolLinks[$i]['name']?>"/></td>
  <td><input type="text" class="validateConfig" name="rightTool_url[<?=$iteration?>][]" value="<?=$rightSideStudentToolLinks[$i]['URL']?>"/></td>
  <td><select name="rightTool_type[<?=$iteration?>][]" class="validateConfig">
    <option value="">Select type</option>
    <option value="link" <?php echo (($rightSideStudentToolLinks[$i]['type']=='link')?'selected="selected"':''); ?>>Link</option>
    <option value="layer" <?php echo (($rightSideStudentToolLinks[$i]['type']=='layer')?'selected="selected"':''); ?>>Layer</option>
    <option value="categorypage" <?php echo (($rightSideStudentToolLinks[$i]['type']=='categorypage')?'selected="selected"':''); ?>>Category Page</option>
  </select><?=$removeToolRow?></td>
</tr>
<?php
}
?>
</table>
</div>
<div style="margin-bottom:10px;">
  <?php
  $doNotShowAddMoreTool = '';
  if($configData['popularCoursesData'][$iteration]['totalSumOfRightLinksAndTools'] >= 8)
  {
    $doNotShowAddMoreTool = 'style="display:none;"';
  }
  ?>
    <a href="javascript:void(0);" <?=$doNotShowAddMoreTool?> id="addMoreRightToolLinks-<?=$iteration?>" onclick="addRightToolBlock('<?=$iteration?>')">Add more student tools</a>
</div>
<script>
  numOfRightToolBlocks[<?=$iteration?>] = '<?=$toolLinkLoop?>';
  totalSumOfRightLinksAndTools[<?=$iteration?>] += <?=$toolLinkLoop?>;
</script>