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
                convert_urls : false ,
                relative_urls : false,
                remove_script_host : false,
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



var noOfwikkicontents = 0;

function removewikkicontent(no)
{
	var id = "main_container_"+no;
	tinyMCE.execCommand( 'mceRemoveControl', false, 'wikkicontent_detail_'+no );
	deleteElement($(id));
	noOfwikkicontents--;
    setImageContainer();
}

function addwikkicontent() {
	noOfwikkicontents++;
	no = noOfwikkicontents;
	var newdiv = document.createElement('div');
    var str = '\
        <div>\
            <b>Page Name:</b> <input type="text" name="blogDescTag[]" id="blogDescTag_'+ noOfwikkicontents +'" validate="validateStr" minLength="3" maxLength="25" caption="page name"/>\
        </div>\
	    <div class="errorPlace">\
		    <div id="blogDescTag_'+ noOfwikkicontents +'_error" class="errorMsg"></div>\
			<div class="lineSpace_12">&nbsp;</div>\
    	</div>\
        <div class="bld topicTextarea" style="width:725px;">\
            Page Content: <div class="formatTextarea">\
                <div class="lineSpace_12">&nbsp;</div>\
                    <textarea name="blogDesc[]" class="textboxBorder mceEditor"  id="blogDesc_'+ noOfwikkicontents +'" rows="45" caption="blog description" style="width:99%;height:500px" ></textarea>\
                    <div class="lineSpace_12">&nbsp;</div>\
                </div>\
        </div>\
    ';

	newdiv.innerHTML = str;
	newdiv.setAttribute('id','main_container_'+no);
	$('blogDescriptionContainer').appendChild(newdiv);
    tinyMCE.execCommand('mceAddControl', false, 'blogDesc_'+no);
    setImageContainer();
}
function saveWikiContent() {
	/*var errmsg = hierarchyMappingForm.customHierarchyValidations('', 'articleCMS', 'formPost');
	if(errmsg != ''){
		hierarchyMappingForm.showErrorMessage(errmsg);
		return false;
	}
	var form = $j('#blog_creation');
	if(validateCreateBlog(form) && setImageContainer()); {
		
	}*/
	var wikiContent = [];
	$j("textarea[name*=blogDesc]").each(function(index) {
		wikiContent.push({'description': tinyMCE.get(this.id).getContent(), 'descriptionId': $j('#blogDescId_'+index).val()});
	})
		$j.ajax( {
	      type: "POST",
	      url: '/blogs/shikshaBlog/saveArticleWikiContent',
	      data: {wiki: wikiContent, blogId: $j('#blogId').val()},
	      success: function( response ) {
	        alert('content saved successfully');
	      }
	    } );
}
</script>
<?php
	if(!(isset($blogInfo) && is_array($blogInfo))) {
		$blogInfo[0]= array();	
	}
	$blogId = isset($blogInfo[0]['blogId']) ? $blogInfo[0]['blogId'] : '';
	$blogTitle = isset($blogInfo[0]['blogTitle']) ? $blogInfo[0]['blogTitle'] : '';
	$mailerTitle = isset($blogInfo[0]['mailerTitle']) ? $blogInfo[0]['mailerTitle'] : '';
	$mailerSnippet = isset($blogInfo[0]['mailerSnippet']) ? $blogInfo[0]['mailerSnippet'] : '';
	$blogDesc	 = isset($blogInfo[0]['blogText']) ? json_decode($blogInfo[0]['blogText'], true) : array('','');
	$blogType	 = isset($blogInfo[0]['blogType']) ? $blogInfo[0]['blogType'] : '';

	$boardName = isset($boardMapping['boardName']) ? $boardMapping['boardName'] : '';
	$className = isset($boardMapping['class']) ? $boardMapping['class'] : '';

	$seoTitle   = isset($blogInfo[0]['seoTitle']) ? $blogInfo[0]['seoTitle'] : '';
	$seoKeywords = isset($blogInfo[0]['seoKeywords']) ? $blogInfo[0]['seoKeywords'] : '';
	$seoDescription = isset($blogInfo[0]['seoDescription']) ? $blogInfo[0]['seoDescription'] : '';
	$seoUrl = isset($blogInfo[0]['url']) ? $blogInfo[0]['url'] : '';	//Added by Ankur for adding URL in Blogs
	$tags = isset($blogInfo[0]['tags']) ? $blogInfo[0]['tags'] : '';
	$summary	 = isset($blogInfo[0]['summary']) ? $blogInfo[0]['summary'] : '';
	$acronym	 = isset($blogInfo[0]['acronym']) ? $blogInfo[0]['acronym'] : '';
	$parentId    = isset($blogInfo[0]['parentId']) ? $blogInfo[0]['parentId'] : '';
	
	$chapterNumber = isset($blogInfo[0]['chapterNumber']) ? $blogInfo[0]['chapterNumber'] : '';
	$chapterName = isset($blogInfo[0]['chapterName']) ? $blogInfo[0]['chapterName'] : '';
	$bookName	 = isset($blogInfo[0]['bookName']) ? $blogInfo[0]['bookName'] : '';
	$selectedCategory	 = isset($blogInfo[0]['boardId']) ? $blogInfo[0]['boardId'] : '';
	$selectedCountry	 = isset($blogInfo[0]['countryId']) ? $blogInfo[0]['countryId'] : '2';
	$blogImageUrl = isset($blogInfo[0]['blogImageURL']) ? $blogInfo[0]['blogImageURL'] : '';
	$blogTypeValue = isset($blogInfo[0]['blogTypeValues']) ? $blogInfo[0]['blogTypeValues'] : '';
	$homepageImgURL = isset($blogInfo[0]['homepageImgURL']) ? $blogInfo[0]['homepageImgURL'] : '';

	$status = isset($blogInfo[0]['status']) ? $blogInfo[0]['status'] : '';
        if(isset($blogInfo[0]['relatedDate']) && $blogInfo[0]['relatedDate']!='' && $blogInfo[0]['relatedDate']!='0000-00-00 00:00:00'){
		$arrayDate = explode(" ",$blogInfo[0]['relatedDate']);
		$relatedDate = $arrayDate[0];
	}
	else{
		$relatedDate = 'YYYY-MM-DD';
	}
	$caption	 = $blogId == '' ? 'Add' : 'Update';
	$buttonCaption	 = $blogId == '' ? 'Publish' : (($status == 'draft') ? 'Update Draft' : 'Update');
	$cmsFlag = 0;
	if(isset($fromCMS) && $fromCMS != 0) {
		$cmsFlag = 1;
	}
        $isSetNoIndexCheck = (isset($blogInfo[0]['noIndex']) && $blogInfo[0]['noIndex']==true ) ? 'checked' : '';
