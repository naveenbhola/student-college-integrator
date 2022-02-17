<?php $this->load->view('shikshaSchema/header'); ?>
<h1><a href='/shikshaSchema/ShikshaSchema/index' class='nlink'>Modules / </a><a href='/shikshaSchema/ShikshaSchema/module/<?php echo $container; ?>/<?php echo $module; ?>' class='nlink'><?php echo $container." :: ".$module; ?></a></h1>

<h1>MMP Tables Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>MMP tables store the various details of marketing pages customized on Shiksha.com.</div>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/mmp_db.JPG'>
        <img src='/public/images/mmp_db.JPG' width='840' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'marketing_page_master')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'cmp_formcustomization')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'mmp_pageid_formid_mapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'mmp_forms')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'marketing_pageid_courses_mapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'LDBCoursesToSubcategoryMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'categoryBoardTable')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tCourseSpecializationMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'blogTable')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?>