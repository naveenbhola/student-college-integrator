<?php $this->load->view('shikshaSchema/header'); ?>

<h1>User action mapping to Redis Structure</h1>


<div class='desc'><b>Thread View</b>
            <ol>
                <li>Add tagIds into userFollowsTag:user:&lt;userId&gt; sorted set for all tagIds to which this thread is mapped.</li>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt; set.</li>
                <li>Add value to view property of thread in threadQualityParameter:thread:&lt;threadId&gt;.</li>
                <li>Update thread quality score in threadQualityScore:thread Hash based on current action.</li>
            </ol>
</div>

<div class='desc'><b>Thread Anwer/Comment</b>
            <ol>
                <li>Consider User Level.</li>
                <li>Add userId into threadContributors:thread:&lt;threadId&gt; set.</li>
                <li>Add tagIds into userFollowsTag:user:&lt;userId&gt; sorted set for all tagIds to which this thread is mapped.</li>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt; set.</li>
                <li>Add value to Anwer/Comment property of thread in threadQualityParameter:thread.</li>
                <li>Update thread quality score in threadQualityScore:thread Hash based on current action.</li>
            </ol>
</div>

<div class='desc'><b>Thread Share</b>
            <ol>
                <li>Add tagIds into userFollowsTag:user:&lt;userId&gt; sorted set for all tagIds to which this thread is mapped.</li>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt; set.</li>
                <li>Update thread quality score in threadQualityScore:thread Hash based on current action.</li>
            </ol>
</div>

<div class='desc'><b>Thread Post</b>
            <ol>
                <li>Add userId into threadContributors:thread:&lt;threadId&gt;.</li>
                <li>Add tagIds into userFollowsTag:user:&lt;userId&gt; sorted set for all tagIds to which this thread is mapped.</li>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt; set.</li>
                <li>Add value to post property of thread in threadQualityParameter:thread.</li>
		<li>Update thread quality score in threadQualityScore:thread Hash based on current action.</li>
            </ol>
</div>

<div class='desc'><b>Thread Follow</b>
            <ol>
                <li>Add userId into threadFollowers:thread:&lt;threadId&gt;.</li>
                <li>Add tagIds into userFollowsTag:user:&lt;userId&gt; sorted set for all tagIds to which this thread is mapped.</li>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt; set.</li>
                <li>Add value to follow property of thread in threadQualityParameter:thread.</li>
		<li>Update thread quality score in threadQualityScore:thread Hash based on current action.</li>
            </ol>
</div>

<div class='desc'><b>Thread Unfollow</b>
            <ol>
                <li>Remove userId from threadFollowers:thread:&lt;threadId&gt;.</li>
                <li>Add userId into threadUnfollowers:thread:&lt;threadId&gt;.</li>
                <li>Add value to unfollow property of thread in threadQualityParameter:thread.</li>
                <li>Update thread quality score in threadQualityScore:thread Hash based on current action.</li>
            </ol>
</div>

<div class='desc'><b>User Follow &lt;followUserId&gt;</b>
            <ol>
                <li>Add userId into userFollowers:user:&lt;followUserId&gt; set.</li>
                <li>Add followUserId into userFollows:user:&lt;userId&gt; set.</li>
            </ol>
</div>

<div class='desc'><b>User Unfollow &lt;followUserId&gt;</b>
            <ol>
                <li>Remove userId from userFollowers:user:&lt;followUserId&gt; set.</li>
                <li>Remove followUserId from userFollow:user:&lt;userId&gt; set. (maybe)</li>
                <li>UnfollowList. (maybe)</li>
            </ol>
</div>

<div class='desc'><b>User Freind List &lt;userId&gt; At time of User Creation</b>
            <ol>
                <li>Add all freindUserIds into userFreind:user:&lt;userId&gt; set.</li>
            </ol>
</div>

<div class='desc'><b>Tag Follow &lt;tagId&gt;</b>
            <ol>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt;.</li>
                <li>Add tagId into tagUserFollows:user:&lt;userId&gt;.(maybe)</li>
            </ol>
</div>

<div class='desc'><b>Tag View &lt;tagId&gt;</b>
            <ol>
                <li>Add tagIds into userFollowsTag:user:&lt;userId&gt; sorted set for all tagIds to which this thread is mapped.</li>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt; set.</li>
            </ol>
</div>

<div class='desc'><b>Tag Unfollow &lt;tagId&gt;</b>
            <ol>
                <li>Remove userId from tagFollowers:tag:&lt;tagId&gt;.</li>
                <li>Remove tagId from tagUserFollows:user:&lt;userId&gt;.(maybe)</li>
                <li>Add userId in tagUnfollowers:tag:&lt;tagId&gt;</li>
            </ol>
</div>


<div class='desc'><b>Upvote/Downvote/ReportAbuse On Thread or its children(Answer/Comment/Reply)</b>
            <ol>
                <li>Add tagIds into userFollowsTag:user:&lt;userId&gt; sorted set for all tagIds to which this thread is mapped.</li>
                <li>Add userId into tagFollowers:tag:&lt;tagId&gt; set.</li>
                <li>Add value to Upvote/Downvote/ReportAbuse property of thread in threadQualityParameter:thread.</li>
                <li>Update thread quality score in threadQualityScore:thread Hash based on current action</li>
            </ol>
</div>


<?php $this->load->view('shikshaSchema/footer'); ?>

