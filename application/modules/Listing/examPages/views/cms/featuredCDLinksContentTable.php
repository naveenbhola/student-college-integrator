<div style="position:relative;">
    <table class="uni-table" cellspacing="0" cellpadding="0" style="padding:0px 15px;">
        <tr>
            <td width="79%">
                <div>
                    <label class="l-width">Showing featured CD Links of:</label>
                         <input type="text" name="examSearch" id="examSearch" class="input-txt" onkeyup="searchExam('examSearchFeatured','examSearch')" placeholder="Search for exams.." autocomplete="off" style="width:64%;"> 
                        <ul id="examSearchFeatured" class="examSearch" style="display:none;">
                            <?php foreach($examList as $id=>$name){?>
                                <li><a href="/examPages/ExamMainCMS/manageExamPageFeaturedCDLinks?examId=<?=$id?>"><?=$name?></a></li>
                            <?php } ?>
                        </ul> 
                </div>

            </td>
            <td width="21%">
               <a class="mnge-dv" style="padding:10px;" href="/examPages/ExamMainCMS/manageExamPageFeaturedCDLinks">Reset Exam</a>
            </td>
        </tr>
            
    </table>
</div>
<div class="search-section" style="box-sizing:border-box;padding:0 15px;">
    <table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0" id='featuredList'>
        <?php 
        if(is_array($featuredData) && count($featuredData) > 0){
        ?>
            <tr id="mainHeading">
                <th width="20%">Originating Exam</th>
                <th width="20%">Exam Course Group(s)</th>
                <th width="10%">Campaign Name</th>
                <th width="10%">Start Date</th>
                <th width="10%"> <a href="javascript:void(0);" id="cdEventEndDate">End Date <b style="display: none;" id="eventOrder"></b></a></th>
                <th width="10%">View Count</th>
                <th width="10%">Click Count</th>
                <th width="10%">Edit</th>
                <th width="10%">Delete</th>
            </tr>
            <?php $this->load->view('examPages/cms/featuredCDLinksContentList');
         }
         if(count($featuredData) < 1)
            {
                echo '<tr class="no-update">';
                echo '<td colspan="10">No Featured CD Links found.</td>';
                echo '</tr>';
            }
        ?>
    </table>
</div>
<?php if(isset($totalLinks) && $totalLinks > $item_per_page){?>
    <div class="load-more-sec clear-width" style="text-align: center; font-size:14px;padding:15px;">
        <a href="javascript:void(0);" id="loadMoreBtn">Show More Links</a>
    </div>
<?php }?>
