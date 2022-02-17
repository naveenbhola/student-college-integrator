<script type="text/javascript">
    var streamSubcategoryName = new Array();
    var subCategoryCourseName = new Array();
    var subCategoryCourseNameURL = new Array();
    <?php foreach($course_browse_section_data as $streamName) { ?>
        streamSubcategoryName["<?php echo $streamName['category_name'];?>"] = [];
        <?php foreach($streamName['subcategory_courses'] as $subcategoryNames) { ?>
            streamSubcategoryName["<?php echo $streamName['category_name'];?>"].push("<?php echo $subcategoryNames['subcategory_short_name'];?>");
            subCategoryCourseName["<?php echo $subcategoryNames['subcategory_short_name'];?>"] = [];
            subCategoryCourseNameURL["<?php echo $subcategoryNames['subcategory_short_name'];?>"] = [];
            <?php foreach($subcategoryNames['courses'] as $subcategoryCourses) { ?>
                subCategoryCourseName["<?php echo $subcategoryNames['subcategory_short_name'];?>"].push("<?php echo $subcategoryCourses['course_title'];?>");
                subCategoryCourseNameURL["<?php echo $subcategoryNames['subcategory_short_name'];?>"].push("<?php echo $subcategoryCourses['course_url'];?>");
            <?php }
        }
    } ?>
</script>

<!-- institute header top form new -->
<p style="clear:both"></p><div class="slctCourseNew">
    <i class="vw_blckovrly"></i>
    <div class="vw_cntnt">
    <div class="vw_universty">
        <span><h1 class="vw_universty1"><?php echo html_escape($institute->getName()); ?>,<b><?=(($currentLocation->getLocality() && $currentLocation->getLocality()->getName()) ? $currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName()?></b></h1>
        </span>
    </div>
    <div class="vw_frmContnr">
        <p class="vw_universty2">Select your course below to get details</p>
        <div class="vw_slctBx vw_slctBx1">
    <i class="common-sprite vw-icnarow"></i>
    <select id="courseCategory" onchange="showDesiredCourse();" class="stream-select" autocomplete="off">
        <option>Choose Stream</option>
        <?php foreach($course_browse_section_data as $categoryId => $categorySection) { ?>
            <option value="<?php echo $categorySection['category_name']; ?>"><?php echo $categorySection['category_name']; ?></option>
        <?php } ?>
    </select>
</div>
<div class="vw_slctBx vw_slctBx2">
    <i class="common-sprite  vw-icnarow"></i>
    <select id="courseSubcategory" onchange="showCourses();" class="stream-select">
        <option value="default">Select Course Type</option>
    </select>
</div>
<div class="vw_slctBx vw_slctBx3">
    <i class="common-sprite  vw-icnarow"></i>
    <select id="courseNames" class="stream-select">
        <option>Select Course</option>
    </select>
</div>
<div class="vw_slctBx vw_slctBx4 txtClrActvWhite" onclick="redirectToCoursePage();">
    View details
</div>
    </div>
    </div>
</div><p style="clear:both"></p>

<script>
    function showDesiredCourse() {
        //reset option
        var html = getSubcategoryNameSelectBox($j('#courseCategory').val());
        $j('#courseSubcategory').html(html);
        $j('#courseSubcategory').val('default');
        $j('#courseNames').val('Select Course');
        showCourses();
    }
    
    function getSubcategoryNameSelectBox(categoryName) {
        var html = "<option value='default'>Select Course Type</option>";
        if(categoryName != 'Choose Stream') {
            for(var i = 0; i < streamSubcategoryName[categoryName].length; i++) {
                html += "<option value="+btoa(streamSubcategoryName[categoryName][i])+">"+streamSubcategoryName[categoryName][i]+"</option>";
            }
        }
        return html;
    }

    function showCourses() {
        var html = getCourseNameSelectBox($j('#courseSubcategory').val());
        $j('#courseNames').html(html);
        $j('#courseNames').val('Select Course');
    }
    
    function getCourseNameSelectBox(courseName) {
        var html = "<option value='Select Course'>Select Course</option>";
        if(courseName != 'default') {
            var courseNames = atob(courseName);
            for(var i = 0; i < subCategoryCourseName[courseNames].length; i++) {
                html += "<option value="+btoa(subCategoryCourseNameURL[courseNames][i])+">"+subCategoryCourseName[courseNames][i]+"</option>";
            }
        }
        return html;
    }

    function redirectToCoursePage() {
        if($j('#courseNames').val() != "Select Course") {
            window.location.href = atob($j('#courseNames').val());
        }
        else {
            alert("Please select a course first");
        }
    }
</script>
<!-- institute header top form new ends-->