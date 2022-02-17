<?php

function get_data($url)
{
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

$fh = fopen("/tmp/recobug.txt","a");
fwrite($fh,date("Y-m-d H:i:s"));
fwrite($fh,"\n");
fclose($fh);

$returned_content = get_data('http://localshiksha.com/recommendation/recommendation/getRecommendations');

