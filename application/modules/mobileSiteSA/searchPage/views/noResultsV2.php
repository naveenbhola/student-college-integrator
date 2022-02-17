<div id="noSearchResultFound" style="padding: 117px 20px 100px;" class="no-shortlist-college">
<?php if(ENABLE_ABROAD_SEARCH){ ?>
<p><strong>No Result Found for "<?= htmlentities(base64_decode($keywordEncoded));?>"</strong></p>
<p class="no-shortlist-msg">Please Change Your Keyword and Search Again</p>
<?php } else { ?>
<p><strong>Something Went Wrong.</strong></p>
<p class="no-shortlist-msg">Please Try Again Later</p>
<?php } ?>
</div>