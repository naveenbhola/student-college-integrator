<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Shiksha Auto-Answer</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
	.list-group-item { background-color: inherit; border:0px; padding:2px; list-style:disc;    display: list-item;}
	.list-group{margin-left:10px;}
</style>
</head>
<body style="padding: 10px;background-color:#f7f7f7;">
<div class="container-fluid">
<div class="row">


<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
    FB.init({
      appId            : '548946048907287',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v2.11'
    });
  };
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution=setup_tool
  page_id="2758917381000429"
  theme_color="#007075"
  logged_in_greeting="Shiksha Answers for you ..."
  logged_out_greeting="Shiksha Answers for you ...">
</div>


	<div class="col-xs-12 col-md-12">
	<h2>Auto-Answer</h2>
	<p>Click on chat plugin below to check the answers for sample questions.</p>
	</div>
<!--	<form class="form-inline1">
	<div class="form-group ">
		<input placeholder="Type your query here ..." type="text" id="query" name="query" required="required" size=100 class="form-control" />
	</div>
	
	<form class="form-inline" action="return false;" onsubmit="return false;">
		<input type="button" value="Get Answer" id="search" class="btn btn-default" />
	</div>
	<br/><br/>
	</form>
-->
<div class="col-xs-12 col-md-12 clearfix">
	<div class="">
		<h4><b>Sample Questions : </b></h4>


<div class="col-md-12 alert-info">
        <h4>1. Ranking Questions : </h4>
        <ul class="list-group row">
			<li class="list-group-item col-xs-12">What is the rank of iit kanpur ? </li> 
			<li class="list-group-item col-xs-12">What are the top colleges at Bangalore ? </li>
			<li class="list-group-item col-xs-12">What are the top colleges accepting CAT ? </li>
			<li class="list-group-item col-xs-12">What are the top bba colleges at Tamil Nadu ? </li>
			<li class="list-group-item col-xs-12">What are the top design colleges at Delhi ? </li>
			<li class="list-group-item col-xs-12">What are the top Civil Engineering colleges ? </li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>2. Fees Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">What is the fees of iit kanpur ?</li>
                <li class="list-group-item col-xs-12">What is the fees of btech in iit kanpur ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>3. Placements Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">How are the placements at iim a ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>4. Scholarship Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Scholarships at IIM Bangalore ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>5. Facilities Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Does iit kanpur has hostel and mess facility ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>6. Dates Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Exam dates for admission at iit kanpur ?</li>
                <li class="list-group-item col-xs-12">What is the last date for admission in btech at iit kanpur ?</li>
                <li class="list-group-item col-xs-12">What is the last date for admission in civil engineering at iit kanpur?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>7. Admission Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">How can i get admission in iit kanpur ?</li>
                <li class="list-group-item col-xs-12">How can i get admission at iit kanpur in mtech ?</li>
                <li class="list-group-item col-xs-12">How can i get admission in mechanical engineering at iit kanpur ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>8. Affilation Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Is delhi university aiu approved ?</li>
                <li class="list-group-item col-xs-12">Is mtech at nsit approved by aicte?</li>
                <li class="list-group-item col-xs-12">Is mba + pgpm program offered by iibs noida affiliated to bharathiar university ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>9. Review Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">How is delhi university ?</li>
                <li class="list-group-item col-xs-12">How is iit kanpur for pursuing btech ?</li>
                <li class="list-group-item col-xs-12">How is iit kanpur for pursuing engineering ?</li>
                <li class="list-group-item col-xs-12">How is iit kanpur for pursuing civil engineering ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>10. Eligibility Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Do i need to write any entrance exam to get into iima ?</li>
                <li class="list-group-item col-xs-12">How much do i need to score in jee mains and class 12th to get admission in btech iit kanpur ?</li>
                <li class="list-group-item col-xs-12">How much do i need to score in jee mains and class 12th to get admission in civil engineering iit kanpur ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>11. Cutoff Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">What is cut off for at iit kanpur?</li>
                <li class="list-group-item col-xs-12">What is the aieee cut off for at iit kanpur?</li>
                <li class="list-group-item col-xs-12">What is the cut off of btech at iit kanpur?</li>
                <li class="list-group-item col-xs-12">What is the cut off of engineering at iit kanpur?</li>
                <li class="list-group-item col-xs-12">What is the cut off for civil engineering at iit kanpur ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>12. Category Page Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Part time colleges in delhi accepting cat</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>13. Exam Cutoff Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">What is the cut-off for cat this year ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>14. Exam Eligibility Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">What is the eligibility for jee mains this year ?</li>
        </ul>
</div>


<div class="col-md-12 alert-info">
        <h4>15. Exam Dates Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">When will the cat exam for mba be conducted this year?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>16. Exam Counselling Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Which site can we apply for jee mains counselling from ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>17. Exam Answer Key Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Can you share the answer key of jee mains paper set w ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>18. Exam Admit Card Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">Where can i download jee mains admit card ?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>19. Exam Sample Papers Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">From where can i get the previous year's question papers of jee mains?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>20. Exam Pattern Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">What is the exam pattern of jee advanced?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>20. Exam Syllabus Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">What is the syllabus of iit jee?</li>
        </ul>
</div>

<div class="col-md-12 alert-info">
        <h4>21. Exam Application Form Questions : </h4>
        <ul class="list-group row">
                <li class="list-group-item col-xs-12">How can i apply for sat online?</li>
        </ul>
</div>

<style>
.alert-info {
	    padding: 10px 15px;
}
</style>


	</div>
	<div id="result" class="alert alert-success hide"></div>

	<pre  id="resultinfo" class="alert alert-info hide">
	</pre>
</div>

	<script type="text/javascript">
		
			    $("#search").click(function()
			    {
			        var textboxvalue = $('input[name=query]').val();

			        $.ajax(
			        {
			            type: "POST",
			            headers: {
			            	"AUTHREQUEST": "INFOEDGE_SHIKSHA",
							"Content-Type": "application/x-www-form-urlencoded",
							"Access-Control-Allow-Origin": "*"
			            },
			            //url: 'http://172.16.3.111/autoanswer/api/v1/query',
				url: 'https://apis.shiksha.com/autoanswer/api/v1/query',
			            data: {"question": textboxvalue},
				    beforeSend : function(){
						$("#result").removeClass("hide").html("Processing ...");
						$("#resultinfo").html("");
					},
			            success: function(result)
			            {
					if(typeof(result.data.answer) !== undefined){
						if(result.data.answer == "")
							$("#result").removeClass("hide").html("No Answer Found.");
						else{
					                $("#result").removeClass("hide").html(result.data.answer);
							$("#resultinfo").removeClass("hide").html(JSON.stringify(result, undefined, 2));
						}
					}
					else
						$("#result").removeClass("hide").html("Something went wrong !!!");
			            },
				    error: function(){
						$("#result").removeClass("hide").html("Something went wrong. !!!");
					}
				
			        });
			    });
	</script>
</div>
</div>
</body>

</html>
