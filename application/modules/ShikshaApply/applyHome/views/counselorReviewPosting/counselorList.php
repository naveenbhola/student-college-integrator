<div class="rvw-dv">
<?php if(!$userCounselorId){?>
<a href="javascript:void(0);" class="rvw-anchr rvw-arww">Select Your Counselor to Review </a>
<?php } ?>
<div class="cnslr-Rlyr">
        <div class="ovfl-aut">
        <ul id="counselorList">
         <?php foreach ($userRelatedCounselors as $key=>$value) { ?>
            <li class="counselorList counsList_<?php echo $key;?>" counselorId="<?php echo $key; ?>">
                <div class="csl-im counsList_img"><img src="<?php echo getImageUrlBySize($value['counselorImageUrl'],'64x64');?>"></div>
                <div class="csl-det">
                    <strong class="counsList_name"><?php echo $value['counsellor_name']; ?></strong>
                    <p class="counsList_expertise"><?php echo $value['counsellor_expertise']; ?></p>
                </div>
            </li>
        <?php } ?>
        </ul>
     </div>
</div>
</div>