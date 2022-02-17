<?php $this->load->view('shikshaSchema/header'); ?>

<h1>Tags CMS functionality</h1>


<div class='desc'><b>Addition of new tags</b>

            <ol>
                <li>Sub-val structure would not be there on the backend</li>
                <li>But system to give recommendation on different combinations of Topics to be created</li>
                <li>When a new topic is getting added then moderator must be shown the impact of this change
                        <ul style="margin-top:20px;">
                                <li>Number of questions that shall get affected (old questions that would get tagged with the new topic)</li>
                                <li>Ability to do sanity on the questions that shall get tagged</li>
                                <li>Option of either attaching the new topic with old questions or not</li>
                        </ul>
                </li>
            </ol>

</div>

<div class='desc'><b>Deletion of tags</b>

            <ol>
                <li>Show the impact of the change
                        <ul style="margin-top:20px;">
                                <li>Number of questions that shall get affected because of this</li>
                                <li>Number of users who have explicitly followed this topic</li>
                                <li>Number of questions that shall end up with zero tags because of this deletion</li>
                        </ul>	
		</li>
                <li>Show the child topics that shall get affected
                        <ul style="margin-top:20px;">
                                <li>Option to delete them or re-assign them</li>
                                <li>Impact on questions because of the child topic change</li>
                                <li>Number of people who are following that particular child topic</li>
                        </ul>
                </li>
		<li>All questions with zero topics associated with them shall come under moderation flow</li>
            </ol>

</div>


<div class='desc'><b>Editing of Tags</b>

            <ol>
                <li>User can only delete and add a new topic , no direct editing of a topic</li>
		<li>Editing of the attributes of a topic is allowed (e.g synonym, description etc) </li>
            </ol>

</div>


<div class='desc'><b>Adding a Synonym</b>

            <ol>
                <li>On adding a new synonym the subsequent questions that come on the system shall get tagged based on the new synonym. Older questions shall only get tagged during the weekly/monthy retagging of questions CRON</li>
                <li>System to check whether that synonym already exist and is attached to which topic</li>
                <li>Same when deleting a synonym</li>
            </ol>

</div>

<div class='desc'><b>Edit Parent of Tag</b>

            <ol>
                <li>The retagging of questions that had this topic would happen as part of the weekly CRON</li>
            </ol>

</div>

<div class='desc'><b>On Any Topic</b>

            <ol>
                <li>View & edit the parent topic</li>
                <li>View the first level child topics</li>
                <li>View & edit the synonyms</li>
		<li>View & edit the description</li>
		<li>View the Topic entity </li>
            </ol>

</div>

<div class='desc'><b>Search option on Tag CMS</b>

            <ol>
                <li>View topics by entity (view all course tags/career tags)</li>
                <li>View all topics under a parent (e.g all topics with Pilot as the parent)</li>
                <li>View the count of questions and discussions associated with each tags . Option to sort by count</li>
            </ol>

</div>


<?php $this->load->view('shikshaSchema/footer'); ?>

