<?php $this->load->view('shikshaSchema/header'); ?>

<h1>App User Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>We have introduced 1 new table related to Shiksha Follow feature</div>
            <ul>
                <li><b>tuserFollowTable</b> It contains mapping between user (the one who is following) and the followed entity (can be question/discussion/tag/user). A user can follow multiple entities of different types. Once, he unfollows any entity, we mark it as unfollow.</li>
            </ul>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/appDoc/UserFollow.png'>
        <img src='/public/images/appDoc/UserFollow.png' width='840' />
    </a>
</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

