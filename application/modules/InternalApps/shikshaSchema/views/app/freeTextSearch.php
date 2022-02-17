<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Free Text Search</title>

<style>
<!-- 
select {font-size:12px;}
A:link {text-decoration: none; color: blue}
A:visited {text-decoration: none; color: purple}
A:active {text-decoration: red}
A:hover {text-decoration: underline; color:red}
-->
</style>
<script TYPE="text/javascript"> 
<!-- hide 
function killerrors()
{ 
return true; 
} 
window.onerror = killerrors; 
// --> 
</script>
<style type="text/css">
<!--
.ft0{font-style:normal;font-weight:bold;font-size:27px;font-family:Arial;color:#000000;}
.ft1{font-style:normal;font-weight:normal;font-size:16px;font-family:Times New Roman;color:#000000;}
.ft2{font-style:normal;font-weight:bold;font-size:19px;font-family:Arial;color:#000000;}
.ft3{font-style:normal;font-weight:bold;font-size:16px;font-family:Arial;color:#000000;}
.ft4{font-style:normal;font-weight:normal;font-size:16px;font-family:Gautami;color:#000000;}
.ft5{font-style:normal;font-weight:normal;font-size:16px;font-family:Times New Roman;color:#222222;}
.ft6{font-style:normal;font-weight:normal;font-size:16px;font-family:Gautami;color:#222222;}
.ft7{font-style:normal;font-weight:normal;font-size:13px;font-family:Verdana;color:#000000;}
.ft8{font-style:normal;font-weight:normal;font-size:13px;font-family:Gautami;color:#000000;}
-->
</style>
</head>
<body vlink="#FFFFFF" link="#FFFFFF" bgcolor="#ffffff" style="margin:0px;">
<div style='background:#f8f8f8; border-bottom:1px solid #eee; height:30px;'><div style='margin:0px auto; width:840px; text-align:right; padding-top:8px;'><a href='http://snapdragon.infoedge.com/public/documentation/' class='doclink' target='_blank'></a></div></div>
<script TYPE="text/javascript">
var currentpos,timer; 
function initialize() 
{ 
timer=setInterval("scrollwindow()",10);
} 
function sc(){
clearInterval(timer); 
}
function scrollwindow() 
{ 
currentpos=document.body.scrollTop; 
window.scroll(0,++currentpos); 
if (currentpos != document.body.scrollTop) 
sc();
} 
document.onmousedown=sc
document.ondblclick=initialize
</script>
<div style="position:absolute;top:0;left:0"></div>
<div style="position:absolute;top:102;left:62"><span class="ft0">Mobile App : Search</span></div>
<div style="position:absolute;top:113;left:329"><span class="ft1"> </span></div>
<div style="position:absolute;top:141;left:68"><span class="ft1"> </span></div>
<div style="position:absolute;top:163;left:68"><span class="ft2">1. Tag Search(Free Text) </span></div>
<div style="position:absolute;top:191;left:68"><span class="ft1">Whenever a user searches for a tag with a Query Q, following steps are carried out to fetch the </span></div>
<div style="position:absolute;top:214;left:68"><span class="ft1">results : </span></div>
<div style="position:absolute;top:237;left:93"><span class="ft1">1. Query parameter(q) is set to Q+&quot;Q&quot;. Quoted part of the query helps in exact matching of </span></div>
<div style="position:absolute;top:259;left:118"><span class="ft1">the query. </span></div>
<div style="position:absolute;top:282;left:118"><span class="ft1">Eq. If user searches for “mba&quot; then, q=mba+&quot;mba&quot; </span></div>
<div style="position:absolute;top:305;left:93"><span class="ft1">2. The fields on which search will work are :  </span></div>
<div style="position:absolute;top:328;left:143"><span class="ft1">a.</span></div>
<div style="position:absolute;top:328;left:168"><span class="ft3"> tag_name</span></div>
<div style="position:absolute;top:328;left:246"><span class="ft4">.</span></div>
<div style="position:absolute;top:328;left:246"><span class="ft1"> : text_general_tight </span></div>
<div style="position:absolute;top:351;left:143"><span class="ft1">b.</span></div>
<div style="position:absolute;top:351;left:168"><span class="ft3"> tag_name_without_special_chars</span></div>
<div style="position:absolute;top:351;left:434"><span class="ft4">.</span></div>
<div style="position:absolute;top:351;left:434"><span class="ft1"> : course_facet </span></div>
<div style="position:absolute;top:374;left:143"><span class="ft1">c.</span></div>
<div style="position:absolute;top:374;left:168"><span class="ft3"> tag_name_text</span></div>
<div style="position:absolute;top:374;left:285"><span class="ft4">.</span></div>
<div style="position:absolute;top:374;left:285"><span class="ft1"> : text </span></div>
<div style="position:absolute;top:397;left:143"><span class="ft1">d.</span></div>
<div style="position:absolute;top:397;left:168"><span class="ft3"> tag_name_string</span></div>
<div style="position:absolute;top:397;left:302"><span class="ft4">.</span></div>
<div style="position:absolute;top:397;left:302"><span class="ft1"> : text_general_tight_nonstopwords </span></div>
<div style="position:absolute;top:420;left:143"><span class="ft1">e.</span></div>
<div style="position:absolute;top:420;left:168"><span class="ft3"> tag_synonyms</span></div>
<div style="position:absolute;top:420;left:285"><span class="ft4">.</span></div>
<div style="position:absolute;top:420;left:285"><span class="ft1"> : text_general_tight </span></div>
<div style="position:absolute;top:443;left:93"><span class="ft1">3. tie is set to 0.2 to consider score of each matching field. </span></div>
<div style="position:absolute;top:466;left:93"><span class="ft1">4. Tags with high quality score are given a slight boost. Eg. bf=ord(tag_quality_factor)^2 </span></div>
<div style="position:absolute;top:489;left:93"><span class="ft1">5. tag_synonym allows the matching of synonyms with the given text. Eg. if a user types </span></div>
<div style="position:absolute;top:512;left:118"><span class="ft1">“</span></div>
<div style="position:absolute;top:512;left:124"><span class="ft4">.</span></div>
<div style="position:absolute;top:512;left:124"><span class="ft5">master of business administration</span></div>
<div style="position:absolute;top:512;left:372"><span class="ft6">.</span></div>
<div style="position:absolute;top:512;left:372"><span class="ft1">&quot; then </span></div>
<div style="position:absolute;top:512;left:419"><span class="ft4">.</span></div>
<div style="position:absolute;top:512;left:419"><span class="ft3">tag_synonyms </span></div>
<div style="position:absolute;top:512;left:541"><span class="ft4">.</span></div>
<div style="position:absolute;top:512;left:541"><span class="ft1">field will help in mapping it to </span></div>
<div style="position:absolute;top:534;left:118"><span class="ft1">“mba&quot;. </span></div>
<div style="position:absolute;top:556;left:68"><span class="ft2"> </span></div>
<div style="position:absolute;top:584;left:68"><span class="ft2">2. Question Search(Free Text) </span></div>
<div style="position:absolute;top:612;left:68"><span class="ft1">Whenever a user searches for a question with a Query Q, following steps are carried out to fetch </span></div>
<div style="position:absolute;top:634;left:68"><span class="ft1">the results : </span></div>
<div style="position:absolute;top:657;left:93"><span class="ft1">1. Using QER objective and background part of the Q is identified. </span></div>
<div style="position:absolute;top:680;left:93"><span class="ft1">2. Objective part of Q is matched with </span></div>
<div style="position:absolute;top:680;left:381"><span class="ft4">.</span></div>
<div style="position:absolute;top:683;left:381"><span class="ft7">question_objective</span></div>
<div style="position:absolute;top:683;left:513"><span class="ft8">.</span></div>
<div style="position:absolute;top:680;left:513"><span class="ft1">  and background part is matched </span></div>
<div style="position:absolute;top:703;left:118"><span class="ft1">with </span></div>
<div style="position:absolute;top:703;left:153"><span class="ft4">.</span></div>
<div style="position:absolute;top:706;left:153"><span class="ft7">question_background. </span></div>
<div style="position:absolute;top:706;left:312"><span class="ft8">.</span></div>
<div style="position:absolute;top:703;left:312"><span class="ft1">Both parts are also matched with </span></div>
<div style="position:absolute;top:703;left:560"><span class="ft4">.</span></div>
<div style="position:absolute;top:706;left:560"><span class="ft7">question_title </span></div>
<div style="position:absolute;top:706;left:660"><span class="ft8">.</span></div>
<div style="position:absolute;top:703;left:660"><span class="ft1">field in the </span></div>
<div style="position:absolute;top:726;left:118"><span class="ft1">question document. Entities (such as course level) are also identified from Q to given </span></div>
<div style="position:absolute;top:749;left:118"><span class="ft1">them boost. </span></div>
<div style="position:absolute;top:772;left:93"><span class="ft1">3. Questions with no answers are filtered out. </span></div>
<div style="position:absolute;top:795;left:93"><span class="ft1">4. An extra boost of 100 is also given on question_title field for showing relevant results. </span></div>
<div style="position:absolute;top:818;left:68"><span class="ft1"> </span></div>
<div style="position:absolute;top:840;left:68"><span class="ft2">3. Discussion Search(Free Text) </span></div>
<div style="position:absolute;top:868;left:68"><span class="ft1">Whenever a user searches for a discussion with a Query Q, following steps are carried out to </span></div>
<div style="position:absolute;top:891;left:68"><span class="ft1">fetch the results :  </span></div>
<div style="position:absolute;top:914;left:93"><span class="ft1">1. Q is matched with the discussion_title(mandatory) and discussion_description(optional) </span></div>
<div style="position:absolute;top:937;left:118"><span class="ft1">field. </span></div>
<script TYPE="text/javascript">
			var currentZoom = parent.ltop.currentZoom;
			if(currentZoom != undefined)
				document.body.style.zoom=currentZoom/100;
			</script>
</body>
</html>