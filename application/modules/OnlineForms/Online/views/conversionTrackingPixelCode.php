<style type="text/css">
    table.ofctable { font-size:15px;  border-left:1px solid #ddd;  border-top:1px solid #ddd;}
    table.ofctable th { background: #eee; padding:8px; padding-left: 15px; text-align: left;  border-right:1px solid #ddd;  border-bottom:1px solid #ddd;}
    table.ofctable td { padding:8px; padding-left: 15px; text-align: left; border-right:1px solid #ddd;  border-bottom:1px solid #ddd;}
    .codeprint {font-size:13px; color:#222; display: block; background:#f6f6f6; width:900px; overflow: auto; padding:15px; border:1px solid #ddd; line-height: 150%;}
    ul.pxtype {margin-top:20px; border-bottom:1px solid #ccc;}
    ul.pxtype li {float:left;}
    ul.pxtype li a {display:block; padding:15px 25px 10px 25px; font-size: 18px; text-decoration: none; color:#666}
    ul.pxtype li a:hover {background:#f6f6f6}
    ul.pxtype li a.pxactive {background:#EEF3F9; text-decoration: none; color:#0065DC}
</style>

<div style='margin:15px;'>
    
<div class="fontSize_14p bld" style="font-size:18px;">Pixel codes for #<?php echo $form['pixel_id']; ?></div>

<?php
/*
<ul class='pxtype'>
    <li><a href='#' onclick="$j('#http_pixel_button').addClass('pxactive'); $j('#https_pixel_button').removeClass('pxactive'); $j('#http_pixel').show(); $j('#https_pixel').hide(); return false;" class='pxactive' id='http_pixel_button'>HTTP Pixel</a></li>
    
    <li><a href='#' onclick="$j('#http_pixel_button').removeClass('pxactive'); $j('#https_pixel_button').addClass('pxactive'); $j('#http_pixel').hide(); $j('#https_pixel').show(); return false;" id='https_pixel_button'>HTTPS Pixel</a></li>
    <div style='clear: both'></div>
</ul>
*/
?>

<?php
$code = "<script type=\"text/javascript\">
    var shiksha_params = {};
    shiksha_params.pixel_id = '%s';
    shiksha_params.page = '%s';
    (function(){
        var shk = document.createElement('script');
        shk.async = true;
        shk.src = '%s/public/js/shikshaConversion.js';
        var ref = document.getElementsByTagName('script')[0];
        ref.parentNode.insertBefore(shk, ref);
    })();
</script>
<noscript><img height=\"1\" width=\"1\" alt=\"\" style=\"display:none\" src=\"".SHIKSHA_HOME."/ofconversion?id=%s&page=%s\" /></noscript>";

$homeURL = SHIKSHA_HOME;
if($scheme == 'https') {
    //$homeURL = str_replace("http", "https", SHIKSHA_HOME);
}
?>

<div id='http_pixel'>
<div class="fontSize_14p bld" style="font-size:16px; margin-top: 20px;">Landing Page</div>
<pre class="codeprint"><?php echo htmlspecialchars(sprintf($code,$form['pixel_id'],'landing',$homeURL, $form['pixel_id'],'landing')); ?></pre>

<div class="fontSize_14p bld" style="font-size:16px; margin-top: 30px;">Conversion Page</div>
<pre class="codeprint"><?php echo htmlspecialchars(sprintf($code,$form['pixel_id'],'conversion',$homeURL, $form['pixel_id'],'conversion')); ?></pre>
</div>

<?php
/*
<div id='https_pixel' style='display:none'>
<div class="fontSize_14p bld" style="font-size:16px; margin-top: 20px;">Landing Page</div>
<pre class="codeprint"><?php echo htmlspecialchars(sprintf($code,$form['pixel_id'],'landing', str_replace("http", "https", SHIKSHA_HOME), $form['pixel_id'],'landing')); ?></pre>

<div class="fontSize_14p bld" style="font-size:16px; margin-top: 30px;">Conversion Page</div>
<pre class="codeprint"><?php echo htmlspecialchars(sprintf($code,$form['pixel_id'],'conversion', str_replace("http", "https", SHIKSHA_HOME), $form['pixel_id'],'conversion')); ?></pre>
</div>
*/
?>
            
<br /><br />        
<?php $this->load->view('common/leanFooter'); ?>
