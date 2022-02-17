<?php
if($pollJSON['options'][0] && $pollJSON['options'][0]['value'] !== ""){
?>
       <div class="poll-box" data-enhance="true">
       		<strong><?=$pollJSON['question']?></strong>
            <ul>
			<?php foreach($pollJSON['options'] as $option):?>
            	<li>
					<label>
                    	<input type="radio" name="pollOption" id="poll-option-<?=html_escape($option['id'])?>" value="<?=html_escape($option['id'])?>" />
                        <p><?=html_escape($option['value'])?></p>
					</label>
                </li>
 			<?php endforeach;?>              
                <li style="margin:15px 0 0 0"><input type="button" value="Vote" class="button yellow" onclick="doVote();" /></li>
            </ul>
            
       </div>


       <div class="poll-result" id="poll-results" style="display:none">
       		<strong>Result</strong>
            <ul>
			<?php
				$i = -1;
				foreach($pollJSON['options'] as $key=>$option){
				$i++;
			?>  
            	<li>
                	<p><?=html_escape($option['value'])?></p>
                    <div class="poll-bar-<?=(($i%5)+1)?>" style="width:43%;" id="green-bar-<?=html_escape($option['id'])?>">&nbsp;</div>
                    <span class="comp-percent" id="result-val-<?=html_escape($option['id'])?>">0%</span>
                </li>
			<?php
				}
			?>                
            </ul>
       </div>       



<div class="clearFix spacer20"></div>

<script>
	var pollOption = 0;
	var pollJSON = <?=json_encode($pollJSON)?>;
	var d = new Date();
	var n = d.getTime();	
	//var ec = new everCookie();
	function loadPollWidget(){

		var pollOption = getCookie('poll<?=$pollJSON['id']?>');
			$('#poll-option-'+pollOption).attr('checked', 'checked');
			if(pollOption){
				$('#poll-results').show();
				setCookie('poll<?=$pollJSON['id']?>',pollOption,n + 2592000 ,'/',COOKIEDOMAIN);
			}
			computePollResult();
		
	}
	
	function computePollResult(){
		var total = 0;
		$.each(pollJSON['options'],function(index,option){
			option['response'] = parseInt(option['response']);
			if(option['response'] != 0){
				total += option['response'];
			}
		});
		if(total){
			$.each(pollJSON['options'],function(index,option){
				var width =  Math.round(((100*option['response'])/total) * 10)/10;
				$('#green-bar-'+option['id']).css('width',(width*0.70)+"%");
				$('#result-val-'+option['id']).html(width+"%");
			});
		}
	}
	
	function doVote(){
		var vote = $('input[name=pollOption]:checked').val();
		if(!vote){
			alert("Please select a value to vote");
		}else{
			if(pollOption){
				alert("You have already voted on this poll.");
			}else{
				pollOption = vote;
				var i = 0;
				$.each(pollJSON['options'],function(index,option){
					if(option['id'] == vote){
						i = index;
					}
				});
				pollJSON['options'][i]['response'] = parseInt(pollJSON['options'][i]['response'])+1;
				computePollResult();
				$.ajax({
					url:'/marticle5/ArticleMobile/votePoll/'+pollJSON['id']+'/'+pollOption
				});
				$('#poll-results').show();
				$('#poll-option-'+pollOption).attr('checked', 'checked');
				setCookie('poll<?=$pollJSON['id']?>',pollOption,n + 2592000 ,'/',COOKIEDOMAIN);
				$('#poll-option-'+pollOption).attr('checked', 'checked');
				
			}
			
		}
	}
	
</script>


<?php
}
?>