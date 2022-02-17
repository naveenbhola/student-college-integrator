<script language = "javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
<script>
	
	/*$j("#exampages_cms_cont" ).sortable({
		start : function(event, ui){
			$j(this).find('.tinymce-textarea').each(function(){
				tinymce.execCommand('mceRemoveEditor', false, $j(this).attr('id') );
			});
		},
		stop: function(event, ui ) {
			$j(this).find('.tinymce-textarea').each(function(){
				tinymce.execCommand('mceAddEditor', false, $j(this).attr('id') );
			});
		}
	});*/
	
	//$j("#exampages_cms_cont" ).disableSelection();
	$j("#check_po").change(function() {
		if(this.checked) {
			$j(".cms-accordion-div").css("display", "none");
			$j(".section-title").find("i").addClass("plus-icon").removeClass('minus-icon');
		} else {
			$j(".cms-accordion-div").css("display", "block");
			$j(".section-title").find("i").addClass("minus-icon").removeClass('plus-icon');
		}
	});
	$j("#check_po").attr('checked', false);
</script>