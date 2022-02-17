<div style="position:relative;">
    <table class="uni-table" cellspacing="0" cellpadding="0" style="padding:0px 15px;">
        <tr>
            <td width="79%">
                <div>
                    <label class="l-width">Showing featured content of:</label>
                         <input type="text" name="examSearch" id="examSearch" class="input-txt" onkeyup="searchExam('examSearchFeatured','examSearch')" placeholder="Search for exams.." autocomplete="off" style="width:64%;"> 
                        <ul id="examSearchFeatured" class="examSearch" style="display:none;">
                            <?php foreach($examList as $id=>$name){?>
                                <li><a href="/examPages/ExamMainCMS/manageExamPageFeaturedContent/institute?examId=<?=$id?>"><?=$name?></a></li>
                            <?php } ?>
                        </ul> 
                </div>

            </td>
            <td width="21%">
               <a class="mnge-dv" style="padding:10px;" href="/examPages/ExamMainCMS/manageExamPageFeaturedContent/institute">Reset Exam</a>
            </td>
        </tr>
            
    </table>
</div>
<div class="search-section" style="box-sizing:border-box;">
    <table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0" id='featuredList'>
        <?php 
        if(is_array($featuredData) && count($featuredData) > 0){
        ?>
            <tr>
                <th width="20%">Originating Exam</th>
                <th width="20%">Course Group</th>
                <th width="10%">Destination Univ/College</th>
                <th width="10%">Course</th>
                <th width="10%">CTA Text</th>
                <th width="10%">Redirection Url</th>
                <th width="10%">Start Date</th>
                <th width="10%">End Date</th>
                <th width="10%">Edit</th>
                <th width="10%">Delete</th>
            </tr>
            <?php $this->load->view('examPages/cms/featuredCollegeTableData');
         }
         if(count($featuredData) < 1)
            {
                echo '<tr class="no-update">';
                echo '<td colspan="10">No Featured College found.</td>';
                echo '</tr>';
            }
        ?>
    </table>
</div>
