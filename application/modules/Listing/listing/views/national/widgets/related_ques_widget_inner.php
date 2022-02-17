<?php if(!empty($data)):?>
	<?php foreach ($data as $index => $questionData):?> 
	<?php $count++;?>
	<?php if(!empty($questionData["msgTxt"])):?>
	                <li id="questionDiv_<?php echo $questionData['msgId'];?>">
	                	
					<div class="clear-width">
		                    <span class="ques-icon">Q</span>
		                    <div class="ques-details">
		                        
		                        <?php  
		                        $questionText = $questionData["msgTxt"];
		                        $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
		                        $questionText = formatQNAforQuestionDetailPageWithoutLink($questionText,140);
		                        ?>
					<p><a class="black-link" target="_blank" href="<?php echo getSeoUrl($questionData["msgId"],'question',$questionText,'','',$questionData["creationDate"]);?>" ><?php echo $questionText;?></a></p>
		                        <p class="ques-head">
		                            <strong class="fllt" ><span>Posted by:</span> <a target="_blank" href="/getUserProfile/<?php echo $questionData['displayname'];?>"><?php echo $questionData["firstname"].' '.$questionData['lastname'];?></a></strong>
		                            <span id="div_<?php echo $questionData['msgId'];?>_postedBy" class="flRt" style=" margin-right: 10px ;"><?php echo  makeRelativeTime($questionData["creationDate"]);?></span>
		                        </p>
					
		                    </div>
				    </div>	               
	                </li>
	 <?php endif;?>                    
	<?php endforeach;?>
<?php endif;?>

