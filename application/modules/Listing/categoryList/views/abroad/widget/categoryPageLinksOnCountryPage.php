<?php 
    // get value for visible in the popularcoursesdata array
    $showTable = false;
    foreach($popularCoursesData as $key=>$value){
        if($value['universityCount']>0){
            $showTable = true;
            break;
        }
    }
?>
<?php if($showTable){ ?>
<table cellpadding="0" cellspacing="0" class="univ-offerdLink-table flLt">
    <tr>
        <td class="quick-link-box"><a class="font-14"><strong>Quick Links</strong></a><span class="caret2"></span></td>
        <?php foreach($popularCoursesData as $popularCourse){ ?>
            <?php if((int)$popularCourse['universityCount']>0){?>
                <td width="250px">
                    <a href="<?=($popularCourse['url'])?>" class="font-14"><strong><?=($popularCourse['name'])?> <?=($countryName=="Abroad"?"Abroad":"in ".$countryName)?></strong></a>
                    <p>(<?=$popularCourse['universityCount']?> <?=($popularCourse['universityCount'] == 1)?"college":"colleges"?>)</p>
                </td>
            <?php } ?>
        <?php } ?>
    </tr>
</table>

 <?php } ?>