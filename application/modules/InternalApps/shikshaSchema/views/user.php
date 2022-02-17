<?php $this->load->view('shikshaSchema/header'); ?>
<h1><a href='/shikshaSchema/ShikshaSchema/index' class='nlink'>Modules / </a><a href='/shikshaSchema/ShikshaSchema/module/<?php echo $container; ?>/<?php echo $module; ?>' class='nlink'><?php echo $container." :: ".$module; ?></a></h1>

<h1>User Tables Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>User tables store the various details of users register on Shiksha.com e.g. personal details, study preferences, educational background, registration source, login/logout tracking. We also store various flags related to user e.g. whether mobile and email are verified/valid, user has unsubscribed from emails etc.</div>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/user_db.png'>
        <img src='/public/images/user_db.png' width='840' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tuser')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tuserflag')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tUserPref')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tUserLocationPref')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tUserEducation')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tUserSpecializationPref')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tUserPref_testprep_mapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tusersourceInfo')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tuserLoginTracking')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?>
