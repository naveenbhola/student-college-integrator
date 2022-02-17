<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();

define("BUCKET_STATIC", "static");
define("BUCKET_INSTITUTE_ATTR", "institute_attributes");
define("BUCKET_RANKING", "ranking");

$config['buckets_priority'] =  array(BUCKET_STATIC, BUCKET_INSTITUTE_ATTR, BUCKET_RANKING);

$config['buckets'] =  array(BUCKET_STATIC => 
										array( array("pattern" => "what is your name ?",
													  "type" => "static",
													  "response" => "My Name is AutoBot"),
												array("pattern" => "Hi",
													  "type" => "static",
													  "response" => "Hello")
											 ),
							BUCKET_INSTITUTE_ATTR =>
										array(     // Fees Type Attribute
												array("pattern" => "what is {*} college?"),
												array("pattern" => "how many {*,1} college"),
												array("pattern" => "{*} what (is|are) the{*,0,2}(fee|fees) {*}"),
												array("pattern" => "{*} want to know{*,0,2}(fee|fees) {*}"),
												array("pattern" => "{*} how much{*,0,2}(fee|fees) {*}"),
												array("pattern" => "what is (fee|fees) structure for {*}"),
												array("pattern" => "{*} know the (fee|fees) strurcture {*}"),
												array("pattern" => "(details|detail) regarding{*,0,2}(fee|fees)"),
												array("pattern" => "(information|info) (abt|about){*,0,2}(fee|fees)"),
												array("pattern" => "{*} provide {*} (fee|fees) {*} for"),
												array("pattern" => "{*} want to know the fees {*}"),

												array("pattern" => "{*} How (is|are)(the)?(placements|placement) {*}"),
												array("pattern" => "{*} How about{*,0,1}placements {*}"),
												array("pattern" => "{*} Can {I|we|you}{*,0,4}(placement|placements) {*}"),
												array("pattern" => "{*} What (is|are){*,0,1}(placement|placements) {*}"),
												array("pattern" => "{*} (placement|placements) {*,0,2} which (company|companies)"),
												array("pattern" => "{*} (last|current) (year|years) (placement|placements) {*}"),
												array("pattern" => "{*} placements from {*}"),

												array("pattern" => "How (many|much){*,0,2}(seat|seats) *"),
												array("pattern" => "* total (seat|seats) *"),
												array("pattern" => "* number of (seat|seats) *"),
												array("pattern" => "what{*,0,2}(seat|seats) availability *"),
												array("pattern" => "Is{*,0,2}(seat|seats) * (available|) *","boolReply" => true),
												array("pattern" => "can i (get|apply){*,0,3}seat *","boolReply" => true),
												array("pattern" => "is there seat *","boolReply" => true)
											),
							BUCKET_RANKING => array(
												array("pattern" => "what are the top {*} colleges in delhi")
											)
							);
?>