<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/* ====================================================================
 * The Apache Software License, Version 1.1
 *
 * Copyright (c) 2000-2004 The Apache Software Foundation.  All rights
 * reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *
 * 3. The end-user documentation included with the redistribution,
 *    if any, must include the following acknowledgment:
 *       "This product includes software developed by the
 *        Apache Software Foundation (http://www.apache.org/)."
 *    Alternately, this acknowledgment may appear in the software itself,
 *    if and wherever such third-party acknowledgments normally appear.
 *
 * 4. The names "Apache" and "Apache Software Foundation" must
 *    not be used to endorse or promote products derived from this
 *    software without prior written permission. For written
 *    permission, please contact apache@apache.org.
 *
 * 5. Products derived from this software may not be called "Apache",
 *    nor may "Apache" appear in their name, without prior written
 *    permission of the Apache Software Foundation.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED.  IN NO EVENT SHALL THE APACHE SOFTWARE FOUNDATION OR
 * ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 * USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * ====================================================================
 *
 * This software consists of voluntary contributions made by many
 * individuals on behalf of the Apache Software Foundation.  For more
 * information on the Apache Software Foundation, please see
 * <http://www.apache.org/>.
 *
 * Portions of this software are based upon public domain software
 * originally written at the National Center for Supercomputing Applications,
 * University of Illinois, Urbana-Champaign.
 * $Id:$
 */
