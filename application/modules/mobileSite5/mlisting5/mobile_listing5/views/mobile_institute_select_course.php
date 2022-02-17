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

<div class="slctCourseNew">
    <i class="vw_blckovrly"></i>
    <div class="vw_cntnt"><h1>Select your course below to get details</h1>
    <div class="vw_slctBx">
        <i class="icon-file vw-icnarow"></i>
        <select id="courseCategory" onchange="showDesiredCourse();" autocomplete="off">
            <option>Choose Stream</option>
            <?php foreach($course_browse_section_data as $categoryId => $categorySection) { ?>
                <option value="<?php echo $categorySection['category_name']; ?>"><?php echo $categorySection['category_name']; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="vw_slctBx">
        <i class="icon-file vw-icnarow"></i>
        <select id="courseSubcategory" onchange="showCourses();" autocomplete="off">
            <option value="default">Select Course Type</option>
        </select>
    </div>
    <div class="vw_slctBx">
        <i class="icon-file vw-icnarow"></i>
        <select id="courseNames" autocomplete="off">
            <option>Select Course</option>
        </select>
    </div>
    <a class="vw_detail" onclick="redirectToCoursePage();">View details</a>
    </div>
</div>

<script>
    function showDesiredCourse() {
        //reset option
        var html = getSubcategoryNameSelectBox($('#courseCategory').val());
        $('#courseSubcategory').html(html);
        $("#courseSubcategory").selectmenu('refresh', true);
        $('#courseNames').selectmenu('refresh', true);
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
        var html = getCourseNameSelectBox($('#courseSubcategory').val());
        $('#courseNames').html(html);
        $('#courseNames').selectmenu('refresh', true);
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
        if($('#courseNames').val() != "Select Course") {
            window.location.href = atob($('#courseNames').val());
        }
        else {
            alert("Please select a course first");
        }
    }
</script>