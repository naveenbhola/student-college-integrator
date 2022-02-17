<?php if(!empty($qna)){ ?>
<section class="content-wrap2 clearfix">
<h2 class="other-ques-title">Other Question about this Course</h2>
        <article class="clearfix">
        <div class="other-que-section">
            <ul>
            <?php
                foreach ($qna as $equestionId => $data){
                    $questionData = $data['data'];
                    $questionText = $questionData["title"];
                    $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
                    $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);
                ?>
                <li>
                    <div class="que-col"><span class="ques-icon">Q</span></div>
                    <div class="other-que-detail">
                        <strong><a href="<?php echo $questionData["q_url"];?>"><?php echo $questionText;?></a></strong>
                        <p class="posted-info clearfix">
                            <span><label>Posted by:</label> <?php echo $questionData["firstname"].' '.$questionData['lastname'];?></span>
                            <span><?php echo  makeRelativeTime($questionData["creationDate"]);?></span>
                        </p>
                    </div>
                </li>
                <?php
                }            
            ?>
            </ul>
            <div class="que-btn-sec" style="padding:10px"><a class="button blue small que-btn" href="<?php echo $courseObj->getURL();?>" style="margin:0;"><span>View all questions</span></a></div>
                </div>
   </article>
   
</section>
<?php } ?>