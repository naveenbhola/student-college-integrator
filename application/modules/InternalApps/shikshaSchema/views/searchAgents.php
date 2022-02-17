<?php $this->load->view('shikshaSchema/header'); ?>

<div style='margin-bottom: 20px;'>
<h1>Search Agents Tables</h1>
<h3>Search agents (also called Lead Genies) is the system for automatic lead delivery to clients. Clients can create lead genies for a criteria (e.g. desired course, preferred study location, educational background etc.). Lead genie system, running in the background will match the leads as soon as they are registered with genies created by clients and allocate the same based on the business logic defined. A lead can be only be shared among limited number of genies. The limit is defined for the credit group the lead's desired course belongs to. The credits are deducted whenever a lead is allocated to a genie, again based on the credit group of lead's desired course. Genies can also have auto-responders set, where the lead genie system will send pre-(and client-) defined emails/sms to leads.</h3>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/SA.png'>
        <img src='/public/SA.png' width='840' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SASearchAgent')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SASearchAgentAutoResponder_email')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SASearchAgentAutoResponder_sms')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SASearchAgent_contactDetails')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SASearchAgentDisplayData')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SA_GLOBALQUOTA_SMS_MAIL_SETTINGS')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SASearchAgentBooleanCriteria')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SARangedSearchCriteria')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SAMultiValuedSearchCriteria')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SAPreferedLocationSearchCriteria')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SALeadAllocation')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SALeadAllocationCron')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SALeadMatchingLog')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SALeadsLeftoverStatus')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'SAReuseCron')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'LDBLeadContactedTracking')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tCourseGrouping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tGroupCreditDeductionPolicy')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?>