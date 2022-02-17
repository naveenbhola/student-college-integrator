<?php
if(!empty($resultArr)) {
?>
<!-- Start_Related Institutes -->
<div class="related-discus-pannel">
    <h4>Related Discussions</h4>
    <ul>
    <?php //if(empty($resultArr)) echo "No Related Discussions";?>
    <?php
for($i = 0; $i < count($resultArr) && $i < 4; $i++) {
$thisResult = (array)$resultArr[$i];
$discussionTxt = $thisResult['msgTxt'];
$discussionURL = $thisResult['url'];
$comment = $thisResult['comment'];
if($comment ==1) $commentText = ' Comment';else if($comment>1) $commentText = ' Comments';else $commentText ='';
?>

<li>
    <a href="<?php echo $discussionURL; ?>" ><?php echo $discussionTxt; ?></a>
    <p><?php echo $comment.$commentText ; ?></p>
</li>

<?php
}
?>
</ul>
</div>
<!-- End_Related Institutes -->
<?php
}
?>

