<div class="clear-width">
	<h3 class="section-title">SEO Details</h3>
	<div class="cms-form-wrap">
		<ul>
			<li class="applyContent examContent" style="display: none">
			<label class="applyContent" style="display: none">Apply Content SEO URL : </label>
			<label class="examContent" style="display: none">Exam Content SEO URL : </label>
			<div class="cms-fields">
				<input class="universal-txt-field cms-text-field" name="SEOurl" id="SEOurl" required="true" type="text" value="<?=$contentURL?>" validationType = "applyContentUrl" caption = "valid SEO url" maxlength="300" minlength="3"/>
				<div style="display: none; margin-bottom: 5px" class="errorMsg" id="SEOurl_error"></div>
			</div>
			</li>
			<li class="examContent" style="display: none">
			<label class="examContent" style="display: none">Re-direction URL : </label>
			<div class="cms-fields">
				<input <?php if($action=='edit'){ echo 'disabled="disabled"'; }?> class="universal-txt-field cms-text-field" name="redirectSEOUrl" id="redirectSEOUrl" type="text" value="<?php echo $content['examContentDetails']['oldContentURL'];?>" validationType = "url" caption = "exam content redirection url" maxlength="500" minlength="3" onblur="validateRedirection();"/>
				<input name="redirectContentId" id="redirectContentId" type="hidden" />
				<input name="redirectSectionId" id="redirectSectionId" type="hidden" />
				<input name="redirectContentType" id="redirectContentType" type="hidden" />
				<div style="display: none;" class="errorMsg" id="redirectSEOUrl_error"></div>
			</div>
			</li>
			<li>
			<label class="article">Article SEO Title : </label>
			<label class="guide" style="display: none">Guide SEO Title : </label>
			<label class="examPage" style="display: none">Exam Page SEO Title : </label>
			<label class="applyContent" style="display: none">Apply Content SEO Title : </label>
			<label class="examContent" style="display: none">Exam Content SEO Title : </label>
			<div class="cms-fields">
				<input class="universal-txt-field cms-text-field" name="SEOtitle" type="text" value="<?=$content['basic_info']['seo_title']?>" maxlength="240"/>
			</div>
			</li>
			<li>
			<label class="article">Article SEO Keywords : </label>
			<label class="guide" style="display: none">Guide SEO Keywords : </label>
			<label class="examPage" style="display: none">Exam Page SEO Keywords : </label>
			<label class="applyContent" style="display: none">Apply Content SEO Keywords : </label>
			<label class="examContent" style="display: none">Exam Content SEO Keywords : </label>
			<div class="cms-fields">
				<textarea class="cms-textarea" name="SEOkeywords" maxlength="240"><?=$content['basic_info']['seo_keywords']?></textarea>
			</div>
			</li>
			<li>
			<label class="article">Article SEO Description : </label>
			<label class="guide" style="display: none">Guide SEO Description : </label>
			<label class="examPage" style="display: none">Exam Page SEO Description : </label>
			<label class="applyContent" style="display: none">Apply Content SEO Description : </label>
			<label class="examContent" style="display: none">Exam Content SEO Description : </label>
			<div class="cms-fields">
				<textarea class="cms-textarea" name="SEOdescription" maxlength="290"><?=$content['basic_info']['seo_description']?></textarea>
			</div>
			</li>
			
			<li>
			<label class="article">Map to Old Shiksha Article : </label>
			<label class="guide" style="display: none">Map to Old Shiksha Article :</label>
			<label class="examPage" style="display: none">Map to Old Shiksha Article :</label>
			<div class="cms-fields commonTags">
				<input class="universal-txt-field cms-text-field" id="seoMappingContentId" caption="Article Mapping Id" name="seoMappingContentId" type="text" onblur="showErrorMessage(this);" onchange="showErrorMessage(this);" validationType="numeric" maxlength= "5" required="" value="<?=$content['basic_info']['seoMappingContentId']?>"/>
				<div style="display: none; margin-bottom: 5px" class="errorMsg" id="seoMappingContentId_error"></div>
			</div>
			</li>
			
			<li>
			<div class="cms-fields">
				<label style="text-align: left;">
					<input style="float: left;" type="checkbox" name="dontUpdatePublishdate" id="dontUpdatePublishdate" value="1" class="">
						<div style="float: left; padding-top: 2px;"> Dont update the published date </div>
				</label>
			</div>
			</li>
		</ul>
	</div>
</div>