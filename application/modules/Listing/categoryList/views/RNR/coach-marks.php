<style>
.coachmark{color:#fff; font-family:"Comic Sans MS", cursive; font-size:18px;}
.coach-sprite{background:url(public/images/coach-sprite.png); display:inline-block;font-style:none; vertical-align:middle; position:relative;}
.left-top-arrow, .right-top-arrow, .left-bottom-arrow, .right-bottom-arrow{background-position:0 -77px; width:43px; height:31px;left:-32px;}
.right-top-arrow{background-position:0 -35px; left:49px;}
.left-bottom-arrow{background-position:0 -112px; left:84px;}
.right-bottom-arrow{background-position:0 0; left:90px;}
.coach-img-1, .coach-img-2, .coach-img-3, .coach-img-4{position:absolute;left: -20px;position: absolute; top: 39px; z-index: 1000;}
.coach-img-2{right:188px; top:33px; left:auto;}
.coach-img-3{top:37px; left:-30px;}
.coach-img-4{right:94px; top:37px; left:auto;}
.dismiss-text{position:absolute; left:25%; top:160px; z-index:1000;}
.dismiss-text a{text-decoration:underline; font-size:25px; color:#fff;}

</style>
<!-- Coach Mark Starts -->
<div id="coach-marks-bg" style="width: 100%; position: fixed; left: 0px; top: 0px; bottom: 0px; z-index: 999; height: 1200px; background:url(public/images/coachmark-bg.png);" onclick="hideCoachMarks();"></div>
<div class="coachmark" id="coach-marks-cont">
	<div class="coach-img-1">
    	<i class="coach-sprite left-top-arrow"></i>
        <p>Use filters to get<br/>more appropriate results</p>
    </div>
    <div class="coach-img-2">
    	<i class="coach-sprite right-top-arrow"></i>
        <p>Click to sort by Fees or Eligibility</p>
    </div>
</div>