?>
	<div style="position:absolute;display:none;width:100%;" id="blogImagesContainer">
		<div>
			<div id="blogImages"></div>
			<form action="/common/uploadImage/blog/0" method="post" enctype="multipart/form-data" id="blogImageForm">
				Add an image:&nbsp;<input type="file" name="shikshaImage[]" id="shikshaImage" onchange="this.form.submit();"/>
			</form>
		</div>
	</div>
		<div class="lineSpace_10">&nbsp;</div>

	<div style="position:absolute;display:none;" id="blogHomePageImagesContainer">
		<div>
			<div id="blogHomePageImages"><?php if($homepageImgURL != ''){?><div style="border-bottom:solid 0px #acacac;" id="imageContainer"><a target="_blank" href="<?php echo MEDIA_SERVER.$homepageImgURL; ?>"><img width="60" border="0" align="absmiddle" id="blogThumbnail_401122" src="<?php echo MEDIA_SERVER.$homepageImgURL; ?>"></a><label class="fontSize_12p bld"><?php echo MEDIA_SERVER.$homepageImgURL; ?></label></div><?php } ?></div>
			<form action="/common/uploadImage/blog/0" method="post" enctype="multipart/form-data" id="homePageImageForm">
				Add an image for HomePage:&nbsp;<input type="file" name="shikshaHomePageImage[]" id="shikshaHomePageImage" onchange="this.form.submit();"/>
			</form>
	        <div class="formField errorPlace"><div id="homePageImage_error" class="errorMsg"></div></div>
		</div>
	</div>	
	<form onSubmit="var errmsg = hierarchyMappingForm.customHierarchyValidations('', 'articleCMS', 'formPost');if(errmsg != ''){hierarchyMappingForm.showErrorMessage(errmsg);return false;}return (validateCreateBlog(this) && setImageContainer());" id="blog_creation" name="blog_creation" method="post" action="/blogs/shikshaBlog/createBlog/<?php echo $cmsFlag; ?>" novalidate="novalidate">   
	<input type="hidden" name="mediatype" id="mediatype" value="image" />
        <input type="hidden" name="addActionType" id="addActionType"/>
	<input type="hidden" name="blogId" id="blogId" value="<?php echo $blogId; ?>"/>
	<input type="hidden" name="blogImageUrl" id="blogThumbnail" value="<?php echo $blogImageUrl; ?>"/>

	<input type="hidden" name="blogImageForHomePage" id="blogImageForHomePage" value="<?php echo $homepageImgURL; ?>"/>

    <div style="float:left; width:750px;">
	<div class="formField">
		<div class="bld mb5">Article Title <span style="color:#FF0000;">*</span></div>
		<input type="text" name="blogtitle" id="blogtitle"  value="<?php echo $blogTitle; ?>" validate="validateStr" maxlength="120" minlength="10" required="1" class="textboxBorder" style="width:62%;" caption="Blog title" />
	</div>	
	        <div class="formField errorPlace"><div id="blogtitle_error" class="errorMsg"></div></div>

        <div class="formField">
		<div class="bld mb5">Mailer Title <span style="color:#FF0000;"></span></div>
		<input type="text" name="mailertitle" id="mailertitle"  value="<?php echo $mailerTitle; ?>" maxlength="100" minlength="1" class="textboxBorder" validate="validateStr" style="width:62%;" caption="Mailer title" />
	</div>
            <div class="formField errorPlace"><div id="mailertitle_error" class="errorMsg"></div></div>
        
         <div class="formField">
		<div class="bld mb5">Mailer Snippet <span style="color:#FF0000;"></span></div>
		<div  class="formInput inline"><textarea id="mailerSnippet" name="mailerSnippet" class="mceNoEditor" minLength="0" maxLength="300" caption="Mailer Snippet" validate="validateStr"><?php echo $mailerSnippet; ?></textarea></div>

	</div>
            <div class="formField errorPlace"><div id="mailerSnippet_error" class="errorMsg"></div></div>
        
	<div id="testTitle" class="errorMsg"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="formField">
		<div class="bld mb5">Article Type:</div>
		<select name="blogType" id="blogType" caption="Blog Type" class="textboxBorder" onchange="toggleFields();">
            <option value="kumkum">Kum Kum Articles</option>
            <!--option value="exam">Exam Articles</option-->
            <option value="user">Shiksha Articles</option>
            <option value="faq">Frequently Asked Questions</option>
            <option value="news">News</option>
            <option value="testPrep">Test Prep</option>
            <option value="boards">Boards</option>
            <option value="coursesAfter12th">Courses After 12th</option>
        </select>
	</div>

	<div class="lineSpace_10">&nbsp;</div>
	<div id="boardsList" style="display: none;"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="boardClassList" style="display: none;"></div>
	<div id="boardError" class="errorMsg" style="display: none;">This article has been already mapped to this article type and board/class.</div>

	<div class="formField errorPlace"><div id="blogType_error" class="errorMsg"></div></div>
	<div class="lineSpace_10">&nbsp;</div>	
    <div id='blogTypeVal' name='blogTypeVal' style='display:none'>		
		<div class="formField">
			<div class="bld mb5">Exam Type:</div>
			<select name="blogTypeValue" id="blogTypeValue" caption="Exam Type" class="textboxBorder" >
				<option value="UG">Under Graduate</option>
				<option value="PG">Post Graduate</option>
				<option value="EnglishTest">English Tests</option>
				<option value="Doctoral">Doctorate</option>
			</select>
		</div>	
		<div class="formField errorPlace"><div id="blogType_error" class="errorMsg"></div></div>
		<div class="lineSpace_10">&nbsp;</div>	
    </div>
	<div id="KumKumFields" style="display:none">
		<div class="formField">
			<div class="bld mb5">Chapter Number:</div>
			<input type="text" name="chapterNumber" id="chapterNumber"  value="<?php echo $chapterNumber; ?>" validate="validateInteger" maxlength="3" minlength="1" class="textboxBorder" size="3" caption="Chapter Number"/>
		</div>	
		<div class="formField errorPlace"><div id="chapterNumber_error" class="errorMsg"></div></div>
		<div class="lineSpace_10">&nbsp;</div>	
		<div class="formField">
			<div class="bld mb5">Chapter Name:</div>
			<input type="text" name="chapterName" id="chapterName"  value="<?php echo $chapterName; ?>" validate="validateStr" maxlength="200" minlength="5" class="textboxBorder" style="width:62%;" caption="Chapter Name"/>
		</div>	
		<div class="formField errorPlace"><div id="chapterName_error" class="errorMsg"></div></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="formField">
			<div class="bld mb5">Book Name:</div>
			<select name="bookName" id="bookName" class="textboxBorder" caption="Book Name">
				<option value="">--Select Book--</option>
				<option value="After 10+2 And Beyond - Science &  Technology">After 10+2 And Beyond - Science &  Technology</option>
				<option value="After 10+2 And Beyond - Humanities &  Commerce">After 10+2 And Beyond - Humanities &  Commerce</option>
				<option value="Study Abroad">Study Abroad</option>
				<?php
					global $countries;
					foreach($countries as $key => $country) {
						if($key == 'india') {continue;}
						$bookNameForCountry = 'Study in '. $country['name'];
				?>
				<option value="<?php echo $bookNameForCountry; ?>"><?php echo $bookNameForCountry; ?></option>
				<?php
					}
				?>
			</select>
		</div>	
		<div class="formField errorPlace"><div id="bookName_error" class="errorMsg"></div></div>
		<div class="lineSpace_10">&nbsp;</div>
	</div>	
	<!-- Start: Added by Ankur on 1 March for adding URL to blogs -->
	<div class="formField">
		<div class="bld mb5">Article SEO URL:</div>		
		<div class="formInput inline"><textarea <?php if($blogId!=''){echo "disabled='true'";}?> name="seoUrl" id="seoUrl" class="mceNoEditor" minLength="0" maxLength="500" caption="seo url"><?php echo $seoUrl; ?></textarea></div>
	</div>

	<!-- Adding url redirection check box for articles in cms--> 
	<div class="lineSpace_10">&nbsp;</div>
	<input type='checkbox' id='apply_301' name='apply_301'></input>Apply URL Redirection
	<div class="lineSpace_10">&nbsp;</div>


	<div class="errorPlace"><div id="seoUrl_error" class="errorMsg"></div></div>
	<div class="lineSpace_10">&nbsp;</div>
	<!-- End: Added by Ankur on 1 March for adding URL to blogs -->
	<div class="formField">
		<div class="bld mb5">Article SEO Title:</div>		
		<div class="formInput inline"><input name="seoTitle" id="seoTitle" class="mceNoEditor" validate="validateStr" minLength="10" maxLength="100" caption="seo Title" value="<?php echo $seoTitle; ?>"/></div>
	</div>
	<div class="errorPlace"><div id="seoTitle_error" class="errorMsg"></div></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="formField">
		<div class="bld mb5">Article SEO Keywords:</div>		
		<div class="formInput inline"><textarea name="seoKeywords" id="seoKeywords" class="mceNoEditor" validate="validateStr" minLength="10" maxLength="250" caption="seo keywords"><?php echo $seoKeywords; ?></textarea></div>
	</div>
	<div class="errorPlace"><div id="seoKeywords_error" class="errorMsg"></div></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="formField">
		<div class="bld mb5">Article SEO Description:</div>	
		<div class="formInput inline"><textarea name="seoDescription" id="seoDescription" class="mceNoEditor" validate="validateStr" minLength="10" maxLength="250" caption="seo description"><?php echo $seoDescription; ?></textarea></div>
	</div>
	<div class="errorPlace"><div id="seoDescription_error" class="errorMsg"></div></div>	
	<div class="lineSpace_10">&nbsp;</div>
	<div class="formField">
		<div class="bld mb5">Article Summary <span style="color:#FF0000;">*</span>:</div>	
		<div class="formInput inline"><textarea name="summary" id="summary" class="mceNoEditor" required="true" validate="validateStr" minLength="10" maxLength="500" caption="summary"><?php echo $summary; ?></textarea></div>
	</div>
	<div class="errorPlace"><div id="summary_error" class="errorMsg"></div></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="formField">
		<div class="bld mb5">Article Tags:</div>		
		<div class="formInput inline">
			<input name="tags" id="tags" caption="article tags" value="<?php echo $tags; ?>" disabled/>
		</div>
	</div>
	<div class="errorPlace"><div id="tags_error" class="errorMsg"></div></div>	
	<div class="formField">
		<div class="lineSpace_12">&nbsp;</div>
		<div class="bld mb5">Article layout:</div>
		<div class="formInput inline" >
            <select name="articleLayout" id="articleLayout" onchange="changeArticleType();">
        		<option value="general">General</option>
				<option value="qna">Q & A</option>
			</select>
        </div>
	</div>
	<div class="errorPlace"><div id="articleLayout_error" class="errorMsg"></div></div>	
	<div class="lineSpace_12">&nbsp;</div>
	<div id="qnaArticle">
		<?php $this->load->view('blogs/createBlog_Form_qna'); ?>
	</div>
	<div id="slideshowArticle">
		<?php $this->load->view('blogs/createBlog_Form_slideshow'); ?>
	</div>
	<div id="generalArticle">
		<div class="lineSpace_10">&nbsp;</div>
		<div class="bld mb5">Article Description <span style="color:#FF0000;">*</span>:</div>
		<div id="blogDescriptionContainer">
			<?php
				$blogDescriptionCount = 0;
				foreach($blogDesc as $key => $blogDescription) {
					$pageContent = $blogDescription['description'];
					$pageTag = $blogDescription['descriptionTag'];
			?>
			<div>
				<input type="text" name="blogDescIds[]" id="blogDescId_<?=$key?>" value="<?=$blogDescription['descriptionId']?>"/>
				<b>Page Name :</b> <input type="text" name="blogDescTag[]" id="blogDescTag_<?php echo $blogDescriptionCount; ?>" value="<?php echo $pageTag; ?>" minLength="3" maxLength="25" validate="validateStr" caption="page name"/>
			</div>
			<div class="errorPlace"><div id="blogDescTag_<?php echo $blogDescriptionCount; ?>_error" class="errorMsg"></div></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="bld topicTextarea" style="width:600px;">
				Page Content :
				<div class="formatTextarea">
					<div class="lineSpace_12">&nbsp;</div>
					<textarea name="blogDesc[]" class="textboxBorder mceEditor"  id="blogDesc_<?php echo $blogDescriptionCount++; ?>" rows="45" caption="blog description" style="width:99%;height:500px" ><?php echo $pageContent; ?></textarea>
					<div class="lineSpace_12">&nbsp;</div>
				</div>
			</div>
			<?php
			}
			?>
			<script>noOfwikkicontents = <?php echo $blogDescriptionCount-1; ?>;</script>
		</div>
		<div ><a href="javascript:void(0);" onclick="addwikkicontent()">Add More Pages</a>
			<?php if($blogId != '') { ?>
				<a href="javascript:void(0);" class="buttoncms--orange" onclick="saveWikiContent()">Save</a>
			<?php } ?>
		</div>
		<div class="errorPlace"><div id="blogDesc_error" class="errorMsg"></div></div>
	</div>
	  
	<div class="lineSpace_10">&nbsp;</div>
	<div class="lineSpace_12">&nbsp;</div>

	<div class="bld mb5">Article Relevancy <span style="color:#FF0000;">*</span>
