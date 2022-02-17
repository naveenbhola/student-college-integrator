<script>
tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "remove,shikshaFile,shikshaImage,quote,embed,autolink,youtubeIframe,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,VCMS",
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,pasteword,|,link,unlink,|,bullist,numlist,|,embed,quote,|,shikshaImage,|,shikshaFile,|,remove,styleselect,formatselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,image,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,sub,sup,|,fullscreen,|,preview,|,code,|,VCMS",
        
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
		editor_selector : "mceEditor",
    	editor_deselector : "mceNoEditor",
    	setup : function(ed) {
    			ed.onKeyUp.add(function(ed, e) {
    				var fullText    = (tinyMCE.activeEditor.getContent()).replace(/(<([^>]+)>)/ig,"");
    				var currentNode = tinyMCE.activeEditor.selection.getNode().innerHTML.replace(/(<([^>]+)>)/ig,"");
    				var isSelectedText = tinyMCE.activeEditor.selection.getSel().isCollapsed;
    				if(isSelectedText && fullText.length>=1 && typeof tinyMCE.activeEditor.id != 'undefined' && tinyMCE.activeEditor.id && typeof _initAutoSuggestor != 'undefined'){
    					_initAutoSuggestor(currentNode, fullText, tinyMCE.activeEditor.id, e);	
    				}else if(isSelectedText){
    					_escape = [];
    				}
    			});
    	},
		content_css : '/public/css/<?php echo getCSSWithVersion("articles"); ?>',
		width:900,
		height:500,
        // Skin options
        skin : "o2k7",
        skin_variant : "silver",
		valid_elements : ""
					+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
					  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
					  +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
					+"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
					  +"|height|hspace|id|name|object|style|title|vspace|width],"
					+"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
					  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
					  +"|shape<circle?default?poly?rect|style|tabindex|title|target],"
					+"base[href|target],"
					+"basefont[color|face|id|size],"
					+"bdo[class|dir<ltr?rtl|id|lang|style|title],"
					+"big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"blockquote[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
					  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
					  +"|onmouseover|onmouseup|style|title],"
					+"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
					+"br[class|clear<all?left?none?right|id|style|title],"
					+"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
					  +"|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
					  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
					  +"|value],"
					+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
					  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
					  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
					  +"|valign<baseline?bottom?middle?top|width],"
					+"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
					  +"|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
					  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
					  +"|valign<baseline?bottom?middle?top|width],"
					+"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
					+"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
					+"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
					+"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
					  +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
					  +"|style|title|target],"
					+"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
					  +"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
					+"frameset[class|cols|id|onload|onunload|rows|style|title],"
					+"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"head[dir<ltr?rtl|lang|profile],"
					+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
					+"html[dir<ltr?rtl|lang|version],"
					+"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
					  +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
					  +"|title|width],"
					+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
					  +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|src|style|title|usemap|vspace|width],"
					+"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
					  +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
					  +"|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
					  +"|readonly<readonly|size|src|style|tabindex|title"
					  +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
					  +"|usemap|value],"
					+"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
					+"kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
					  +"|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
					  +"|onmouseover|onmouseup|style|title],"
					+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
					  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
					  +"|value],"
					+"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
					+"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
					+"noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"noscript[class|dir<ltr?rtl|id|lang|style|title],"
					+"object[align<bottom?left?middle?right?top|archive|border|class|classid"
					  +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
					  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
					  +"|vspace|width],"
					+"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|start|style|title|type],"
					+"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
					  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
					  +"|onmouseover|onmouseup|selected<selected|style|title|value],"
					+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|style|title],"
					+"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
					+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
					  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
					  +"|onmouseover|onmouseup|style|title|width],"
					+"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
					+"samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"script[charset|defer|language|src|type],"
					+"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
					  +"|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
					  +"|tabindex|title],"
					+"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title],"
					+"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"style[dir<ltr?rtl|lang|media|title|type],"
					+"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title],"
					+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
					  +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
					  +"|style|summary|title|width],"
					+"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
					  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
					  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
					  +"|valign<baseline?bottom?middle?top],"
					+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
					  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
					  +"|style|title|valign<baseline?bottom?middle?top|width],"
					+"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
					  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
					  +"|readonly<readonly|rows|style|tabindex|title],"
					+"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
					  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
					  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
					  +"|valign<baseline?bottom?middle?top],"
					+"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
					  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
					  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
					  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
					  +"|style|title|valign<baseline?bottom?middle?top|width],"
					+"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
					  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
					  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
					  +"|valign<baseline?bottom?middle?top],"
					+"title[dir<ltr?rtl|lang],"
					+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
					  +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title|valign<baseline?bottom?middle?top],"
					+"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
					+"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
					  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
					+"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
					  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
					  +"|onmouseup|style|title|type],"
					+"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
					  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
					  +"|title]"
});
</script>

	    <p style="margin-top:10px;">Showing Result For: <strong><?php echo htmlentities($listingName);?> ( <?php echo htmlentities($listingId);?> )</strong></p>
		<div id="admissionDiv">
			<div class="lineSpace_10">&nbsp;</div>
			<div class="rnk-plchdr">
						<label>PlaceHolder :</label>
						<div>
					        <label class="sub-lbl">UILP name placeholder:</label>
					        <span>&lt;instituteName&gt;</span> <span class='disl'>(cutoff)<span>
					    </div>
					   	<div>
					        <label class="sub-lbl">UILP Short Name with location placeholder:</label>
					        <span>&lt;instituteShortNameWithLoc&gt;</span><span class='disl'>(cutoff,placement,admission,acp,bip,sip)</span>
					   	</div>
					    <div>
					        <label class="sub-lbl">UILP short Name placeholder:</label>
					        <span>&lt;instituteShortName&gt;</span> <span class='disl'>(cutoff)</span>
					    </div>
					    <div>
				           <label class="sub-lbl">Current year placeholder:</label>
				           <span>&lt;year&gt;</span><span class='disl'>(cutoff,admission,acp,bip,sip)</span>
					    </div>
					   	<div>
					            <label class="sub-lbl">Base Course placeholder:</label>
					            <span>&lt;baseCourseName&gt;</span>  <span class='disl'>(bip,placement)</span>
					    </div>
					    <div>
					        <label class="sub-lbl">Stream placeholder:</label>
					        <span>&lt;streamName&gt;</span>  <span class='disl'>(sip)</span>
					    </div>
					    <div>
					        <label class="sub-lbl">Exam placeholder:</label>
					        <span>&lt;ExamName&gt;</span> <span class='disl'>(cutoff)</span>
					    </div>
					    <div>
					        <span>Please don’t add IULP placeholder while updating H1. IULP Name will be added by default.</span>
					    </div>

				   </div>

			<div>
				
				<ul class='cms-flex_ul'>
					<li>
						<label>Page H1 :</label>
						<div class="add-field-box">
							<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:45px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_heading_field" name="page_title_field"><?php if($admissionData[0]['page_h1'])echo $admissionData[0]['page_h1'];?></textarea>
						</div>
					</li>
					<li>
						<label>Page Title:</label>
						<div class="add-field-box">
							<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:45px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_title_field" name="page_title_field"><?php if($admissionData[0]['page_title'])echo $admissionData[0]['page_title'];?></textarea>
						</div>
					</li>
					<li>
						<label>Page Description :</label>
						<div class="add-field-box">
							<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:45px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_description_field" name="page_title_field"><?php if($admissionData[0]['page_description'])echo $admissionData[0]['page_description'];?></textarea>
						</div>
					</li>
				</ul>
			<div>
				

			
			<div id="UnivDescContainer">
				<p class="update-label">
			        <input type="checkbox" name="updatedate" id="update"> <label for="update">Update Posted Date</label>
			        <input type="hidden" name="defaultUpdateDate" id="defaultUpdatedDate" value="<?=$admissionData[0]['posted_on']?>">
			    </p>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="bld topicTextarea" style="width:600px;">
					Description :
					<div class="formatTextarea">
						<div class="lineSpace_12">&nbsp;</div>
						<textarea name="admissionDescTag" class="textboxBorder mceEditor"  id="admissionDescTag" caption="admission description" style="width:99%;height:500px"><?php echo htmlentities($admissionData[0]['description']); ?></textarea>
						<div class="lineSpace_12">&nbsp;</div>
					</div>
				</div>
			
			</div>
			<div class="errorPlace"><div id="admissionDescTag_error" class="errorMsg"></div></div>
		</div>
		
		<div align="left">
			<span>
				
	            <button class="btn-submit7 w117" type="Submit" onclick="addAdmissionActionStatus('live');">
					<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Publish</p></div>
				</button>
	               
	            <button class="btn-submit7 w117" type="Submit" onclick="addAdmissionActionStatus('draft');">
					<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Save As Draft</p></div>
				</button>
	                        
			</span>&nbsp;		
			<span>
			
				<button class="btn-submit7 w117" value="" type="button" onclick="addAdmissionActionStatus('deleted');">
					<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Delete Content</p></div>
				</button>
			</span>
		</div>
	    
	  