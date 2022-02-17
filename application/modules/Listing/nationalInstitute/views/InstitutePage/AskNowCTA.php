<?php $GA_Tap_On_ASK = 'ASK_QUESTION'; ?>
<div class="alter-div align-center">
	<textarea id="questionText"  placeholder="Type your Question here.." class="qstn__area"></textarea>
	<a class="button button--orange" id ="instituteAskButton" ga-attr="<?=$GA_Tap_On_ASK?>" onclick="askCTAInstitute()">Ask Question</a>
</div>