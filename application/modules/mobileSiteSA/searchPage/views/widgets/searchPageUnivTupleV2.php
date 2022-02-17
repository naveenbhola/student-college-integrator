<?php
 if(count($univData > 0)){
foreach($univData as $univId => $uniTupleData){

?>
<div class="mb20" data-enhance="false">
<div class="srcRs-lst" style="border-bottom:1px solid #ccc;padding-bottom:10px;">
<div>
<div class="srcRs-det clearfix">
<div class="rsCr-img flLt"><a href="<?php echo $uniTupleData['univSeoUrl'];?>" target="_blank" class="tl" loc="img" lid="<?php echo $uniTupleData['univId'];?>"><img height="90" width="135" src="<?php echo $uniTupleData['logoLink'];?>" alt="<?php echo htmlentities($uniTupleData['univName']);?>"/></a></div>
<div class="rsCr-inf">
<p class="src-uni">
<strong><a href="<?php echo $uniTupleData['univSeoUrl'];?>" target="_blank" class="tl" loc="utitle" lid="<?php echo $uniTupleData['univId'];?>"><?php echo ($uniTupleData['univName']);?></a></strong>
<span><?php echo $uniTupleData['cityName'].', ';?><?php echo $uniTupleData['countryName'];?></span>
</p>
</div>
</div>
<div class="srcCr-info">
<ul class="cr-dur">
<?php if($uniTupleData['univType']!=''){?>
<li><label>University Type</label> <span><?php echo ucfirst($uniTupleData['univType']);?></span></li>
<?php } ?>
<?php if($uniTupleData['estYear']!=''){?>
<li><label>Established in </label> <span><?php echo $uniTupleData['estYear'];?></span></li>
<?php } ?>
<?php if($uniTupleData['mealFee'] !='' && $uniTupleData['mealRawValue']>0){?>
<li><label>Room & Meals </label> <span><?php echo $uniTupleData['mealFee'];?></span></li>
<?php } ?>
</ul>
<?php if($uniTupleData['rank']!='' && $uniTupleData['rankURL'] !=''){?>
<p style="padding-bottom:5px;">Ranked <?php echo htmlentities($uniTupleData['rank']);?> in <a href="<?php echo $uniTupleData['rankURL'];?>" target="_blank" class="tl" loc="rutitle" lid="<?php echo $uniTupleData['univId'];?>"><?php echo ($uniTupleData['rankName']);?></a></p>
<?php } ?>
<strong class="pop-crse">Popular courses</strong>
<ul class="pop-crseList">
<?php foreach ($uniTupleData['course'] as $key => $courseData) {?>
<li><a href="<?php echo $courseData['cSeoUrl'];?>" target="_blank" class="tl" loc="popCTitle" lid="<?php echo $courseData['cId'];?>"><?php echo ($courseData['cName']);?></a></li>
<?php } ?>
</ul>
<?php
	$today = date("Y-m-d");
    if($uniTupleData['announcementSection']['text'] && $today >= $uniTupleData['announcementSection']['startdate'] && $today <= $uniTupleData['announcementSection']['enddate']) {
                ?>
<div class="Annoncement-Box">
  <strong>Annoncement</strong>
  <div class="Announcement-section">
    <p> <?php echo $uniTupleData['announcementSection']['text'] ?></p>
      <p><?php echo $uniTupleData['announcementSection']['actiontext']?></p>
  </div>
</div>
<?php } ?>
<div class="srcBtn-cl">
<a href="<?php echo $uniTupleData['univSeoUrl'];?>" target="_blank" class="crse-btn2 view-Det tl" loc="udbut" lid="<?php echo $uniTupleData['univId'];?>">View details</a>
</div>
</div>
</div>
</div>
</div>
<?php  }}?>
