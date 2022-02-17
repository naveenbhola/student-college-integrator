<?php
	$protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script language="javascript" src="/public/js/imageUpload.js"></script>

	<link rel="stylesheet" type="text/css" href="/public/css/styles.css"/>
	<script src="/public/js/jquery-1.7.1.min.js"></script> 
	<script src="/public/js/facedetection/ccv.js"></script> 
	<script src="/public/js/facedetection/face.js"></script>
	<script src="/public/js/jquery.imgareaselect.dev"></script>
	<script src="/public/js/common.js"></script> 

    <link rel="stylesheet" type="text/css" href="/public/css/imgareaselect-default.css" />
	<script type="text/javascript" src="/public/js/jquery.imgareaselect.pack.js"></script>

	<style>
		.face {
			border:2px solid #FFF;
		}
	</style>
	
	
</head>
<body>
	<div id="fb-root"></div>
	<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
    var appId = '191415211010732';
    // Initialize the JS SDK
	window.fbAsyncInit = function() 
	{
		FB.init({
			appId: appId,
			cookie: true,
			frictionlessRequests: true
		});
		FB.getLoginStatus(function(response) {
			uid = response.authResponse.userID ? response.authResponse.userID : null;
		});
	}

   
	function brag(picURl){
	//	FB.ui({ method: 'feed',
	//	caption: 'Amit Singhal has coosen a new career.',
	//	//picture: 'http://localshiksha.com/Careers/CareerController/mergeImages/'+width+'/'+height+'/'+left+'/'+toper+'/'+url_base64_encode($("#myPicture").attr('src')),
	//	source: 'http://images.shiksha.com/mediadata/images/1338466725phpxGklg1.jpeg',
	//	name: 'Shiksha career Central',
	//	link: 'http://apps.facebook.com/myavator/',
	//	description: 'I choose a career as an Accountant. ',
	//	properties:{
	//		'link1' : {
	//			'text':'myshiksha',
	//			'href':'http://www.shiksha.com'
	//		}
	//	},
	//	actions:{
	//		'name': 'enter carrer central',
	//		'link': 'http://apps.facebook.com/myavator/'
	//	}
	//  }, function(){});
	
	
	FB.api(
		'me/myavator:choose',
		'post',
		{
		  career: "http://samples.ogp.me/191426554342931"
		},
		function(response) {
		 console.log(response);
		}
	  );
	
//
//	 FB.api('/'+uid+'/albums', 'get', {
//            name: 'Timeline Photos',
//			source:{
//				'link':'http://www.google.com/',
//				'text':'google'
//			}
//        }, function (response) {
//			
//          if (!response || response.error) {
//                alert('Error occured:' + response);
//            } else {
//               $.each(response.data,function(index,element){
//					if(element.type == "wall"){
//						
//	var imgURL = 'http://farm4.staticflickr.com/3332/3451193407_b7f047f4b4_o.jpg';//your external photo url
//        FB.api('/'+element.id+'/photos', 'post', {
//            message: 'photo description',
//            url: imgURL,
//			source:{
//				'link':'http://www.google.com/',
//				'text':'google'
//			}
//        }, function (response) {
//			console.log(response);
//            if (!response || response.error) {
//                alert('Error occured:' + response.error);
//            } else {
//                alert('Post ID: ' + response.id);
//            }
//
//        });
//					}
//			   })
//            }
//
//        });
	 
	
	}
	
	function invite(){
		FB.ui({method: 'apprequests',
			title: 'Play Friend Smash with me!',
			message: 'Friend Smash is smashing! Check it out.',
		  }, function(res){
			console.debug(res);
		  }
		  );
	}
</script>
<!-- Form starts -->
<form action="/Careers/CareerController/uploadImage" id="OnlineForm" accept-charset="utf-8" method="post" enctype="multipart/form-data" onsubmit="storeUserFunc(this); return false;" novalidate="novalidate">

	<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
	Upload an Image: <input type='file' name='fbImage' id='fbImage'></input>
	<input type='submit' value='Upload'></input>
</form>

<div id='content' style='margin-top:50px;display:none;position:relative;float:left;width:400px;' >
	<img id="myPicture" width="400" border=0 src="<?=$protocol?>://localshiksha.com/mediadata/<?=$file?>" ></img>
</div>

<div id='contentFull' style="visibility:hidden;position:absolute;top:0;left:0" >
	<img id="myPictureFull" border=0 src="<?=$protocol?>://localshiksha.com/mediadata/<?=$file?>" ></img>
</div>

<div id="contentPreview" style="position:relative;margin-top:50px;float:left;margin-left:20px;display:none;width: 200px; height: 300px;">
  <div id="preview" style="overflow: hidden; z-index: 1; position: absolute; top: 39px; height: 104px; left: 47px; width: 77px;">
	<img id="previewimg" style="width: 60px; height: 80px;" src="<?=$protocol?>://localshiksha.com/mediadata/<?=$file?>" />
  </div>
  <div id='avatar' style="position:absolute;z-index:10;top:0px;left:0px;">
	  <img src="/public/images/avatarFB.png" border=0>
  </div>
