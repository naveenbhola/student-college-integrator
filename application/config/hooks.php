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
* Classes list:
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
|
*/
/* ==============
 * TYPES OF HOOKS
 * ==============
pre_system
	Called very early during system execution. Only the benchmark and hooks class have been
	loaded at this point. No routing or other processes have happened.
pre_controller
	Called immediately prior to any of your controllers being called. All base classes, routing,
	and security checks have been done.
post_controller_constructor
	Called immediately after your controller is instantiated, but prior to any method calls
	happening.
post_controller
	Called immediately after your controller is fully executed.
display_override
	Overrides the _display() function, used to send the finalized page to the web browser at the
	end of system execution. This permits you to use your own display methodology. Note that you
	will need to reference the CI superobject with $this->CI =& get_instance() and then the
	finalized data will be available by calling $this->CI->output->get_output()
cache_override
	Enables you to call your own function instead of the _display_cache() function in the output
class. This permits you to use your own cache display mechanism.
post_system
	Called after the final rendered page is sent to the browser, at the end of system execution
	after the finalized data is sent to the browser.
*/
global $isMobileApp;

if($isMobileApp)
{
	$hook['pre_system'][] = array(
	    'function' => 'api_version',
	    'filename' => 'api_version.php',
	    'filepath' => 'hooks',
	);
}
else
{
	$hook['pre_system'][] = array(
	    'function' => 'get_mobile_useragent',
	    'filename' => 'get_mobile_useragent.php',
	    'filepath' => 'hooks',
	);
}

$hook['pre_system'][] = array(
    'function' => 'load_exceptions',
    'filename' => 'error_handler.php',
    'filepath' => 'hooks',
);

$hook['pre_system'][] = array(
    'function' => 'track_visitor',
    'filename' => 'visitor_tracker.php',
    'filepath' => 'hooks',
);

$hook['pre_controller'][] = array(
            'function' => 'set_siteview',
            'filename' => 'set_siteview.php',
            'filepath' => 'hooks',
        );

$hook['post_system'][] = array(
    'function' => 'log_performance_metrics',
    'filename' => 'log_performance_metrics.php',
    'filepath' => 'hooks',
);

$hook['post_system'][] = array(
    'function' => 'set_apache_var',
    'filename' => 'set_apache_var.php',
    'filepath' => 'hooks',
);

$hook['post_system'][] = array(
    'function' => 'set_amp_response_header',
    'filename' => 'set_amp_response_header.php',
    'filepath' => 'hooks',
);
// compress output
/*$hook['display_override'][] = array(
	'class' => '',
	'function' => 'html_compress',
	'filename' => 'html_compress.php',
	'filepath' => 'hooks'
	);*/
/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */
