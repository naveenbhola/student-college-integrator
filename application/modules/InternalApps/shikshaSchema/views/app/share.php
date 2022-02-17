<?php $this->load->view('shikshaSchema/header'); ?>

<h1>App User Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>We have introduced 1 new table related to Shiksha Share feature</div>
            <ul>
                <li><b>entityShareLog</b> It contains mapping between user (the one who is sharing) and the shared entity (can be question/discussion/answer/comment). A user can share multiple entities of different types. We also store the destination App/Site on which the user shared the entity</li>
            </ul>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/appDoc/shareEntity.png'>
        <img src='/public/images/appDoc/shareEntity.png' width='840' />
    </a>
</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

