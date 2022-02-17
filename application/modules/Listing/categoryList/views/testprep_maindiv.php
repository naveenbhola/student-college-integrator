<!--End_SubTab-->
<!--Start_SubSubTab-->
            <div style="height:30px;line-height:30px">
                <!--<div style="">--><div style="padding-left:17px">
                    <div class="float_L" style="width:100%">
                        <div id="shik_catMainSubSubTab" style="width:100%">
                            <div id = "clussubtab">
                                <a class="<?php if($params['course_type']=='All') echo "selectedMainSubSubTab";?>" href="<?php echo $this->url_manager->get_testprep_url($params['pagetype'], $params['blog_acronym'], $params['city_name'], '', '')?>" >All</a>
                                <a class="<?php if($params['course_type']=='Full Time') echo "selectedMainSubSubTab";?>" href="<?php echo $this->url_manager->get_testprep_url($params['pagetype'], $params['blog_acronym'], $params['city_name'], 'Full-Time', '')?>" >Full Time</a>
                                <a class="<?php if($params['course_type']=='Correspondence') echo "selectedMainSubSubTab";?>" href="<?php echo $this->url_manager->get_testprep_url($params['pagetype'], $params['blog_acronym'], $params['city_name'], 'Correspondence', '')?>" >Correspondence</a>
                                <a class="<?php if($params['course_type']=='Part Time') echo "selectedMainSubSubTab";?>" href="<?php echo $this->url_manager->get_testprep_url($params['pagetype'], $params['blog_acronym'], $params['city_name'], 'Part-Time', '')?>" >Part Time</a>
                                <a class="<?php if($params['course_type']=='E-learning') echo "selectedMainSubSubTab";?>" href="<?php echo $this->url_manager->get_testprep_url($params['pagetype'], $params['blog_acronym'], $params['city_name'], 'E-learning', '')?>" >E-learning</a>


                </div>
            </div>
        </div>
        <!--                                        <div class="float_L" style="width:29%;height:30px">
                                        	<div style="width:100%;padding:4px 0" class="txt_align_r"><select><option>Refine by Locality</option></select></div>
                                                </div>-->
        <div class="clear_L">&nbsp;</div>
    </div>
</div>


        <!--End_SubSubTab-->
        <script>
            showTabs('<?php echo 'clustab' . $countArray['selectedCluster']; ?>');
        </script>
<?php
        $COUNT = $countArray['totalcount'];
        $LOWERLIMIT = $start + 1;
        $UPPERLIMIT = ($start + $count) > $COUNT ? $COUNT : ($start + $count); ?>



        <!--Start_OutOfPages-->
<?php if ($showbar != 0) {
            if (!empty($countArray) && $showselectall == 0) {
 ?>
                <div style="background:#f1f1f3;height:33px">
                    <div class="float_L" style="width:69%">
                        <div style="width:100%;padding-top:5px">
                        </div>
                    </div>
                    <div class="float_L" style="width:30%">
                        <div style="width:100%;line-height:32px"><div class="txt_align_r" style="padding-right:10px"><b><?php echo $LOWERLIMIT; ?> -  <?php echo $UPPERLIMIT; ?> of <?php echo $countArray['totalcount'] ?></b></div></div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                </div>
<?php } ?>

<?php if (!empty($countArray) && $showselectall == 1) { ?>
                <div style="background:#f1f1f3;height:33px">
                    <div class="float_L" style="width:68%">
                        <div style="width:100%;padding-top:5px">
                            <span id = "selectalldiv">
                                <span style="position:relative;top:1px;padding-left:5px"><input type="checkbox" name="" id = "checkAll" onclick = "checkAllFields(1);" checked style = 'display:none'/></span>
                                <a href="#" id = "hrefcheckall" checked style = 'display:none' onClick = "var chkAll = document.getElementById('checkAll'); if (chkAll.checked == false) { chkAll.checked = true ; checkAllFields(1);return false;}">Select All</a> &nbsp;<span style="color:#a2a2a2"></span>&nbsp;<!-- <a href="javascript:void(0);" onclick="var chkAll = document.getElementById('checkAll'); chkAll.checked = false ; checkAllFields(1);return false;" id="hrefcheckall">Clear All</a>&nbsp;&nbsp;&nbsp;-->
                                <input type="button" value="" class="all_ShikshaBtn_spirit shik_requestEBrochureOrgBtn"  onclick="var abc = checkformMultipleApply('HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO');return abc;return false;"/>
                            </span>
                        </div>
                    </div>
                    <div class="float_L" style="width:30%">
                        <div style="width:100%;line-height:32px"><div class="txt_align_r" style="padding-right:10px"><b><?php echo $LOWERLIMIT; ?> - <?php echo $UPPERLIMIT; ?> of <?php echo $countArray['totalcount'] ?></b></div></div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                </div>
<?php }
        } ?>
        <!--End_OutOfPages-->

        <!--                                               <div class="lineSpace_10">&nbsp;</div>-->
