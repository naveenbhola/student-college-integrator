<?php $this->load->view('shikshaSchema/header'); ?>

<h1>App User Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>We have modified 2 tables related to Shiksha User Point System</div>
            <ul>
                <li><b>useractionpointmapping</b> This tables contains different actions user can perform and the points assigned to him for each action. As per the new requirements, we have modified this table to add/delete some actions.</li>
                <li><b>userpointsystembymodule</b> This table stored the total points assigned to the user and his Level Id and Name (based on his points)</li>
            </ul>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/appDoc/pointSystem.png'>
        <img src='/public/images/appDoc/pointSystem.png' width='840' />
    </a>
</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