/**
* Class and Function List:
* Function list:
* - __construct()
* - show_404()
* - show_error()
* - is_extension()
* - trace()
* - debug_path()
* - error_handler()
* - exception_handler()
* - shutdown_handler()
* - exception_text()
* - debug_source()
* Classes list:
* - MY_Exceptions extends CI_Exceptions
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Exceptions extends CI_Exceptions {
    /**
     * Some nice names for the error types
     */
      private $flag_mobile_user_agent = "";

    public static $php_errors = array(
        E_ERROR => 'Fatal Error',
        E_USER_ERROR => 'User Error',
        E_PARSE => 'Parse Error',
        E_WARNING => 'Warning',
        E_USER_WARNING => 'User Warning',
        E_STRICT => 'Strict',
        E_NOTICE => 'Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
    );
    /**
     * The Shutdown errors to show (all others will be ignored).
     */
    public static $shutdown_errors = array(
        E_PARSE,
        E_ERROR,
        E_USER_ERROR,
        E_COMPILE_ERROR
    );
    function __construct() {
        parent::__construct();
        log_message('debug', 'MY_Exceptions Class Initialized');
        // If we are in production, then lets dump out now.
        // if (ENVIRONMENT == 'production')
        //{
        //    return;
        // }
        //Set the Exception Handler
        set_exception_handler(array(
            'MY_Exceptions',
            'exception_handler'
        ));
        // Set the Error Handler
        set_error_handler(array(
            'MY_Exceptions',
            'error_handler'
        ));
        // Set the handler for shutdown to catch Parse errors
        register_shutdown_function(array(
            'MY_Exceptions',
            'shutdown_handler'
        ));
        // This is a hack to set the default timezone if it isn't set. Not setting it causes issues.
        date_default_timezone_set(date_default_timezone_get());
    }
    function show_404($page = '') {
        //check if this is Study abroad domain page. If yes, load the Study abroad error page, else load the normal Shiksha error page
        $domain = $_SERVER['HTTP_HOST'];
        $subDomain = explode('.',$domain);
        $subDomain = $subDomain[0];
        if($subDomain=='studyabroad'){
            $this->show_404_abroad();
            exit();
        }
        
        GLOBAL $flag_mobile_user_agent;
        GLOBAL $flag_mobile_js_support_user_agent;
        $this->flag_mobile_js_support_user_agent = $flag_mobile_js_support_user_agent;
        $this->flag_mobile_user_agent =  $flag_mobile_user_agent; 
        if((($this->flag_mobile_user_agent == "mobile") || ($_COOKIE['ci_mobile'] == 'mobile')) && ($_COOKIE['user_force_cookie']  != "YES"))
        {
            header("Sorry, we couldn't find the page you requested.", true, 404);
            $this->config = & get_config();
            $base_url = $this->config['base_url'];
            $ch = curl_init();
            if( ($this->flag_mobile_js_support_user_agent == "yes") || ($_COOKIE['ci_mobile_js_support'] == 'yes') ){
                    curl_setopt($ch, CURLOPT_URL, $base_url . "mcommon5/MobileSiteStatic/mobile404");
            }
            else{
                    curl_setopt($ch, CURLOPT_URL, $base_url . "mcommon/MobileSiteStatic/mobile404");
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_exec($ch);
            curl_close($ch);
            exit();
        }

        // log the error
        log_message('error', '404 Page Not Found --> ' . $page);
        header("Sorry, we couldn't find the page you requested.", true, 404);
        $this->config = & get_config();
        $base_url = $this->config['base_url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "shikshaHelp/ShikshaHelp/errorPage");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($ch);
        curl_close($ch);
        exit();
    }

    function show_410($page = '') {
        GLOBAL $flag_mobile_user_agent;
        GLOBAL $flag_mobile_js_support_user_agent;
        $this->flag_mobile_js_support_user_agent = $flag_mobile_js_support_user_agent;
        $this->flag_mobile_user_agent =  $flag_mobile_user_agent;
        if((($this->flag_mobile_user_agent == "mobile") || ($_COOKIE['ci_mobile'] == 'mobile')) && ($_COOKIE['user_force_cookie']  != "YES"))
        {
            header("Sorry, we couldn't find the page you requested.", true, 410);
            $this->config = & get_config();
            $base_url = $this->config['base_url'];
            $ch = curl_init();
            if( ($this->flag_mobile_js_support_user_agent == "yes") || ($_COOKIE['ci_mobile_js_support'] == 'yes') ){
                    curl_setopt($ch, CURLOPT_URL, $base_url . "mcommon5/MobileSiteStatic/mobile404");
            }
            else{
                    curl_setopt($ch, CURLOPT_URL, $base_url . "mcommon/MobileSiteStatic/mobile404");
            }
            curl_exec($ch);
            curl_close($ch);
            exit();
        }

        //check if this is Study abroad domain page. If yes, load the Study abroad error page, else load the normal Shiksha error page
        $domain = $_SERVER['HTTP_HOST'];
        $subDomain = explode('.',$domain);
        $subDomain = $subDomain[0];
        if($subDomain=='studyabroad'){
            $this->show_404_abroad();
            exit();
        }

        // log the error
        log_message('error', '410 Page Not Found --> ' . $page);
        header("Sorry, we couldn't find the page you requested.", true, 410);
        $this->config = & get_config();
        $base_url = $this->config['base_url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "shikshaHelp/ShikshaHelp/errorPage");
        curl_exec($ch);
        curl_close($ch);
        exit();
    }

    function show_404_abroad($page = '') {
        log_message('error', 'Study Abroad 404 Page Not Found --> ' . $page);
        header("Sorry, we couldn't find the page you requested.", true, 404);
        $this->config = & get_config();
        $base_url = $this->config['base_url'];
        $ch = curl_init();
        
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,array("userEmail"=>strtok($_COOKIE['user'],'|')));
        curl_setopt($ch, CURLOPT_URL, $base_url . "shikshaHelp/ShikshaHelp/errorPageAbroad");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_exec($ch);
        curl_close($ch);
        exit();
    }
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
        error_log(':::error:::' . $heading . '---' . print_r($message, true));
        header("Sorry, we couldn't find the page you requested.", true, $status_code);
        $this->config = & get_config();
        $base_url = $this->config['base_url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . "shikshaHelp/ShikshaHelp/errorPage");
        curl_exec($ch);
        curl_close($ch);
        exit();
    }
    /**
     * Is Extension
     *
     * This checks to see if the file path is to a core extension.
     *
     * @access	private
     * @param	string	$file
     * @return	bool
     */
    private static function is_extension($file) {
        foreach (array(
            'libraries/',
            'core/'
        ) as $folder) {
            if (strpos($file, APPPATH . $folder . config_item('subclass_prefix')) === 0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    /**
     * Trace
     *
     * Returns an array of HTML strings that represent each step in the backtrace.
     *
     * @access	public
     * @param	string	path to debug
     * @return	string
     */
    public static function trace(array $trace = NULL) {
        if ($trace === NULL) {
            // Start a new trace
            $trace = debug_backtrace();
        }
        // Non-standard function calls
        $statements = array(
            'include',
            'include_once',
            'require',
            'require_once'
        );
        $output = array();
        foreach ($trace as $step) {
            if (!isset($step['function'])) {
                // Invalid trace step
                continue;
            }
            if (isset($step['file']) AND isset($step['line'])) {
                // Include the source of this step
                $source = self::debug_source($step['file'], $step['line']);
            }
            if (isset($step['file'])) {
                $file = $step['file'];
                if (isset($step['line'])) {
                    $line = $step['line'];
                }
            }
            // function()
            $function = $step['function'];
            if (in_array($step['function'], $statements)) {
                if (empty($step['args'])) {
                    // No arguments
                    $args = array();
                } else {
                    // Sanitize the file path
                    $args = array(
                        $step['args'][0]
                    );
                }
            } elseif (isset($step['args'])) {
                if (strpos($step['function'], '{closure}') !== FALSE) {
                    // Introspection on closures in a stack trace is impossible
                    $params = NULL;
                } else {
                    if (isset($step['class'])) {
                        if (method_exists($step['class'], $step['function'])) {
                            $reflection = new ReflectionMethod($step['class'], $step['function']);
                        } else {
                            $reflection = new ReflectionMethod($step['class'], '__call');
                        }
                    } else {
                        $reflection = new ReflectionFunction($step['function']);
                    }
                    // Get the function parameters
                    $params = $reflection->getParameters();
                }
                $args = array();
                foreach ($step['args'] as $i => $arg) {
                    if (isset($params[$i])) {
                        // Assign the argument by the parameter name
                        $args[$params[$i]->name] = $arg;
                    } else {
                        // Assign the argument by number
                        $args[$i] = $arg;
                    }
                }
            }
            if (isset($step['class'])) {
                // Class->method() or Class::method()
                $function = $step['class'] . $step['type'] . $step['function'];
            }
            $output[] = array(
                'function' => $function,
                'args' => isset($args) ? $args : NULL,
                'file' => isset($file) ? $file : NULL,
                'line' => isset($line) ? $line : NULL,
                'source' => isset($source) ? $source : NULL,
            );
            unset($function, $args, $file, $line, $source);
        }
        return $output;
    }
    /**
     * Debug Path
     *
     * This makes nicer looking paths for the error output.
     *
     * @access	public
     * @param	string	$file
     * @return	string
     */
    public static function debug_path($file) {
        if (strpos($file, APPPATH) === 0) {
            $file = 'APPPATH/' . substr($file, strlen(APPPATH));
        } elseif (strpos($file, SYSDIR) === 0) {
            $file = 'SYSDIR/' . substr($file, strlen(SYSDIR));
        } elseif (strpos($file, FCPATH) === 0) {
            $file = 'FCPATH/' . substr($file, strlen(FCPATH));
        }
        return $file;
    }
    /**
     * Error Handler
     *
     * Converts all errors into ErrorExceptions. This handler
     * respects error_reporting settings.
     *
     * @access	public
     * @throws	ErrorException
     * @return	bool
     */
    public static function error_handler($code, $error, $file = NULL, $line = NULL) {
        if (error_reporting() & $code) {
            // This error is not suppressed by current error reporting settings
            // Convert the error into an ErrorException
            self::exception_handler(new ErrorException($error, $code, 0, $file, $line));
        }
        // Do not execute the PHP error handler
        return TRUE;
    }
    /**
     * Exception Handler
     *
     * Displays the error message, source of the exception, and the stack trace of the error.
     *
     * @access	public
     * @param	object	 exception object
     * @return	boolean
     */
    public static function exception_handler(Exception $e) {
        try {
            // Get the exception information
            $type = get_class($e);
            $code = $e->getCode();
            $message = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            // Create a text version of the exception
            $error = self::exception_text($e);
            // Log the error message
            log_message('error', $error, TRUE);
            error_log("ERROR" . $error);
            // Get the exception backtrace
            $trace = $e->getTrace();
            if ($e instanceof ErrorException) {
                if (isset(self::$php_errors[$code])) {
                    // Use the human-readable error name
                    $code = self::$php_errors[$code];
                }
            }

            $logUrl = $_SERVER['SCRIPT_URI'].($_SERVER['QUERY_STRING'] ? "?".$_SERVER['QUERY_STRING'] : "");
            $referrer = $_SERVER['HTTP_REFERER'];
            $serverIp = getHostByName(getHostName());

            global $rtr_module;
            global $rtr_class;
            global $rtr_method;
            $isMobile = 0;
            if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')) {
                $isMobile = 1;
            }
             
            $dataToLog = array(
                                'logType' => 'Exception',
                                'error_class' => $type,
                                'error_code' => $e->getCode(),
                                'source_file' => $file,
                                'line_num' => $line,
                                'url' => $logUrl,
                                'referer' => $referrer,
                                'module' => $rtr_module,
                                'controller' => $rtr_class,
                                'method' => $rtr_method,
                                'server' => $serverIp,
                                'mobile' => $isMobile,
                                'exception_msg' => str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($message)),
                                'logTime' => date('Y-m-d H:i:s')        
                        );

            if(!(strtolower($_SERVER['HTTP_SOURCE']) == 'scantool' || strtolower($_SERVER['SOURCE']) == 'scantool'))
                error_log("\n".implode('~e~',$dataToLog), 3, '/tmp/edLogs.log');

            if (ENVIRONMENT != 'production')
            {
                ob_start();
                // This will include the custom error file.
                require FCPATH . "/" . APPPATH . 'errors/error_php_custom.php';
                // Display the contents of the output buffer
                echo ob_get_clean();
            }
            
            return TRUE;
        }
        catch(Exception $e) {
            // Clean the output buffer if one exists
            ob_get_level() and ob_clean();
            // Display the exception text
            echo self::exception_text($e) , "\n";
            // Exit with an error status
            exit(1);
        }
    }
    /**
     * Shutdown Handler
     *
     * Catches errors that are not caught by the error handler, such as E_PARSE.
     *
     * @access	public
     * @return	void
     */
    public static function shutdown_handler() {
        $error = error_get_last();
        if ($error = error_get_last() AND in_array($error['type'], self::$shutdown_errors)) {
            // Clean the output buffer
            ob_get_level() and ob_clean();
            // Fake an exception for nice debugging
            self::exception_handler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
            // Shutdown now to avoid a "death loop"
            exit(1);
        }
    }
    /**
     * Exception Text
     *
     * Makes a nicer looking, 1 line extension.
     *
     * @access	public
     * @param	object	Exception
     * @return	string
     */
    public static function exception_text(Exception $e) {
        return sprintf('%s [ %s ]: %s ~ %s [ %d ]', get_class($e) , $e->getCode() , strip_tags($e->getMessage()) , $e->getFile() , $e->getLine());
    }
    /**
     * Debug Source
     *
     * Returns an HTML string, highlighting a specific line of a file, with some
     * number of lines padded above and below.
     *
     * @access	public
     * @param	string	 file to open
     * @param	integer	 line number to highlight
     * @param	integer	 number of padding lines
     * @return	string	 source of file
     * @return	FALSE	 file is unreadable
     */
    public static function debug_source($file, $line_number, $padding = 5) {
        if (!$file OR !is_readable($file)) {
            // Continuing will cause errors
            return FALSE;
        }
        // Open the file and set the line position
        $file = fopen($file, 'r');
        $line = 0;
        // Set the reading range
        $range = array(
            'start' => $line_number - $padding,
            'end' => $line_number + $padding
        );
        // Set the zero-padding amount for line numbers
        $format = '% ' . strlen($range['end']) . 'd';
        $source = '';
        while (($row = fgets($file)) !== FALSE) {
            // Increment the line number
            if (++$line > $range['end']) break;

            if ($line >= $range['start']) {
                // Make the row safe for output
                $row = htmlspecialchars($row, ENT_NOQUOTES);
                // Trim whitespace and sanitize the row
                $row = '<span class="number">' . sprintf($format, $line) . '</span> ' . $row;
                if ($line === $line_number) {
                    // Apply highlighting to this row
                    $row = '<span class="line highlight">' . $row . '</span>';
                } else {
                    $row = '<span class="line">' . $row . '</span>';
                }
                // Add to the captured source
                $source.= $row;
            }
        }
        // Close the file
        fclose($file);
        return '<pre class="source"><code>' . $source . '</code></pre>';
    }
}
/* End of file MY_Exceptions.php */
/* Location: ./system/application/core/MY_Exceptions.php */
