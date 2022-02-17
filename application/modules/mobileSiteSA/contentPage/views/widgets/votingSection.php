<?php if($content['data']['type'] == 'article')
    {
        $type = 'Article';
    }
    
    else
    {
        $type = 'Guide';
    }    
?>

<div class="vote-up-dwn">

    <p class="voteHead">Was this <?php echo $type;?> helpful?</p>
    <span>
        <a id="sendyes" href="javascript:void(0);" onclick="sendFeedbackForArticle('1',this);">
          <strong class="voteTxt">Yes</strong>
            <i id ='yes' class="sprite vote-up"></i>
     </a>
         <span id ='likes' style=" margin-left: 0px;"></span>
    </span>

    <span style=" margin-left: 10px;">
        <a id="sendno" href="javascript:void(0);" onclick="sendFeedbackForArticle('0',this);">
            <strong class="voteTxt">No</strong>
            <i id='no' class="sprite vote-dwn"></i></a>
        <span id='dislikes' style=" margin-left: 0px;"></span>
    </span>
  
</div>