
        <input type="hidden" name="citiesForTestPrep" id="cities" value=""/>
        <input type="hidden" name="countryForTestPrep" id="country" value=""/>
<?php
if($prepExamInstitutes['total'] > 0) {
?>
<div id="testPrepColleges">
<div class="testPreHeading OrgangeFont fontSize_14p bld">Test Preparation Institutes For <?php echo $blogTitle; ?></div>
<div class="pd_lft_rgt" style="margin:10px 0px;">
    <div  id="pagingID" class="float_R">
        <!--Pagination Related hidden fields Starts-->
        <input type="hidden" id="testprepInstitutesListStartOffSet" value="0" autocomplete="off"/>
        <input type="hidden" id="testprepInstitutesListCountOffset" value="8" autocomplete="off"/>
        <input type="hidden" id="testprepInstitutesListMethodName" value="gettestprepExamColleges" autocomplete="off"/>
        <!--Pagination Related hidden fields Ends  -->
        <div id="testprepInstitutesListPaginataionPlace1" style="position:relative; top:5px;"></div>
        <div  id="testprepInstitutesListPaginataionPlace2" style="display:none"></div>
    </div>
    <div class="bld fontSize_12p" id="testprepInstitutesListHeading">Showing 1 - <?php echo count($prepExamInstitutes['results'] ); ?> institutes out of <?php echo $prepExamInstitutes['total']; ?></div>
</div>
	<ul class="FurtherReading" id="testprepInstitutesListPlace">
        <?php
            foreach($prepExamInstitutes['results'] as $institute) {
                $instituteName = $institute['instituteName'];
                $instituteUrl = $institute['detailUrl'];
        ?>
	    <li>
            <a href="<?php echo $instituteUrl; ?>" title="<?php echo $instituteName; ?>" class="fontSize_12p"><?php echo $instituteName; ?></a>
        </li>
        <?php
            }
        ?>
	</ul>
</div>
</div>
<?php
}
?>            
<div class="lineSpace_10">&nbsp;</div>
<?php
    if($requiredExamInstitutes['total'] > 0) {
?>
<div id="collegeForExams">
<div class="testPreHeading OrgangeFont fontSize_14p bld">Institutes that uses <?php echo $blogTitle; ?> for Admissions</div>
<div class="pd_lft_rgt" style="margin:10px 0px;">
     <div  id="pagingID" class="float_R">
        <!--Pagination Related hidden fields Starts-->
        <input type="hidden" id="requiredInstitutesListStartOffSet" value="0" autocomplete="off"/>
        <input type="hidden" id="requiredInstitutesListCountOffset" value="8" autocomplete="off"/>
        <input type="hidden" id="requiredInstitutesListMethodName" value="getrequiredExamColleges" autocomplete="off"/>
        <!--Pagination Related hidden fields Ends  -->
        <div id="requiredInstitutesListPaginataionPlace1" style="position:relative; top:5px;"></div>
        <div  id="requiredInstitutesListPaginataionPlace2" style="display:none"></div>
    </div>
    <div class="bld fontSize_12p" id="requiredInstitutesListHeading">Showing 1 - <?php echo count($requiredExamInstitutes['results'] ); ?> institutes out of <?php echo $requiredExamInstitutes['total']; ?></div>
</div>
	<ul class="FurtherReading" id="requiredInstitutesListPlace">
        <?php
            foreach($requiredExamInstitutes['results'] as $institute) {
                $instituteName = $institute['instituteName'];
                $instituteUrl = $institute['detailUrl'];
        ?>
	    <li>
            <a href="<?php echo $instituteUrl; ?>" title="<?php echo $instituteName; ?>" class="fontSize_12p"><?php echo $instituteName; ?></a>
        </li>
        <?php
            }
        ?>
	</ul>
</div>
<?php
}
?>
<script>
    var examId = '<?php echo $blogId; ?>';
    try{
        doPagination(<?php echo $prepExamInstitutes['total'] ; ?>, 'testprepInstitutesListStartOffSet', 'testprepInstitutesListCountOffset', 'testprepInstitutesListPaginataionPlace1', 'testprepInstitutesListPaginataionPlace2','testprepInstitutesListMethodName');
   } catch(e) {}
    try{
        doPagination(<?php echo $requiredExamInstitutes['total'] ; ?>, 'requiredInstitutesListStartOffSet', 'requiredInstitutesListCountOffset', 'requiredInstitutesListPaginataionPlace1', 'requiredInstitutesListPaginataionPlace2','requiredInstitutesListMethodName');
   } catch(e) {}
</script>
