<?php $this->load->view('shikshaSchema/header'); ?>

<h1>App User Schema</h1>

<div style='margin-bottom: 40px;'>
<div class='desc'>We have introduced 3 new tables related to Shiksha's Tag requirements</div>
            <ul>
                <li><b>tags</b> This tables contains all the tags generated/created on Shiksha. Contains their id, name, entity (i.e. Country/Stream/Career), description, main_id (in case of Synonym tag), Quality Score</li>
                <li><b>tags_parent</b> Parent Mapping for Tags</li>
                <li><b>tags_content_mapping</b> Tag Mapping with Content (Question/Discussion). We can have multiple tags mapped to a single content.</li>
            </ul>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/images/appDoc/tagSystem.png'>
        <img src='/public/images/appDoc/tagSystem.png' width='840' />
    </a>
</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

