<?php if($instituteType == 1){?>
<?php
$salaryStats = $details['courseDetails']['0']['courseAttributes'];

$maxSalary = 0;
$minSalary = 0;
$avgSalary = 0;
$unit = "";
foreach($salaryStats as $salary){

    if($salary['attribute'] == 'SalaryMin'){
        //$salary['value'] = explode(" ",$salary['value']);
        $minSalary = $salary['value'];
        //$unit = $salary['value']['1'];
    }

    if($salary['attribute'] == 'SalaryMax'){
        //$salary['value'] = explode(" ",$salary['value']);
        $maxSalary = $salary['value'];
        //$unit = $salary['value']['1'];
    }

    if($salary['attribute'] == 'SalaryAvg'){
        //$salary['value'] = explode(" ",$salary['value']);
        $avgSalary = $salary['value'];
        //$unit = $salary['value']['1'];
    }

    if($salary['attribute'] == 'SalaryCurrency'){
        //$salary['value'] = explode(" ",$salary['value']);
        //$avgSalary = $salary['value']['0'];
        $unit = $salary['value'];
    }
}


    $salaryForIndex = ($maxSalary!=0)?$maxSalary:(($avgSalary!=0)?$avgSalary:$minSalary);
    
    if($salaryForIndex%5!=0){
        $graphIndex = $salaryForIndex +'10'-($salaryForIndex%10);
    }else{
        $graphIndex = $salaryForIndex;
    }

    $minSalaryPercent = ($minSalary*100)/$graphIndex;
    $maxSalaryPercent = ($maxSalary*100)/$graphIndex;
    $avgSalaryPercent = ($avgSalary*100)/$graphIndex;




?>
<?php if(($minSalary||$maxSalary||$avgSalary)){?>
<?php if($unit == 'INR'){
    ?>
<div class="wdh100">

<div class="nlt_head Fnt14 bld mb10">Placement - Statistics</div>

<div class="nlt_graph">
<div class="wdh100">
    <?php if($minSalaryPercent!=0){?>
<div class="bcmn bar1" style="width:<?php echo $minSalaryPercent;?>%"><span class="pl5"><?php echo $minSalary/100000;?><?php echo " L";?></span></div>
    <?php }else{?>
<div class="bcmn " style="width:0%">&nbsp</div>
<?php }?>
<?php if($maxSalaryPercent!=0){?>
<div class="bcmn bar2" style="width:<?php echo $maxSalaryPercent;?>%"><span class="pl5"><?php echo $maxSalary/100000;?><?php echo " L";?></span></div>
<?php }else{?>
<div class="bcmn" style="width:0%">&nbsp</div>
<?php }?>
<?php if($avgSalaryPercent!=0){?>
<div class="bcmn bar3" style="width:<?php echo $avgSalaryPercent;?>%"><span class="pl5"><?php echo $avgSalary/100000;?><?php echo " L";?></span></div>
<?php }else{?>
<div class="bcmn " style="width:0%">&nbsp</div>
<?php }?>
</div>
</div>
<div style="height:15px; margin:4px 1px 0 0; overflow:hidden">
<div class="float_R">
<div class="float_L tar w31 barPnt">0</div>
<div class="float_L tar w31 barPnt"><?php echo ($graphIndex*1)/500000;?></div>
<div class="float_L tar w33 barPnt"><?php echo ($graphIndex*2)/500000;?></div>
<div class="float_L tar w34 barPnt"><?php echo ($graphIndex*3)/500000;?></div>
<div class="float_L tar w36 barPnt"><?php echo ($graphIndex*4)/500000;?></div>
<div class="float_L tar w33 barPnt"><?php echo ($graphIndex*5)/500000;?></div>
</div>
</div>

<div style="height:10px; padding:0px 0 0 155px; overflow:hidden;">
<div class="float_L tar " style="font-size:9px;">(in <?php echo $unit;?>)</div>
</div>

</div>
 <div class="lineSpace_20">&nbsp;</div>
 <?php }else{

     ?>

 <div class="wdh100">

<div class="nlt_head Fnt14 bld mb10">Placement - Statistics</div>

<div class="nlt_graph">
<div class="wdh100">
    <?php if($minSalaryPercent!=0){?>
<div class="bcmn bar1" style="width:<?php echo $minSalaryPercent;?>%"><span class="pl5"><?php echo $minSalary;?></span></div>
    <?php }else{?>
<div class="bcmn " style="width:0%">&nbsp</div>
<?php }?>
<?php if($maxSalaryPercent!=0){?>
<div class="bcmn bar2" style="width:<?php echo $maxSalaryPercent;?>%"><span class="pl5"><?php echo $maxSalary;?></span></div>
<?php }else{?>
<div class="bcmn " style="width:0%">&nbsp</div>
<?php }?>
<?php if($avgSalaryPercent!=0){?>
<div class="bcmn bar3" style="width:<?php echo $avgSalaryPercent;?>%"><span class="pl5"><?php echo $avgSalary;?></span></div>
<?php }else{?>
<div class="bcmn " style="width:0%">&nbsp</div>
<?php }?>
</div>
</div>
<div style="height:15px; margin:4px 1px 0 0; overflow:hidden">
<div class="float_R">
<div class="float_L tar w31 barPnt">0</div>
<div class="float_L tar w31 barPnt"><?php echo ($graphIndex*1)/5000;?></div>
<div class="float_L tar w33 barPnt"><?php echo ($graphIndex*2)/5000;?></div>
<div class="float_L tar w34 barPnt"><?php echo ($graphIndex*3)/5000;?></div>
<div class="float_L tar w36 barPnt"><?php echo ($graphIndex*4)/5000;?></div>
<div class="float_L tar w33 barPnt"><?php echo ($graphIndex*5)/5000;?></div>
</div>
</div>

<div style="height:10px; padding:0px 0 0 155px; overflow:hidden;">
<div class="float_L tar " style="font-size:9px;">(in <?php echo $unit;?>)</div>
</div>

</div>
 <div class="lineSpace_20">&nbsp;</div>
 <?php }?>
<?php }?>
<?php }?>
