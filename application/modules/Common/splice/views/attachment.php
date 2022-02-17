<!-- attachment -->
<div class="form-group">
	<div class= "col-md-12 col-sm-12 col-xs-12">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" >Attachment</label>
		<input type="file"  caption="application process" id="<?php echo $id;?>" name="<?php echo $id;?>" class="col-md-9 col-sm-9 col-xs-12" validationtype = 'attachment'>
	</div>
	<div class= "col-md-12 col-sm-12 col-xs-12">
	<label class="control-label col-md-3 col-sm-3 col-xs-12"  style="text-align : left"></label>
<label class="control-label col-md-9 col-sm-9 col-xs-12"  style="text-align : left">Only pdf, ppt, pptx, doc, docx, xls, xlsx, txt, image,zip files are Allowed</label>
</div>
<label class="control-label col-md-3 col-sm-3 col-xs-12"  style="text-align : left"></label>
<div class="control-label col-md-9 col-sm-9 col-xs-12 errorMsg" id="<?php echo $id;?>_error"   style="display: none;text-align:left"></div>
</div>