<?php

class Parsexml 
{
	function search_curl($url,$q,$start,$rows,$qt)
	{
		if($url=="")
		{
			$url="http://localhost:8983/solr/select";
			$query_url=$url.'?q='.$q.'&start='.$start.'&qt='.$qt.'&rows='.$rows.'&indent=on';
		}
		else
		{
			$query_url=$url;
		}
		//error_log_shiksha("URL:".$query_url);
		$curl = curl_init();
		// Setup headers - I used the same headers from Firefox version 2.0.0.6
		// below was split up because php.net said the line was too long. :/
		$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: "; // browsers keep this blank.

		curl_setopt($curl, CURLOPT_URL, $query_url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);

		$html = curl_exec($curl); // execute the curl command

		curl_close($curl); // close the connection

		//error_log_shiksha("Shirish: ".$html);

		return $html; // and finally, return $html
	}

	function xml_curl($url,$content)
	{

		$ch = curl_init(); // initialize curl handle

		curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
		curl_setopt($ch, CURLOPT_POST, 1);

		$result = curl_exec($ch); // run the whole process

		return $result;
	}

    function convert_searchxml($a)
    {
        $xml_head="<add>\n<doc>\n";
        $xml_tail="</doc>\n</add>\n";
        $xml_content="";
        foreach ($a as $k => $v) 
        {
            $xml_content.="<field name=\"$k\">$v</field>\n";
        }
        $xml_doc=$xml_head.$xml_content.$xml_tail;
        return $xml_doc;
    }
}
?>
