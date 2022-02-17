<?php $this->load->view('shikshaSchema/header'); ?>

<h1>App User Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>We have introduced 1 new table related to tracking any content editing</div>
            <ul>
                <li><b>messageEditTracking</b> It contains the information about the item edited. In case of question/discussion, it will contain old title, description and tags. In case of Answer/Comment, it will only contain the old title</li>
            </ul>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/appDoc/editTracking.png'>
        <img src='/public/images/appDoc/editTracking.png' width='840' />
    </a>
</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

