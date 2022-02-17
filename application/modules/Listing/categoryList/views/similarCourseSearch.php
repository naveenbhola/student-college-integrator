    <p><input type="text" id="keywordSuggest" name="keywordSuggest" class="similar-textfield" default="Enter Institute Name" value="<?=($instituteName) ? $instituteName : 'Enter Institute Name'?>" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" autocomplete="off" /></p>
    <div id="suggestions_container_similar"></div>
    <p id="noInstituteFoundError" style="margin-top: 5px; color: #939393; font-size: 17px; width: 400px;display: none;">Sorry no institutes found. Please retype your query or visit our MBA page by <a href="<?=$categoryPageUrl?>">clicking here</a>.</p>
    <div id="selectCourseSection" style="<?=($courseId) ? "" : "display:none;"?>">
        <select class="similar-select" id="instituteCoursesList" validate="validateSelect" required="true" caption="course">
            <?php
                if($selectedCoursesOfInstitutes)
                {
                    echo "<option value=''>Select Course of Interest</option>";
                    foreach($selectedCoursesOfInstitutes as $id=>$courseRow)
                    {
                        $selected = ($id == $courseId) ? "selected" : "";
                        echo "<option value=".$id." ".$selected.">".$courseRow."</option>";
                    }
                }
            ?>
        </select>
        <a class="orangeButtonStyle find-btn" href="javascript:void(0);" onclick="viewSimilarInsttDbTrack('', '', 'SimilarInstitute', 'Find'); <?=($pageType == "similar_home" ? "redirectToSimilarListingPage();" : "getSimilarCourses();")?>">Find</a>
    </div>
    <div style="display:none;" id="instituteCoursesList_error">
        <div style="padding-left:3px;" class="errorMsg">Please select a course.</div>
    </div>
    <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="<?=$instituteId?>" />
    <input type="hidden" name="selectedInstituteName" id="selectedInstituteName" value="<?=$instituteName?>" />