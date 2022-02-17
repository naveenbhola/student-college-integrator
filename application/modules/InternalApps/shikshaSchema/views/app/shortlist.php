<?php $this->load->view('shikshaSchema/header'); ?>

<h1>App User Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>We have introduced 1 new table related to Shiksha Content Shortlist feature</div>
            <ul>
                <li><b>entityShortlist</b> It contains mapping between user (the one who is shortlisting) and the shortlisted entity (can be question/discussion). A user can shortlist multiple entities of different types. If he removes any entity from Shortlist, we mark the entry as deleted.</li>
            </ul>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/appDoc/shortlistEntity.png'>
        <img src='/public/images/appDoc/shortlistEntity.png' width='840' />
    </a>
</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

