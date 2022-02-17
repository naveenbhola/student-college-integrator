<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<title>Tags</title>
</head>
<body>
<h2>Generate Tags</h2><br />
<form action="tags" method='post'  > 
	<span style='vertical-align:top'>Enter Sentence </span><textarea rows="2" cols="60" name='sentence' id='sentence'></textarea>
	<br />
	<input type="button" value='Print Tags' id='tags' > 
	<br /><br />
	<div id='tags_op'></div>
</form>
</body>
<Script>
/* function validate(){
	var val = document.getElementById("sentence").value;
	if(val.trim() == ""){
		alert("Blank sentence");
		return false;
	} 
	return true;
} */

$("#tags").click(function(){
	$val = $.trim($("#sentence").val());
	if($val == ""){
		alert("Blank Setence");
		return;
	}
	$("#tags_op").html("Loading...");

	$.ajax({
	    url: '/Tagging/TaggingController/showSuggestions',
	    headers: {
	        'Content-Type': 'application/x-www-form-urlencoded'
	    },
	    type: "POST", /* or type:"GET" or type:"PUT" */
	    data: {
	    	'sentence' : $val
	    },
	    success: function (result) {
	        $("#tags_op").html(result);
	    },
	    error: function () {
	        console.log("error");
	    }
	});
	

});


</Script>
</html>