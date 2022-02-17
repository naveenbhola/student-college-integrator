<div style="margin-bottom: 10px;" id="rightLinkBlockContainer-<?=$iteration?>">
<table id="rightSideMainLinks" border="0">
<tr>
  <td>Text</td><td>Link</td><td>Link type</td>
</tr>
<?php
$rightSideMainLinks = $configData['popularCoursesData'][$iteration]['rightSideMainLinks'];
//_p($rightSideMainLinks);
$mainLinkLoop = $limits['rightLinks']['min'];
$mainLinkBlockCount = count($rightSideMainLinks);
if($mainLinkBlockCount > $limits['rightLinks']['min'] && $mainLinkBlockCount <= $limits['rightLinks']['max'])
{
  $mainLinkLoop = $mainLinkBlockCount;
}
for($i=0; $i<$mainLinkLoop; $i++)
{
  $removeLinkRow = '';
  if($i+1 > $limits['rightLinks']['min'])
  {
    $removeLinkRow = '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick=\'this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);numOfRightLinkBlocks['.$iteration.']--; totalSumOfRightLinksAndTools['.$iteration.']--; $j("#addMoreRightLink-'.$iteration.'").show(); $j("#addMoreRightToolLinks-'.$iteration.'").show(); \'>Remove</a>';
  }
?>
<tr>
  <td><input type="text" class="validateConfig" name="rightLink_text[<?=$iteration?>][]" value="<?=$rightSideMainLinks[$i]['name']?>"/></td>
  <td><input type="text" class="validateConfig" name="rightLink_url[<?=$iteration?>][]" value="<?=$rightSideMainLinks[$i]['URL']?>"/></td>
  <td><select name="rightLink_type[<?=$iteration?>][]" class="validateConfig">
    <option value="">Select type</option>
    <option value="link" <?php echo (($rightSideMainLinks[$i]['type']=='link')?'selected="selected"':''); ?>>Link</option>
    <option value="layer" <?php echo (($rightSideMainLinks[$i]['type']=='layer')?'selected="selected"':''); ?>>Layer</option>
    <option value="categorypage" <?php echo (($rightSideMainLinks[$i]['type']=='categorypage')?'selected="selected"':''); ?>>Category Page</option>
  </select><?=$removeLinkRow?></td>
</tr>
<?php
}
?>
</table>
</div>
<div style="margin-bottom:10px;">
  <?php
  $doNotShowAddMoreLink = '';
  if($configData['popularCoursesData'][$iteration]['totalSumOfRightLinksAndTools'] >= 8)
  {
    $doNotShowAddMoreLink = 'style="display:none;"';
  }
  ?>
    <a href="javascript:void(0);" <?=$doNotShowAddMoreLink?> id="addMoreRightLink-<?=$iteration?>" onclick="addRightLinkBlock('<?=$iteration?>')">Add more links</a>
</div>
<script>
  numOfRightLinkBlocks[<?=$iteration?>] = '<?=$mainLinkLoop?>';
  totalSumOfRightLinksAndTools[<?=$iteration?>] = <?=$mainLinkLoop?>;
</script>