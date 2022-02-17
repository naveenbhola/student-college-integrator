<?php $this->load->view('shikshaSchema/header'); ?>

<h1>App User Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>We have introduced 3 new tables related to Shiksha Users</div>
            <ul>
                <li><b>tuser_profileType</b> This tables depicts the profile type of the user registered through App. User can be Producer (i.e Expert) OR Consumer (Student). In case of expert, we will also ask his Organisation, College</li>
                <li><b>tuserDataPrivacySettings</b> This table will store user's preferences of his profile view settings. For each field, it can be Public/Private.</li>
                <li><b>facebookFriendsMapping</b> Shows the friends found on Facebook</li>
            </ul>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/appDoc/userTables.png'>
        <img src='/public/images/appDoc/userTables.png' width='840' />
    </a>
</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

