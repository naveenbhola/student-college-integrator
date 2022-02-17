<?php if(isset($myDocuments) && is_array($myDocuments) && count($myDocuments) > 0): ?>
<h3>My Saved Documents</h3>
<?php foreach($myDocuments as $myDocument): ?>    
    <input type='checkbox' name='attachedDocuments[]' id='document<?=$myDocument['id']?>'
        value='<?=$myDocument['id']?>' <?php if(in_array($myDocument['id'],$myAttachedDocuments)) echo "checked = 'checked';"; ?>>
    <label for='document<?=$myDocument['id']?>'><?=$myDocument['document_title']?></label> &nbsp;&nbsp;
<?php endforeach; ?>
<?php endif; ?>
<input type="hidden" name="hasDocuments" value="1" />