<!--Start_Listing-->





<?php $count = count()?>
<?php            if (!empty($listings)) {
 ?>
                <div style="background:#f1f1f3;height:33px">
                    <div class="float_L" style="width:68%">
                        <div style="width:100%;padding-top:5px">
                            <span id = "selectalldiv">
                                <span style="position:relative;top:1px;padding-left:5px"><input type="checkbox" name="" id = "checkAll" onclick = "checkAllFields(1);" checked style = 'display:none'/></span> <a href="#" id = "hrefcheckall" checked style = 'display:none' onClick = "var chkAll = document.getElementById('checkAll'); if (chkAll.checked == false) { chkAll.checked = true ; checkAllFields(1);return false;}">Select All</a> &nbsp;<span style="color:#a2a2a2"></span>&nbsp;<!-- <a href="javascript:void(0);" onclick="var chkAll = document.getElementById('checkAll'); chkAll.checked = false ; checkAllFields(1);return false;" id="hrefcheckall">Clear All</a>&nbsp;&nbsp;&nbsp;--><input type="button" value="" class="all_ShikshaBtn_spirit shik_requestEBrochureOrgBtn"  onclick="var abc = checkformMultipleApply('HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO');return abc;return false;"/>
                            </span>
                        </div>
                    </div>
                    <div class="float_L" style="width:30%">
                        <div style="width:100%;line-height:32px"><div class="txt_align_r" style="padding-right:10px"><b><?php echo $pagination_data['start']; ?> - <?php echo $pagination_data['end']; ?> of <?php echo $pagination_data['count'] ?></b></div></div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                </div>
<?php } ?>



<?php foreach($listings as $listing) { ?>

            <div style="border-bottom: 1px solid rgb(231, 231, 231); padding: 4px 0pt; width: 100%;">
                <div style="padding-bottom: 3px;"><span style="position: relative; top: 1px; padding-left: 5px;">
                        <?php if($listing['type'] == 'main' || $listing['type'] == 'paid_minus_main' || $listing['type'] == 'sponsered'){?>
                        <input type="checkbox" value="<?php echo $listing['institute_id']?>" onclick="checkAllFields(2);" name="reqEbr[]">
                        <?php } ?>
                    </span>
                    <?php if($listing['type'] == 'main' || $listing['type'] == 'sponsered'){?>
                    <span style="padding-left: 29px;" class="homeShik_Icon shik_checkedIcon">&nbsp;</span>
                        <?php } else {?>

                    <span style="padding-left: 29px;" class="homeShik_Icon shik_dotedIcon">&nbsp;</span>
                    <?php } ?>
                    <span>
                        <span id="reqEbr_<?php echo $listing['institute_id']?>" locationname="<?php echo $listing['city_name']?>" displayname="<?php echo $listing['name']?>" type="institute" title="<?php echo $listing['name']?>" url="">
                            <?php
                            $instinamelength = 50;
                                    $coursenamelength = 50;
                                    if(isset($_COOKIE['client']) && $_COOKIE['client'] < 1000)
                                    {
                                        $instinamelength = 30;
                                        $coursenamelength = 30;
                                    }
                                    if(isset($_COOKIE['client']) && $_COOKIE['client'] > 1200)
                                    {
                                        $instinamelength = 80;
                                        $coursenamelength = 80;
                                    }

                                    if(strlen($listing['name']) > $instinamelength)
                                    $truncated_instt_name = substr($listing['name'],0,$instinamelength) . '...';
                                    else
                                    $truncated_instt_name = $listing['name'];
                            ?>

                            <a title="<?php echo $listing['name']?>" class="Fnt14" href="<?php echo getSeoUrl($listing['institute_id'],'institute',$listing['name'],array('location'=>array($listing['city_name'])));?>"><b><?php echo $truncated_instt_name?></b></a>
                        </span>
                    </span></div>
                <div>
                    <div style="width: 315px;" class="float_R">
                        <div style="width: 100%;" class="float_L">
                            <div style="width: 100%;">

                                <div style="width: 158px;" class="float_R">
                                    <div style="width: 100%;" class="float_L">
                                        <div style="width: 100%;">

                                            <div>
                        <?php if($listing['type'] == 'main' || $listing['type'] == 'paid_minus_main' || $listing['type'] == 'sponsered'){ ?>
                                                <input type="button" onclick="if(document.getElementById('floatad1') != null) {document.getElementById('floatad1').style.zIndex = 0;}return calloverlayInstitute(<?php echo $listing['institute_id']?>,'HOMEPAGE_CATEGORY_MIDDLEPANEL_REQUESTINFO');" class="all_ShikshaBtn_spirit shik_requestEBrochureBtn" value="">
                        <?php } ?>
                                            </div>
                                            <?php if($params['pagetype'] == 'most-viewed') {?>
                                            <div class="txt_align_r" style="padding: 10px 10px 0pt 0pt; color: rgb(132, 131, 136);">(<?php echo $listing['view_count']?> views)</div>
                                            <?php }?>
                                            <div class="lineSpace_10">&nbsp;</div>
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-right: 168px;">
                                    <div style="width: 100%;" class="float_L"><?php echo $listing['city_name']?></div>
                                </div>
                            </div>
                        </div>
                        <div class="clear_L">&nbsp;</div>
                    </div>

                    <?php
                    $course_count = 0;
                    foreach($listing['courses'] as $course) {
                    $course_count++;
                    if($course_count > 3) break;
                    ?>
                    <div style="margin-right: 325px;">
                        <div style="width: 100%;" class="float_L">
                            <div style="width: 100%;">
                                <div style="padding-left: 70px;">
                                    <div>
                                        <?php
                                    if(strlen($course['course_name']) > $coursenamelength)
                                    $truncated_course_name = substr($course['course_name'],0,$coursenamelength) . '...';
                                    else
                                        $truncated_course_name = $course['course_name'];
                                        ?>
                                        <a title="<?php echo $course['course_name']?>" href="<?php echo getSeoUrl($course['course_id'],'course',$course['course_name'],array('location'=>array($listing['city_name']), 'institute'=>$listing['name']));?>"><?php echo $truncated_course_name?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear_L">&nbsp;</div>
                    </div>
                    <?php } ?>

                    <div id="<?php echo 'showallcourse'.$listing['institute_id']?>" style = "display:none">
                    <?php
                    $courses_left = count($listing['courses']) - 3;
                    for($i = 3; $i <= count($listing['courses']); $i++) {
                    $course = $listing['courses'][$i];
                    ?>
                    <div style="margin-right: 325px;">
                        <div style="width: 100%;" class="float_L">
                            <div style="width: 100%;">
                                <div style="padding-left: 70px;">
                                    <div>
                                        <?php
                                    if(strlen($course['course_name']) > $coursenamelength)
                                    $course['course_name'] = substr($course['course_name'],0,$coursenamelength) . '...';
                                        ?>
                                        <a title="<?php echo $course['course_name']?>" href="<?php echo getSeoUrl($course['course_id'],'course',$course['course_name'],array('location'=>array($listing['city_name']), 'institute'=>$listing['name']));?>"><?php echo $course['course_name']?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear_L">&nbsp;</div>
                    </div>
                    <?php } ?>
                    </div>

                    <?php if($courses_left > 0) {?>
                    <div style="margin-right: 325px;">
                        <div style="width: 100%;" class="float_L">
                            <div style="width: 100%;">
                                <div style="padding-left: 70px;">
                                    <div>
                                        <a href="#" title = "" onClick =  "showhideCourses(this,'<?php echo $listing['institute_id'];?>');return false;">All courses</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear_L">&nbsp;</div>
                    </div>
<?php } ?>


                    <div class="clear_B">&nbsp;</div>
                </div>
                <!-- <div style="width:100%">
                                                	<div class="shik_categoryListingBrd">&nbsp;</div>
                 </div>-->
            </div>


<?php }?>


