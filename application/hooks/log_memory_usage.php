<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Log memory usage for request
 */
function log_memory_usage()
{
        $memoryUsage = memory_get_peak_usage();
        if($memoryUsage >= 125829120) {
                error_log("SHKMEMUSAGE:: ".$memoryUsage." :: ".$_SERVER['REQUEST_URI']);
        }
}

