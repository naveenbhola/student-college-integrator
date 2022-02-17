<?php
if($pollJSON['options'][0] && $pollJSON['options'][0]['value'] !== ""){
?>
<div class="poll-widget">
	<div class="poll-options" style="<?=($pollJSON['image']?'':'width:100%')?>">
    	<h3><?=$pollJSON['question']?></h3>
        <ul>
			<?php
				foreach($pollJSON['options'] as $option){
			?>
        	<li><input type="radio" name="pollOption" id="poll-option-<?=html_escape($option['id'])?>" value="<?=html_escape($option['id'])?>" /> <?=html_escape($option['value'])?></li>
            <?php
				}
			?>
            <li><input type="button" value="Vote" class="orange-button" onclick="doVote();" /></li>
        </ul>
    </div>
	<?php
		if($pollJSON['image']){
	?>
    <div class="poll-img"><img src="<?=$pollJSON['image']?>" alt="" width="100%" /></div>
	<?php
		}
	?>
</div>

<div class="clearFix spacer20"></div>

<div class="box-shadow poll-results" id="poll-results" style="display:none">
	<h3>Results</h3>
    <ul>
		<?php
			$i = -1;
			foreach($pollJSON['options'] as $key=>$option){
				$i++;
			?>
        	<li>
        	<label><?=html_escape($option['value'])?></label>
				<div class="result">
					<span class="poll-bar-<?=(($i%5)+1)?>" style="width:0%" id="green-bar-<?=html_escape($option['id'])?>"></span>
					<p class="result-val" id="result-val-<?=html_escape($option['id'])?>">0%</p>
				</div>
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
	function loadPollWidget(){
		ec = new evercookie();
		
		ec.get("poll<?=$pollJSON['id']?>", function(value,val) {
			pollOption = value;
			$j('#poll-option-'+pollOption).attr('checked', 'checked');
			if(pollOption){
				$j('#poll-results').show();
				ec.set("poll<?=$pollJSON['id']?>", pollOption);
			}
			computePollResult();
			
		},1);
		
	}
	
	function computePollResult(){
		var total = 0;
		$j.each(pollJSON['options'],function(index,option){
			option['response'] = parseInt(option['response']);
			if(option['response'] != 0){
				total += option['response'];
			}
		});
		if(total){
			$j.each(pollJSON['options'],function(index,option){
				var width =  Math.round(((100*option['response'])/total) * 10)/10;
				$j('#green-bar-'+option['id']).css('width',(width*0.70)+"%");
				$j('#result-val-'+option['id']).html(width+"%");
			});
		}
	}
	
	function doVote(){
		var vote = $j('input[name=pollOption]:checked').val();
		if(!vote){
			alert("Please select a value to vote");
		}else{
			if(pollOption){
				alert("You have already voted on this poll.");
			}else{
				pollOption = vote;
				var i = 0;
				$j.each(pollJSON['options'],function(index,option){
					if(option['id'] == vote){
						i = index;
					}
				});
				pollJSON['options'][i]['response'] = parseInt(pollJSON['options'][i]['response'])+1;
				computePollResult();
				$j.ajax({
					url:'/blogs/shikshaBlog/votePoll/'+pollJSON['id']+'/'+pollOption
				});
				$j('#poll-results').show();
				$j('#poll-option-'+pollOption).attr('checked', 'checked');
				ec.set("poll<?=$pollJSON['id']?>", pollOption);
				$j('#poll-option-'+pollOption).attr('checked', 'checked');
				
			}
			
		}
	}
	
</script>


<?php
}
?>