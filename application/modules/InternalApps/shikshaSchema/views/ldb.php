<?php $this->load->view('shikshaSchema/header'); ?>

<div style='margin-bottom: 20px;'>
<h1>Lead Database/Student Search Tables</h1>
<h3>These tables store the data related to student search in enterprise site. Clients (i.e. Institutes) can login to enterprise site and search for leads by selecting criteria e.g. desired course, preferred study location, educational background etc. and then contact/view details of users (i.e. prospective students) in the search results. The credits are deducted from client's account whenever a student's details are viewed or student is contacted via email or sms. The number of credits to be deducted depends upon the action type (view details/email/sms) and user's desired course. Desired courses are grouped together in credit groups and credit deducation policy is defined for each group. We also track the actions (view/email/sms) done in student search along with the number of credits deducted for that action.</h3>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666; text-align: center;">
    <a href='/public/ldb.png'>
        <img src='/public/ldb.png' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'LDBSearchTracking')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'LDBLeadContactedTracking')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'LDBLeadViewCount')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tGroupCreditDeductionPolicy')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tCourseGrouping')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?>