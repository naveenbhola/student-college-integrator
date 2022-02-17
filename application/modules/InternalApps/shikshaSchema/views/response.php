<?php $this->load->view('shikshaSchema/header'); ?>

<div style='margin-bottom: 20px;'>
<h1>Response Tables</h1>
<h3>A response is created when a user applies to a course of an institute via various action buttons/links on site e.g. request e-brochure, view contact details etc. So a response is basically a user showing interest for a course of an institute. Responses are created only for paid courses and institutes can view their responses by logging to shiksha enterprise site.</h3>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666; text-align: center;">
    <a href='/public/response.png'>
        <img src='/public/response.png' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tempLMSTable')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tempLmsRequest')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'responseLocationTable')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'responseLocationPref')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'responseExportPref')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?>