<div id='googlebannercontainer' class="container"><div><div id="adcontainer1"></div></div><p class="clr"></p></div>
<?php
$channelId = isset($channelId) ? $channelId : 'MAIN_SEARCH_PAGE';
$query = addslashes(htmlspecialchars($keyword));

$queryContext = trim(implode('|', $google_context),'|');
?>

<script type="text/javascript" charset="utf-8"> 
var pageOptions = { 
  'pubId': 'shiksha-js',
  'query': '<?php echo $query; ?>',
  'queryLink' : '<?php echo $search_type; ?>',
  'queryContext' : '<?php echo $queryContext; ?>',
  'channel': '<?php echo $channelId; ?>',
  'hl': 'en'
};
var adblock1 = { 
  'container': 'adcontainer1',
  'number': 5,
  'width': '665px'
};
_googCsa('ads', pageOptions, adblock1);
</script>
