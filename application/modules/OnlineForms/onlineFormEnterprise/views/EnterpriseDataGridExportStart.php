<?php
if (ob_get_level() == 0) ob_start();			
echo "<style>
		body,div{
			font-size:14px;
			font-family:arial;
		}
	  </style>";
echo "<br>All filtered data will be downloaded.<br> Preparing Data...";
echo str_pad('',4096)."\n";
ob_flush();
flush();