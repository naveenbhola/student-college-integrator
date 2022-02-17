<?php $this->load->view('shikshaSchema/header'); ?>

<div style='margin-bottom: 20px;'>
<h1>LDB Course Tables</h1>
<h3>LDB Courses are standard courses defined by Shiksha. An LDB course belongs to a category and sub-category and may or may not have specializations e.g. LDB Course Full Time MBA belogs to category: Management, subcategory: Full Time MBA and has specializations like Marketing, HR, Finance etc. LDB Course PHP belongs to category: Information Technology, subcategory: Programming Languages and doesn't have any specializations. </h3>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666; text-align: center;">
    <a href='/public/LDBCourse.png'>
        <img src='/public/LDBCourse.png' width='840' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tCourseSpecializationMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'categoryBoardTable')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'LDBCoursesToSubcategoryMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'clientCourseToLDBCourseMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'categoryGroupSpecializationMaster')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'courseSpecializationGroupMapping')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?>