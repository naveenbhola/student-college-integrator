<div style='width:1170px; margin-left:15px; border-bottom:0px solid #ddd; margin-bottom: 0px; background: linear-gradient(#f8f8f8, #f2f2f2);  margin-top:20px; border-left:1px solid #ddd; border-right:1px solid #ddd; border-top:1px solid #ddd;'>
<ul class='internaltabs'>
  <li><a href="#" id='tablink1' onclick="activateTab('1')" class='internaltabsactive' style="border-bottom:3px solid #F44336;">New (<span id='newQueryCount'><?php echo $diffNew;?></span>)</a></li>
  <li><a href="#" id='tablink2' onclick="activateTab('2')"  style="border-bottom:3px solid #4CAF50;">Fixed (<span id='queryReduced'><?php echo $diffRemoved;?></span>)</a></li>
  <li><a href="#" id='tablink3' onclick="activateTab('3')" style="border-bottom:3px solid #ffc107;">Still There (<span id='queryReduced'><?php echo $diffSame;?></span>)</a></li>
  <div class='clearFix'></div>
</ul>
</div>