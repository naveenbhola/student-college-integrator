<div style="position:relative;">
    <table class="uni-table" cellspacing="0" cellpadding="0">
        <tr>
            <td width="79%">
                <div>
                    <label class="l-width">Showing updates of:</label>
                         <input type="text" name="examField" id="examField" class="input-txt" onkeyup="searchExam('examSearchOptions','examField')" placeholder="Search for exams.." autocomplete="off" style="width:64%;"> 
                        <ul id="examSearchOptions" style="display:none;">
                            <?php foreach($examList as $id=>$name){?>
                                <li><a href="/examPages/ExamMainCMS/manageExamPageAnnouncements/delete?examId=<?=$id?>"><?=$name?></a></li>
                            <?php } ?>
                        </ul> 
                        <div><div id="examField_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                        
                </div>

            </td>
            <td width="21%">
               <a class="mnge-dv" style="padding:10px;" href="/examPages/ExamMainCMS/manageExamPageAnnouncements/delete">Reset Exam</a>
            </td>
        </tr>
            
    </table>
</div>
       
<div class="search-section">
    <table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0" id='examUpdateList'>
        <?php 
        if(is_array($updateDetails) && count($updateDetails) > 0){
        ?>
            <tr>
                <th width="30%">Recent Updates</th>
                <th width="30%">Announcement URL</th>
                <th width="20%">Exam Name<br/>Exam Course(s)</th>
                <th width="10%">Date Published</th>
                <th width="10%">Action</th>
            </tr>
            <?php $this->load->view('examPages/cms/updateListExam');
         }
         if(count($updateDetails) < 1)
            {
                echo '<tr class="no-update">';
                echo '<td colspan="10">No update found.</td>';
                echo '</tr>';
            }
        ?>
    </table>
</div>
<?php if(isset($totalUpdates) && $totalUpdates >10){?>
    <div class="load-more-sec clear-width" style="text-align: center; font-size:14px;padding:15px;">
        <a href="javascript:void(0);" id="loadMoreBtn">Show More Updates</a>
    </div>
<?php }?>