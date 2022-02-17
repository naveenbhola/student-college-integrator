<div id="adcontainer1"></div>
<?php
$channelId = isset($urlparams['channelId']) ? $urlparams['channelId'] : 'MAIN_SEARCH_PAGE';
$query = addslashes(htmlspecialchars($solr_institute_data['raw_keyword']));
$queryLink = $urlparams['search_type'];

$queryContext[] = isset($urlparams['course_type']) && !empty($urlparams['course_type']) ? $urlparams['course_type']  : '';
$queryContext[] = isset($urlparams['course_level']) && !empty($urlparams['course_level']) ? $urlparams['course_level'] : '';
$queryContext = trim(implode('|', $google_context),'|');
?>

<script type="text/javascript" charset="utf-8"> 
var pageOptions = { 
  'pubId': 'shiksha-js',
  'query': '<?php echo $query; ?>',
  'queryLink' : '<?php echo $queryLink; ?>',
  'queryContext' : '<?php echo $queryContext; ?>',
  'channel': '<?php echo $channelId; ?>',
  'hl': 'en'
};
var adblock1 = { 
  'container': 'adcontainer1',
  'number': 5,
  'width': '700px'
};
_googCsa('ads', pageOptions, adblock1);
</script>