</div>

<br/>


<button onclick="mergeImages()">Download Avator</button>
<button onclick="brag()">Publish</button>
<button onclick="invite()">Invite</button>

<div style="color:#ff0000;font-size:14px" id="msg2">
</div>
<!-- Form ends -->

<script>
	flag = 0;
	coords = <?=json_encode($face)?>;
	var ias = undefined;
	alertmessage("");
	$("#myPicture").load(function() {
		document.getElementById('content').style.display = '';
		document.getElementById('msg2').style.display = '';
		if(ias == undefined){
			ias = $('#myPicture').imgAreaSelect({
				handles: true,
				aspectRatio: "77:104",
				fadeSpeed: 200,
				x1: 100, y1: 100, x2: 377, y2: 304,
				onSelectChange: preview,
				persistent:true,
				instance: true,
				parent:'#content'
			});
		}
		detect();
	});
	function storeUserFunc(formObj) {
	    AIM.submit(formObj, {'onStart' : startCallback, 'onComplete' : showUploadResponse});
	    formObj.submit();
	}
	$('#previewimg').hide();
	function showUploadResponse(response)
	{	//console.debug(response);
		response = $.parseJSON(response);
		if(isValidURL(response.url)){
			flag = 0;
			coords = response.face;
			$("#myPicture").attr('src', response.url);
			$("#previewimg").attr('src', response.url);
			$("#myPictureFull").attr('src', response.url);
			
		}else{
			alertmessage("Image not uploaded. Either its not a valid image file or its larger than 1 MB.");
		}

	}
	
	
function alertmessage(msg){
	$('#msg2').html(msg);
}



function isValidURL(url){
    var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    }
}

	
	function detect(){
		
		if(flag){
			//return false;
		}
		flag = 1;
		//var coords = $('#myPictureFull').faceDetection({
		//	error:function(img, code, message) {
		//		alertmessage('Error: '+message);
		//	}
		//});
		//
		console.debug(coords);
		if(coords.length == 0 ){
			alertmessage('We are unable to find any face in this image. Please select a face manually or upload another image.');
			
		}else{
			alertmessage("");
			var scaleX = $('#myPicture').width() / $('#myPictureFull').width();
			var scaleY = $('#myPicture').height() / $('#myPictureFull').height();
		
			
			var i = coords.length-1
			
			console.debug(coords[i].h);
			a1 = (coords[i].x);
			b1 = (coords[i].y);
			a2 = (coords[i].x + coords[i].w );
			
			b1 = b1 - coords[i].h*0.3;
			
			var ratio = coords[i].h/coords[i].w;
			ratio = ratio*104/77;
			var height = coords[i].h*ratio;
			
			b2 = (b1 + height );
			
			a1 *= scaleX;
			b1 *= scaleY;
			a2 *= scaleX;
			b2 *= scaleY;
			
			document.getElementById('contentPreview').style.display = '';
			
			ias.setSelection(a1, b1, a2, b2);
			ias.update();
			
			preview($('#myPicture'), { x1: a1,y1: b1,x2: a2,y2: b2,width: Math.round(a2-a1),height: Math.round(b2-b1)} );
		}
	
	}
	
	var left = 0;
	var top = 0;
	var width = 0;
	var height = 0;
	function preview(img, selection) {
		if (!selection.width || !selection.height)
			return;
		
		width = ($('#myPicture').width()/(selection.width)) * 77;
		height = ($('#myPicture').height()/(selection.height)) * 104;
		left = -(width/$('#myPicture').width()) * selection.x1; 
		toper = -(height/$('#myPicture').height()) * selection.y1;
		
		
		$('#previewimg').show();
		$('#previewimg').css({
			width:width,
			height: height,
			marginLeft:left,
			marginTop: toper
		});
		
		//ias.setSelection(50, 50, 150, 200, true);
		//ias.setOptions({ show: true });
		//ias.update();
				
		//$('#x1').val(selection.x1);
		//$('#y1').val(selection.y1);
		//$('#x2').val(selection.x2);
		//$('#y2').val(selection.y2);
		//$('#w').val(selection.width);
		//$('#h').val(selection.height);    
	}
	
	function mergeImages(){
		window.open('/Careers/CareerController/mergeImages/'+width+'/'+height+'/'+left+'/'+toper+'/'+url_base64_encode($("#myPicture").attr('src')));
	}
	
	
	function getOriginalWidthOfImg(img_element) {
		var t = new Image();
		t.src = (img_element.getAttribute ? img_element.getAttribute("src") : false) || img_element.src;
		return t.width;
	}
	
	function getOriginalHeightOfImg(img_element) {
		var t = new Image();
		t.src = (img_element.getAttribute ? img_element.getAttribute("src") : false) || img_element.src;
		return t.height;
	}
	
</script>
</body>
</html>