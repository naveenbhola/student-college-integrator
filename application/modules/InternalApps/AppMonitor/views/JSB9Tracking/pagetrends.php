<?php
$this->load->view('AppMonitor/common/header');
?>
<script>
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
<div style="width:100% !important">
	<iframe src="<?php echo $iframeUrl;?>" frameborder="0" scrolling="no" onload="resizeIframe(this)" style="width:100%;">
	</iframe>
</div>
</body>
</html>
