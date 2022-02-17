	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
			<div class="abroad-cms-head" style="margin-top:0; width: 99%;">
            	<?php 
                $examPageDataCount = count($viewExamPageData);?>
                    <h2 class="abroad-sub-title"><?php echo ($examPageDataCount == 0) ? 'No Records Found' : 'All Exams';?></h2>
                    <div class="flRt"><a href="./addEditExamContent" class="orange-btn" style="padding:6px 7px 8px">+ Add Exam Page</a></div>
            </div>
            <div class="search-section">
           	<?php if($examPageDataCount > 0) { ?>
                <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                    <tr>
                        <th width="5%" align="center">S.No.</th>
                        <th width="40%">
                            <span class="flLt" style="margin-top:6px;">Exam Name</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">Grade Type</span>
                        </th>
                        <th width="15%">
                        <span class="flLt" style="margin-top:6px;">Last Modified Date</span>
                        </th>
                         <th width="15%">
                        <span class="flLt" style="margin-top:6px;">Action</span>
                        </th>
                    </tr>
                    <?php 
                    $serialNo = 1;
                    foreach($viewExamPageData as $data) { ?>
                    <tr>
                    	<td align="center"><?php echo $serialNo++.'.';?></td>
                        <td>
                            <?php echo htmlspecialchars ($data['exam_name']);?>
                            <?php 
                            if(!in_array($data['exam_name'], $blogExamsList)){
                                ?>
                                <span style='font-weight:bold;font-size:12px;'>(This exam does not exist in exam list.)</span>
                                <?php
                            }
                            ?>
                            <p class="cms-sub-cat"><?php echo htmlspecialchars ($data['category_name']);?></p>
                        </td>
                        <td>
                            <p class="cms-associated-cat"><?php echo ($data['grade_type'] != '') ? htmlspecialchars($data['grade_type']) : 'N/A';?></p>
                        </td>
                        <td>
                            <p class="cms-table-date"><?php echo date('d M Y',strtotime($data['last_modified_date']));?></p>
                            <?php 
                            if($data['status'] == 'live') {
                                echo '<p class="publish-clr">Published</p>';
                            } else {
                                echo '<p class="draft-clr">Draft</p>';
                            }
                            ?>
                        </td>
                        <td>
                        <div class="edit-del-sec">
                            <a href="/examPages/ExamPagesCMS/editExamPageForm/<?php echo $data['exampage_id'];?>">Edit</a>&nbsp;&nbsp;
                            <a href="<?php echo $data['preview'];?>" target="_blank">Preview</a>&nbsp;&nbsp;
                        </div>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                <?php } ?>
            </div>
        <div class="clearFix"></div>
        </div>  
    </div>
<?php $this->load->view('common/footerNew'); ?>