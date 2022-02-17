<span class="opt-ul" id="socialLayer" style="display: none;">
 <ul>
     <li><a href="javascript: void(0);" onClick="shareEntityFacebook();" class=""><i class="fb-ico"></i>facebook</a></li>
     <li><a href="javascript: void(0);" onClick="shareEntityTwitter();" class=""><i class="twt-ico"></i>Twitter</a></li>
     <li><a href="javascript: void(0);" onClick="shareEntityGoogle();" class=""><i class="gplus-ico"></i>Google +</a></li>
 </ul>
</span>

<script>
if (document.addEventListener){
         document.addEventListener('mouseup', handleShareLayerHide, false);
} else if (document.attachEvent){
         document.attachEvent('onmouseup', handleShareLayerHide);
}

function handleShareLayerHide(e)
{
	var target = (e && e.target) || (event && event.srcElement); 
	var obj = $('socialLayer'); 
	if(target!=obj){
		obj.style.display='none';
	} 
}
</script>
