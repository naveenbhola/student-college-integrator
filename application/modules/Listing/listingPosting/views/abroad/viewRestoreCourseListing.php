
<div class="abroad-cms-rt-box">
    <div class="abroad-cms-head" style="margin-top:0;">
        <h2 class="abroad-sub-title">Restore Course</h2>
    </div>

    <div class="search-section">
        <div class="adv-search-sec restoreCrse-Adv">
            <div class="cms-adv-box restoreCrse-Div">
                <div class="cms-search-box restoreCrse-search">
                    <i class="abroad-cms-sprite search-icon"></i>
                    <input name="searchCourse" id="searchCourseId" type="text" placeholder="Search Course id" class="search-field">
                    <a href="javascript:void(0);" onClick="searchRestoreCourse()" class="search-btn">Search</a>
                </div>
            </div>
        </div>
        <?php if(empty($showResult)){
            $cssclass = "restore_Results";
        }else{
            $cssclass = "";
        }  ?>
        <div class="<?php echo $cssclass; ?>">
            <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                <tbody><tr>
                    <th width="10%" align="center">
                        <span class="flLt" style="margin-top:6px;">Course Id</span>
                    </th>
                    <th width="30%">
                        <span class="flLt" style="margin-top:6px;">Course Name</span>
                    </th>
                    <th width="25%">
                        <span class="flLt" style="margin-top:6px;">University Name</span>
                    </th>
                    <th width="15%">
                        <span class="flLt" style="margin-top:6px;">Deleted Date</span>
                    </th>
                    <th width="20%">
                        <span class="flLt" style="margin-top:6px;"></span>
                    </th>
                </tr>
                <?php if(!empty($courseData)) { ?>
                    <tr>
                        <td><?php echo  $courseData['course_id']; ?></td>
                        <td>
                            <p><?php echo  htmlspecialchars($courseData['courseTitle']); ?></p>
                            <p class="cms-sub-cat"><?php echo $courseData['course_level_1'];
                                if($courseData['parentCategory_name']){echo " ".$courseData['parentCategory_name'];}
                                if($courseData['subCategory_name']){echo ", ".$courseData['subCategory_name'];}	?></p>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($courseData['university_name']); ?>
                            <p class="cms-sub-cat"><?php echo htmlspecialchars($courseData['city_name'].", ".$courseData['country_name']);?></p>
                        </td>
                        <td><p class="cms-table-date"><?php echo $courseData['deleted_date'];?></p></td>
                        <td><a href="javascript:void(0);" id="restoreCourse" onclick=restoreCourse("<?php echo $courseData['course_id']; ?>") class="orange-btn">Restore</a></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td colspan=5>
                            <p class="no-found"><?php echo !empty($errorMessage) ? $errorMessage : 'No deleted course found for given course id.'; ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
</div>