: <select name="articleRelevancy" id="artRel" validate="validateSelect" required="true" caption="Article Relevancy">
		<option value="">Select Relevancy</option>
		<option value="3">3 Days</option>
		<option value="7">1 Week</option>
		<option value="30">1 Month</option>
		<option value="180">6 Months</option>
		<option value="365">1 Year</option>
		<option value="-1">No limit</option>
	</select></div>
	<div class="errorPlace"><div class="errorMsg" id="artRel_error" style="left:130px;position:relative;"></div></div>
	<div class="sec-mpng">
		<strong class="hdng">Article Mapping</strong>
		<div class="bld mb5">Stream Hierarchy :</div>
		<?php echo Modules::run('common/commonHierarchyForm/getHierarchyMappingForm','articleCMS',$prefilledData);?>
	</div>

    <!-- LDB Course Setting starts -->
    <input type="hidden" name="ldbCourseList" id="ldbCourseList" value="<?php echo isset($blogInfo[0]['ldbCourses']) ? json_decode($blogInfo[0]['ldbCourses'], true) : ''; ?>">
    <div class="lineSpace_10">&nbsp;</div>
    <!-- LDB Course Setting ends -->

	<div id="examFields" style="display:none">
		<div class="formField">
			<div class="bld mb5">Choose the Parent Article:</div>		
			<div class="formInput inline">
				<select id="parentId" name="parentId">
					<option value="0">--None--</option>
					<?php 
						foreach($examParents as $examParent) {
							$examParentId = $examParent['blogId'];
							$examParentName = $examParent['blogTitle'];
							$childExams = $examParent['exam'];
					?>
					<option value="<?php echo $examParentId; ?>"><?php echo $examParentName; ?></option>
					<?php
							foreach($childExams as $childExam){
								$childExamId = $childExam['blogId'];
								$childExamTitle = $childExam['blogTitle'];
					?>
						<option value="<?php echo $childExamId; ?>">&nbsp;&nbsp;&nbsp;<?php echo $childExamTitle; ?></option>
					<?php
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="errorPlace"><div id="parentId_error" class="errorMsg"></div></div>
		<!-- category select end -->	
		<div class="lineSpace_10">&nbsp;</div>
		<div class="formField">
			<div class="bld mb5">Acronym:</div>		
			<div class="formInput inline" ><input type="text" name="acronym" id="acronym" value="<?php echo $acronym; ?>" /></div>
		</div>
		<div class="errorPlace"><div id="acronym_error" class="errorMsg"></div></div>
		<!-- category select end -->	
		<div class="lineSpace_10">&nbsp;</div>
	</div>

	<div class="row">
		<div style="display: none; float:left; width:100%">
	        <div class="bld float_L">Article Page Images: <span class="grayFont" style="font-size:12px;font-weight:normal;">(Acceptable formats - JPEG,GIF,PNG)</span></div>
	        <div class="lineSpace_10">&nbsp;</div>
            <div id="fakeImageContainer">&nbsp;</div>
	            <div id="fakeHPImageContainer">&nbsp;</div>

		</div>
		<div class="lineSpace_5">&nbsp;</div>
	</div>
	
	<!--<div class="lineSpace_10">&nbsp;</div>
	<div><span class="Fnt11">Type the characters you see in picture below &nbsp;<span style="color:#FF0000;">*</span></div>
	<div align="center" class="divborder" style="width:200px;">
	     <img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>" id="blogCaptcha"/>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div align="left"><input type="text" name="secCode" id="secCode" validate="validateStr" maxlength="5" minlength="5" required="1" caption="security code" value="" style="width:200px;"/></div>
	<div class="formField errorPlace"><div id="secCode_error" class="errorMsg"></div></div>-->
	<!--<div style="position:absolute;display:none;" id="blogHomePageImagesContainer">
		<div>
			<div id="blogHomePageImages"></div>
				Add an image for HomePage:&nbsp;<input type="file" name="shikshaHomePageImage[]" id="shikshaHomePageImage" />
		</div>
	</div>-->
	<input type='checkbox' id='showOnHome'  onchange ="showHomePageImgBox(this)" name='showOnHome'></input>Show on homepage slider
	<div class="lineSpace_10">&nbsp;</div>	
	<input type='checkbox' id='noIndexCheck' name='noIndexCheck' <?php echo $isSetNoIndexCheck; ?> ></input>Do not allow Search engines to index this article
	<div class="lineSpace_10">&nbsp;</div>
	<input type='checkbox' id='updateDate' name='updateDate'></input>Update date
	<div class="lineSpace_10">&nbsp;</div>
	<div class="bld mb5">Poll: <span id="pollTitleExternal"><?=html_escape($pollJSON['title'])?></span>&nbsp;<a href="#" onclick="showPollOverlay();return false;">Add/Edit Poll</a></div>
	<div class="lineSpace_10">&nbsp;</div>
	<textarea id="pollJSON" name="pollJSON" style="display:none"><?php if($pollJSON['title'] !=''){echo url_base64_encode(json_encode($pollJSON));}?></textarea>        
                    <!--Start_Date-->
                    <script>var checkUpdateFlag='';</script>
                    <?php if(isset($LastUpdateSet)){?>
                     <script>var checkUpdateFlag=true;</script>
                    <?php }?>

                    <div class="normaltxt_11p_blk_arial bld float_L"><input type="checkbox" id="checkboxLastUpdated" name="key_page_52" onClick='toggleUpdateLatestUpdate(this);' /> LATEST UPDATE</div>
		   <div id="updateLatestDiv" style="display:none;margin-left:20px;" class="normaltxt_11p_blk_arial bld float_L"><input type="checkbox" id="updateLatestUpdate" name="updateLatestUpdate" /> Do not show on top of Latest Update</div>
		   <input type='hidden' id='latestUpdateWasOn' name='latestUpdateWasOn' value='0'/>
		   <div class="clear_B">&nbsp;</div>
                   <div class="lineSpace_20">&nbsp;</div>
                    <!--End_Date-->
        
        
        
        
	<div align="left">
		<span>
			<button class="btn-submit7 w3 _bbtnClk" type="Submit" onclick="addActionStatus('<?php if($status == 'draft'){ echo 'draft';}else{echo 'live';}?>');">
				<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog"><?php echo $buttonCaption; ?></p></div>
			</button>
                        <?php if($status == 'draft'){?>
                        <button class="btn-submit7 w3 _bbtnClk" type="Submit" onclick="addActionStatus('live');">
				<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Publish</p></div>
			</button>
                        <?php }else if($status == ''){ // for new article only?>
                        <button class="btn-submit7 w3 _bbtnClk" type="Submit" onclick="addActionStatus('draft');">
				<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Save As Draft</p></div>
			</button>
                        <?php }?>
		</span>&nbsp;		
		<span>
		<?php 
            $cancel = $cmsFlag == 1 ? 'window.close();' : 'window.location=\'/enterprise/Enterprise/index/1\';';
		?>
			<button class="btn-submit5 w3" value="" type="button" onclick="<?php echo $cancel; ?>">
				<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
			</button>
		</span>
	</div>
    
    </div>
  
    <div class="clearFix"></div>
    
</form>



<?php $this->load->view('blogs/createBlog_Form_Poll'); ?>
<?php $this->load->view('blogs/createBlog_Form_UploadImageSlideShow'); ?>
<script>
    var thumbNailElementSelected = null;
    var boardName = '<?php echo $boardName;?>';
    var className = '<?php echo $className;?>';
    var blogType  = '<?php echo $blogType; ?>';
    var bstatus   = '<?php echo $status;?>';
  
    if(checkUpdateFlag==true){
        $("checkboxLastUpdated").checked = true;
		$('latestUpdateWasOn').value = '1';
		toggleUpdateLatestUpdate($("checkboxLastUpdated"));
    }
    
    function addActionStatus(val) {
        document.getElementById('addActionType').value = val;
    }
    
    function toggleUpdateLatestUpdate(obj){
        if(obj.checked==true)
            $('updateLatestDiv').style.display = '';
        else
            $('updateLatestDiv').style.display = 'none';
    }

    function setImageContainer() {
        var fakeImageContainer = document.getElementById('fakeImageContainer');
        var mainImageContainer = document.getElementById('blogImagesContainer');
        var xPos = obtainPostitionX(fakeImageContainer);
        var yPos = obtainPostitionY(fakeImageContainer);
        mainImageContainer.style.left = obtainPostitionX($('showOnHome')) +'px';
        mainImageContainer.style.top = yPos + 20 +'px';
        //mainImageContainer.style.display = '';
        fakeImageContainer.style.height = mainImageContainer.offsetHeight + 30 + 'px';
        return true;
    }

    function checkURL(myUrl){
    	var temp = myUrl.split('com/');
        var str = '';
        if(temp.length > 1){
                for (var i = 1; i < temp.length; i++) {
                        str += '/'+temp[i];
                }
        }else{
                str = temp;
        }
        return str;
    }
    function showUploadImageResponseForBlog(response) {
        try{
            var mediaDetails = eval('eval('+response+')');
        } catch(e) {
            alert(response);
            return false;
        }
		
        var mediaId = mediaDetails.mediaid;
        var mediaUrl = mediaDetails.imageurl;
        mediaUrl = checkURL(mediaUrl);
        var mediaThumbUrl = mediaDetails.thumburl;
      	mediaThumbUrl = checkURL(mediaThumbUrl);
        var pickThumbnailText = 'Pick it as thumbnail of the article';
        var mediaPickFlag = false;
        if(document.getElementById('blogThumbnail').value.replace(':80','') == mediaThumbUrl.replace(':80','')) {
            pickThumbnailText = 'Remove it as thumbnail for article';
            mediaPickFlag = true;
        }
        imagePlaceHolderId ="imageContainer_" + mediaId;
        imagePlaceHolderInnerHTML = '<div id="'+ imagePlaceHolderId +'" style="height:55px;border-bottom:solid 1px #acacac;"><a href="'+ '<?=MEDIA_SERVER?>'+mediaUrl +'" target="_blank">\
                                        <img src="'+ '<?=MEDIA_SERVER?>'+mediaThumbUrl +'" border="0" align="absmiddle" id="blogThumbnail_'+ mediaId +'"/>\
                                    </a>&nbsp;\
                                    <label class="fontSize_12p bld">'+ insertWbr('<?=MEDIA_SERVER?>'+mediaUrl,30) +'\
                                    &nbsp;<a title="Delete Image"><img src="/public/images/img_Delete.gif" border="0" alt="Delete Image" align="absmiddle" onclick="deleteBlogImage('+ mediaId +');" style="cursor:pointer;"/></a>\
                                    &nbsp;<a href="javascript:void(0)" id="mediaPick_'+ mediaId +'" onclick="return toggleThumbnail(this,'+ mediaId +')">'+ pickThumbnailText +'</a>\
                                    </div><div class="lineSpace_10">&nbsp;</div>';
        document.getElementById('blogImages').innerHTML += imagePlaceHolderInnerHTML;
//    Mine----            document.getElementById('blogHomePageImages').innerHTML += imagePlaceHolderInnerHTML;

        document.getElementById('fakeImageContainer').style.height = (document.getElementById('blogImagesContainer').offsetHeight + 30) + 'px';
        var blogMedia = document.createElement('input');
        blogMedia.type = 'hidden';
        blogMedia.name = 'blogImage[]';
        blogMedia.id = 'blogImage_'+ mediaId;
        blogMedia.value = mediaId +'#'+ '<?=MEDIA_SERVER?>'+mediaUrl;
        document.getElementById('blog_creation').appendChild(blogMedia);
        document.getElementById('blogImageForm').reset();
        if(mediaPickFlag) {
            thumbNailElementSelected = document.getElementById('mediaPick_'+ mediaId);
        }
    }

    function toggleThumbnail(thumbnailElement, mediaId) {
        if(thumbnailElement.innerHTML == 'Pick it as thumbnail of the article') {
            var toggleFlag = confirm('Picking this image as thumbnail for the article will change any other previous images picked for thumbnail to this image! Are you sure to continue?');
            if(toggleFlag) {
                thumbnailElement.innerHTML = 'Remove it as thumbnail of the article';
                document.getElementById('blogThumbnail').value = document.getElementById('blogThumbnail_'+ mediaId).src;// thumbnail image shown in the image section
                if(thumbNailElementSelected != null) {
                    document.getElementById(thumbNailElementSelected.id).innerHTML = 'Pick it as thumbnail of the article';
                }
                thumbNailElementSelected = thumbnailElement;
            }
        } else {
            var toggleFlag = confirm('This will remove this image as thumbnail for the article! Are you sure to continue?');
            if(toggleFlag) {
                thumbnailElement.innerHTML = 'Pick it as thumbnail of the article';
                document.getElementById('blogThumbnail').value = '';
                thumbNailElementSelected = null;
            }
        }
    }

    function deleteBlogImage(mediaId) {
        var flag = confirm('You are going to delete an image of this article page. Press Ok to proceed!');
        if(flag) {
            document.getElementById('imageContainer_'+mediaId).parentNode.removeChild(document.getElementById('imageContainer_'+mediaId));
            document.getElementById('blogImage_'+ mediaId).parentNode.removeChild(document.getElementById('blogImage_'+ mediaId));
            document.getElementById('fakeImageContainer').style.height = (document.getElementById('blogImagesContainer').offsetHeight + 30) + 'px';
            alert("The image is successfully deleted.\n Please remove the image from all the places where it is getting used in the article.");
            document.getElementById('blogThumbnail').value = '';
            setImageContainer();
        }
    }

    function deleteBlogHPImage(mediaId) {
        var flag = confirm('You are going to delete the home page image of this article. Press Ok to proceed!');
        if(flag) {
            document.getElementById('homePageImageContainer_'+mediaId).parentNode.removeChild(document.getElementById('homePageImageContainer_'+mediaId));
            document.getElementById('blogImage_'+ mediaId).parentNode.removeChild(document.getElementById('blogImage_'+ mediaId));
            document.getElementById('fakeImageContainer').style.height = (document.getElementById('blogHomePageImagesContainer').offsetHeight + 30) + 'px';
            alert("The image is successfully deleted.");
            setImageContainer();
        }
    }
	if('<?php echo $selectedCountry; ?>' && '<?php echo $selectedCountry; ?>' > 2){
	  completeCategoryTree = categoryTreeAbroad;
	}
    
    selectComboBox(document.getElementById('bookName'), '<?php echo $bookName; ?>');
    selectComboBox(document.getElementById('blogType'), '<?php echo $blogType; ?>');
    selectComboBox(document.getElementById('parentId'), '<?php echo $parentId; ?>');
    selectComboBox(document.getElementById('blogTypeValue'), '<?php echo $blogTypeValue; ?>');
    

    <?php 
    if($homepageImgURL != '')
    {
    ?>
    $("showOnHome").checked = true;
    showHomePageImgBox($('showOnHome'));
    //$('showOnHome').style.marginTop = '30px';
    <?php 
    }
    ?>

    //Code end for Article LDB Course addition
    function showHomePageImgBox(val){
	 	$('homePageImage_error').innerHTML = '';
    	$('homePageImage_error').parentNode.style.display='none';
    	/*
    	if(val.checked == true){
    		$('blogImagesContainer').appendChild($('blogHomePageImagesContainer'));
    		$('blogHomePageImagesContainer').style.display = 'block';
    		$('blogHomePageImagesContainer').style.marginTop = '61px';
    		$('blogHomePageImagesContainer').style.marginLeft = '0px';
    		val.style.marginBottom = '65px';

    	}
    	else {
    		$('blogHomePageImagesContainer').style.display = 'none';
    		val.style.marginBottom = '0px';
    	}
    	*/
    }

    function showUploadImageResponseForHomePageBlog(response) { 
        try{
            var mediaDetails = eval('eval('+response+')');
        } catch(e) {
        	alert(response);
            return false;
        }

        var mediaId = mediaDetails.mediaid;
        var mediaUrl = mediaDetails.imageurl;
        var mediaThumbUrl = mediaDetails.thumburl;
        var pickThumbnailText = 'Pick it as thumbnail of the article';
        var mediaPickFlag = false;
        if(document.getElementById('blogThumbnail').value.replace(':80','') == mediaThumbUrl.replace(':80','')) {
        //    pickThumbnailText = 'Remove it as thumbnail for article';
            mediaPickFlag = true;
        }
        homePageImagePlaceHolderId ="homePageImageContainer_" + mediaId;
        homePageImagePlaceHolderInnerHTML = '<div id="'+ homePageImagePlaceHolderId +'" style="border-bottom:solid 0px #acacac;"><a href="'+ '<?php echo MEDIA_SERVER;?>'+mediaUrl +'" target="_blank">\
                                        <img src="'+ '<?php echo MEDIA_SERVER;?>'+mediaThumbUrl +'" border="0" align="absmiddle" id="blogThumbnail_'+ mediaId +'"/>\
                                    </a>&nbsp;\
                                    <label class="fontSize_12p bld">'+ insertWbr('<?php echo MEDIA_SERVER;?>'+mediaUrl,30) +'\
                                    &nbsp;&nbsp;</div><div class="lineSpace_10">&nbsp;</div>';
       document.getElementById('blogHomePageImages').innerHTML = homePageImagePlaceHolderInnerHTML;

        document.getElementById('fakeHPImageContainer').style.height = (document.getElementById('blogHomePageImagesContainer').offsetHeight - 30) + 'px';
        var blogMediaForHomePage = document.createElement('input');
        blogMediaForHomePage.type = 'hidden';
        blogMediaForHomePage.name = 'blogImageForHomePage';
        blogMediaForHomePage.id = 'blogImageForHomePage_'+ mediaId;
        blogMediaForHomePage.value = mediaUrl;
        document.getElementById('blog_creation').appendChild(blogMediaForHomePage);
        document.getElementById('homePageImageForm').reset();
        if(mediaPickFlag) {
            thumbNailElementSelected = document.getElementById('mediaPick_'+ mediaId);
        }
    }

    function toggleFields(){
        var blogType = document.getElementById('blogType').value;
        		$j('.boardClassList, #boardsList, #boardError').hide();
        switch(blogType){
            case 'kumkum':
                document.getElementById('KumKumFields').style.display = 'none';//'block';
                document.getElementById('examFields').style.display = 'none';
                document.getElementById('blogTypeVal').style.display = 'none';
                break;
            case 'exam':
                document.getElementById('KumKumFields').style.display = 'none';
                document.getElementById('blogTypeVal').style.display = 'none';
                document.getElementById('examFields').style.display = 'block';
                break;
            case 'examstudyabroad':
                document.getElementById('KumKumFields').style.display = 'none';
                document.getElementById('examFields').style.display = 'block';
                document.getElementById('blogTypeVal').style.display = 'block';
                break;
            case 'faq':
                document.getElementById('KumKumFields').style.display = 'none';
                document.getElementById('examFields').style.display = 'block';
                document.getElementById('blogTypeVal').style.display = 'none';
                break;
            default:
                document.getElementById('KumKumFields').style.display = 'none';
                document.getElementById('examFields').style.display = 'none';
                document.getElementById('blogTypeVal').style.display = 'none';
                if(blogType == 'boards' || blogType == 'coursesAfter12th'){
            		prepareBoardsData(blogType);
                }
        }
        setImageContainer();
    }
    window.onload = function () {
    <?php 
        foreach($blogImages as $blogImage) {
            $imageUrl = explode('.',$blogImage['imageUrl']);
            $thumbNailUrl = '_s.'. end($imageUrl);
            unset($imageUrl[count($imageUrl) -1]);
            $thumbNailUrl = implode('.',$imageUrl) . $thumbNailUrl;
            $imageDetails = array(
                                'mediaid' => $blogImage['mediaId'],
                                'imageurl' => $blogImage['imageUrl'],
                                'thumburl' => $thumbNailUrl 
                                );
            echo 'showUploadImageResponseForBlog(\''. json_encode($imageDetails)  .'\');';
        }
    ?>
    AIM.submit(document.getElementById('blogImageForm'), {'onStart' : startCallback, 'onComplete' : showUploadImageResponseForBlog});
    AIM.submit(document.getElementById('homePageImageForm'), {'onStart' : startCallback, 'onComplete' : showUploadImageResponseForHomePageBlog});
    toggleFields();

    if(typeof(className) !='undefined' && blogType == 'boards' && boardName !=''){
        prepareBoardClass(className);
    }

	$('articleLayout').value = '<?=($blogInfo[0]['blogLayout']?$blogInfo[0]['blogLayout']:"general")?>';
	changeArticleType();
	$('artRel').value = ('<?php echo $blogInfo[0]['blogRelevancy']; ?>') ? '<?php echo $blogInfo[0]['blogRelevancy']; ?>' : '-1';
    }
	setTimeout(function(){
	AIM3.submit(document.getElementById('slideImageForm'), {'onStart' : startCallback, 'onComplete' : showUploadImageResponseForBlogSlide});	
	
	AIM2.submit(document.getElementById('pollImageForm'), {'onStart' : startCallback, 'onComplete' : showUploadImageResponseForBlogPoll});
	
	},3000);
	setInterval(function(){
		setImageContainer();
	},2000);
</script>
<style type="text/css">
a.buttoncms--orange:hover{background-color:#db6d1d;color:#fff!important;text-decoration: none;}
a.buttoncms--orange{background-color:#f37921;border-color:#f37921;color:#fff;padding:0 25px;font-size:.875rem;height:32px;cursor:pointer;line-height:32px; float: right;}
}
</style>