<!--End_Listing-->

<!--Start_Pagination-->
<div style="width:100%;padding:20px 0 10px 0">
<?php if ($pagination_data['total_pages'] > 1) { ?>
        <div class="float_L" style="width:40%">
            <div style="width:100%">
<?php if (1) { ?>
                        <span class="homeShik_Icon shik_checkedIcon" style="padding-left:29px">&nbsp;</span>Sponsored Listing
<?php } ?>
                    </div>
                </div>
<?php if (1) { ?>
                    <div class="float_L" style="width:60%">
                        <div style="width:100%;height:30px;line-height:30px">
                            <div class="txt_align_r pagingID" style="padding-right:10px" id="paginataionPlace1" >
                            <?php $url = $this->url_manager->get_testprep_url($params['pagetype'], $params['blog_acronym'], $params['city_name'], '', '')?>
                            <?php if($pagination_data['page'] > 1) { ?><a href="<?php echo $url."-".($pagination_data['page'] - 1)?>">Prev</a><?php }?>
                                <span id="pageNumbers">

                                <?php for($i = 1; $i <= $pagination_data['total_pages']; $i++) {?>
                                <?php if($i == $pagination_data['page']) {?>
                                <a onclick="return false;" class="show" href="#"><?php echo $i?></a>
                                    <?php } else { ?>
                                <a href="<?php echo $url."-".$i?>"><?php echo $i?></a>
                                <?php } } ?>
                            </span>
                            <?php if($pagination_data['page'] < $pagination_data['total_pages']) {?><a href="<?php echo $url."-".($pagination_data['page'] + 1)?>">Next</a><?php }?>

                            </div>
                    </div>
                </div>
                <?php } ?>
    <div class="clear_L">&nbsp;</div>
                <?php } else { ?>
                <?php } ?>
</div>
<!--End_Pagination-->

