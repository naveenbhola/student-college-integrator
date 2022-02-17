<div id="addImageSlideShow" style="display:none;margin:20px;width:100%;text-align:center">
	<ul>
		<li>
			<div class="flLt" style="width:300px; padding:0 15px 12px 0">
				<form action="/common/uploadImageDialog/blog/0" method="post" enctype="multipart/form-data" id="slideImageForm">
				<input class="universal-txt-field" id="shikshaImageDialog" type="file" onchange="this.form.submit();" name="shikshaImageDialog[]">
				<div>Recommended image size :- Width: 600-700px Height: 400px</div>
				</form>
			</div>
			<div class="clearFix"></div>
		</li>
	</ul>
</div>

<script>
var currentSlide = -1;
function showSlideOverlay(id){
	 currentSlide = id;
	 var content = $('addImageSlideShow').innerHTML;
     overlayParentAnA = $('addImageSlideShow');
     overlayParentAnA.innerHTML = '';
     showOverlayAnA(367,400,'Upload Image',content);
}

function showUploadImageResponseForBlogSlide(response){
	try{
		var mediaDetails = eval('eval('+response+')');
	}catch(e){
		$j('#blogslideshowDescImage_'+currentSlide).val("");
		$('blogslideshowDescImageSrc_'+currentSlide).src="/public/images/blankImg.gif";
		alert(response);
		return false;
	}
	if(mediaDetails['imageurl'] != undefined){
		$j('#blogslideshowDescImage_'+currentSlide).val(mediaDetails['imageurl']);
		document.getElementById('blogslideshowDescImageSrc_'+currentSlide).src=mediaDetails['imageurl'];
		hideOverlayAnA();
		setImageContainer();
	}else{
		$j('#blogslideshowDescImage_'+currentSlide).val("");
		$('blogslideshowDescImageSrc_'+currentSlide).src="/public/images/blankImg.gif";
		alert(mediaDetails);
	}
	
}

AIM3={
	frame:function(c){
		var n="f";
		var d=document.createElement("DIV");
		d.innerHTML="<iframe style=\"display:none\" src=\"about:blank\" id=\""+n+"\" name=\""+n+"\" onload=\"AIM3.loaded('"+n+"')\"></iframe>";
		document.body.appendChild(d);
		var i=document.getElementById(n);
		if(c&&typeof (c.onComplete)=="function"){
			i.onComplete=c.onComplete;
		}
	return n;
	},
	form:function(f,_6){
		f.setAttribute("target",_6);
	},
	submit:function(f,c){
		AIM3.form(f,AIM3.frame(c));
		if(c&&typeof (c.onStart)=="function"){
			return c.onStart();
		}else{
			
			return true;
		}
	},
	loaded:function(id){
		var i=document.getElementById(id);
		if(i.contentDocument){
			var d=i.contentDocument;
			i.onComplete = showUploadImageResponseForBlogSlide;
			
		}else{
			
			if(i.contentWindow){
				
				var d=i.contentWindow.document;
			}else{
				
				var d=window.frames[id].document;
			}		
		}
		if(d.location.href=="about:blank"){
			return;
		}
		if(typeof (i.onComplete)=="function"){
			i.onComplete(d.body.innerHTML);
		}
	}
};


</script>