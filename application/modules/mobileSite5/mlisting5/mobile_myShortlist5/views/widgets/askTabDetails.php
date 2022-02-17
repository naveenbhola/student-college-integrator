<?php
        foreach ($qna as $index => $data){

            $questionData = $data["data"];
            if(!empty($questionData["title"])){

                    $questionText = $questionData["title"];
                    $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
                    $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);

                    $answers = $data["answers"];
                    $count_answers = count($answers);
    ?>
                        <li><a href="/mobile_myShortlist5/MyShortlistMobile/showQuestionDetailPage/<?php echo $courseId;?>/<?php echo $questionData['msgId']?>" style="color:#000;" ><?php echo $questionText; ?> <?php echo $count_answers?"(".$count_answers." ans)":"";?></a></li>
    <?php
            }
        }
?>