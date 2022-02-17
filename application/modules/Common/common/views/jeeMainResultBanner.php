<style type="text/css">
.fixedLeft{position:fixed;left:20px;bottom:110px;z-index: 9999;}
.blackBox{position:relative}
.cross{background:#fff;color:#000;position:absolute;right:-12px;top:2px;box-shadow:0px 1px 10px 0 rgba(0,0,0,0.7);border-radius:50%;height:20px;height:28px;width:28px;text-align:center;line-height:24px;font-weight:600;font-size: 18px;cursor: pointer;}
.textBox{background:#000;color:#fff;position:relative;border-radius:50%;padding:10px;width:110px;height:110px;text-align:center;box-shadow: 0px 1px 23px 0px rgba(0,0,0,0.5)}
.textBox:before,.textBox:after{content:"";display:inline-block;width:30px;height:2px;background:#fff;position:absolute;text-align:center;left:50%;transform:translateX(-50%)}
.textBox:before{top:15px}
.textBox:after{bottom:15px}
.textBox .arrow.right{margin-left: -2px;}
i.right.arrow:after{margin: 0}
.textBox .link {font-size: 12px;color:#0efbf3;}
.textBox p{line-height: 16px;margin-top: 15px;font-size:14px;}
</style>
<div id="jeeMainResBanner" class="fixedLeft">
	<div class="blackBox">
		<span class="cross" onclick="document.getElementById('jeeMainResBanner').style.display = 'none'">x</span>
		<a onclick="document.getElementById('jeeMainResBanner').style.display = 'none'" href="https://www.shiksha.com/b-tech/jee-main-exam-results">
		<div class="textBox">
			<p>JEE Main Result declared</p>
			<span class="link">Read Details<i class="arrow right"></i></span>
		</div>
		</a>
	</div>
</div>