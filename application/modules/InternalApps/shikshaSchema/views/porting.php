<?php $this->load->view('shikshaSchema/header'); ?>

<div style='margin-bottom: 20px;'>
<h1>Lead Porting Tables</h1>
<h3>The lead porting system was developed to port leads to client's systems. Clients provide an API and data format in which they accept the requests (GET, POST, JSON etc) and the criteria for which they want the leads (desired course, preferred location etc.). The matching leads are then ported to client's system using the API and data format give.</h3>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666; text-align: center;">
    <a href='/public/porting.png'>
        <img src='/public/porting.png' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'porting_main')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'porting_status')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'porting_subscription')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'porting_masterfield_list')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'porting_field_mappings')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'porting_conditions')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?> 