<div class="lineSpace_10">&nbsp;</div>
        <input type="hidden" name="citiesForTestPrep" id="cities" value=""/>
        <input type="hidden" name="countryForTestPrep" id="country" value=""/>
<div>
<?php
if($prepExamInstitutes['total'] > 0) {
?>
<div id="testPrepColleges" class="float_L" style="width:50%">
	<div class="careerOptionPanelBrd" style="margin-right:5px;">
		<div class="careerOptionPanelHeaderBg">
			<h5><span class="blackFont fontSize_13p">Test Preparation Institutes For <?php echo $blogTitle; ?></span></h5>
		</div>
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
        <div class="mar_full_10p" style="height:200px">
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
</div>
<?php
}
?> 
<?php
    if($requiredExamInstitutes['total'] > 0) {
?>
<div id="collegeForExams" class="float_L" style="width:49.9%;">
	<div class="careerOptionPanelBrd" style="margin-left:5px;">
		<div class="careerOptionPanelHeaderBg">
			<h5><span class="blackFont fontSize_13p">Institutes that uses <?php echo $blogTitle; ?> for Admissions</span></h5>
		</div>
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
        <div class="mar_full_10p" style="height:200px">
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
    </div>
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
	<div class="clear_L"></div>
</div>

