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
// Unique error identifier
$error_id = uniqid('error'); ?>
	<style type="text/css">
#exception_error {
	background: #ddd;
	font-size: 1em;
	font-family:sans-serif;
	text-align: left;
	color: #333333;
}
#exception_error h1,
#exception_error h2 {
	margin: 0;
	padding: 1em;
	font-size: 1em;
	font-weight: normal;
	background: #F59C49;
	color: #FFFFFF;
}
#exception_error h1 a,
#exception_error h2 a {
	color: #FFFFFF;
}
#exception_error h2 {
	background: #6893CD;
}
#exception_error h3 {
	margin: 0;
	padding: 0.4em 0 0;
	font-size: 1em;
	font-weight: normal;
}
#exception_error p {
	margin: 0;
	padding: 0.2em 0;
}
#exception_error a {
	color: #1b323b;
}
#exception_error pre {
	overflow: auto;
	white-space: pre-wrap;
}
#exception_error table {
	width: 100%;
	display: block;
	margin: 0 0 0.4em;
	padding: 0;
	border-collapse: collapse;
	background: #fff;
}
#exception_error table td {
	border: solid 1px #ddd;
	text-align: left;
	vertical-align: top;
	padding: 0.4em;
}
#exception_error div.content {
	padding: 0.4em 1em 1em;
	overflow: hidden;
}
#exception_error pre.source {
	margin: 0 0 1em;
	padding: 0.4em;
	background: #fff;
	border: dotted 1px #b7c680;
	line-height: 1.2em;
}
#exception_error pre.source span.line {
	display: block;
}
#exception_error pre.source span.highlight {
	background: #f0eb96;
}
#exception_error pre.source span.line span.number {
	color: #666;
}
#exception_error ol.trace {
	display: block;
	margin: 0 0 0 2em;
	padding: 0;
	list-style: decimal;
}
#exception_error ol.trace li {
	margin: 0;
	padding: 0;
}
.js .collapsed {
	display: none;
}
</style>
	<script type="text/javascript">
	document.documentElement.className = 'js';
function koggle(elem)
{
	elem = document.getElementById(elem);

	if (elem.style && elem.style['display'])
		// Only works with the "style" attr
	var disp = elem.style['display'];
	else if (elem.currentStyle)
		// For MSIE, naturally
	var disp = elem.currentStyle['display'];
	else if (window.getComputedStyle)
		// For most other browsers
	var disp = document.defaultView.getComputedStyle(elem, null).getPropertyValue('display');

	// Toggle the state of the "display" style
	elem.style.display = disp == 'block' ? 'none' : 'block';
	return false;
}
</script>

<div id="exception_error">
	<h1><span class="type"><?php
echo $type ?> [ <?php
echo $code ?> ]:</span> <span class="message"><?php
echo $message ?></span></h1>
	<div id="<?php
echo $error_id ?>" class="content">
		<p><span class="file"><?php
echo MY_Exceptions::debug_path($file) ?> [ <?php
echo $line ?> ]</span></p>
		
		<?php
echo MY_Exceptions::debug_source($file, $line) ?>
		
		<ol class="trace">
			<?php
foreach (MY_Exceptions::trace($trace) as $i => $step): ?>
			<li>
				<p>
					<span class="file">
					<?php
    if ($step['file']):
        $source_id = $error_id . 'source' . $i; ?>
						<a href="#<?php
        echo $source_id ?>" onclick="return koggle('<?php
        echo $source_id ?>')"><?php
        echo MY_Exceptions::debug_path($step['file']) ?> [ <?php
        echo $step['line'] ?> ]</a>
					<?php
    else: ?>
						{<?php
        echo 'PHP internal call'; ?>}
					<?php
    endif ?>
					</span>
					&raquo;
					<?php
    echo $step['function'] ?>(<?php
    if ($step['args']):
        $args_id = $error_id . 'args' . $i; ?><a href="#<?php
        echo $args_id ?>" onclick="return koggle('<?php
        echo $args_id ?>')"><?php
        echo 'arguments' ?></a><?php
    endif ?>)
				</p>
			<?php
    if (isset($args_id)): ?>
				<div id="<?php
        echo $args_id ?>" class="collapsed">
					<table cellspacing="0">
					<?php
        foreach ($step['args'] as $name => $arg): ?>
						<tr>
							<td><code><?php
            echo $name ?></code></td>
							<td><pre><?php
            echo print_r($arg, TRUE) ?></pre></td>
						</tr>
					<?php
        endforeach ?>
					</table>
				</div>
			<?php
    endif ?>
			<?php
    if (isset($source_id)): ?>
				<pre id="<?php
        echo $source_id ?>" class="source collapsed"><code><?php
        echo $step['source'] ?></code></pre>
			<?php
    endif ?>
			</li>
			<?php
    unset($args_id, $source_id); ?>
			<?php
endforeach ?>
		</ol>
	</div>

	<h2><a href="#<?php
echo $env_id = $error_id . 'environment' ?>" onclick="return koggle('<?php
echo $env_id ?>')"><?php
echo 'Environment' ?></a></h2>
	<div id="<?php
echo $env_id ?>" class="content collapsed">
	
	<?php
$included = get_included_files() ?>
		<h3><a href="#<?php
echo $env_id = $error_id . 'environment_included' ?>" onclick="return koggle('<?php
echo $env_id ?>')"><?php
echo 'Included files' ?></a> (<?php
echo count($included) ?>)</h3>
		<div id="<?php
echo $env_id ?>" class="collapsed">
			<table cellspacing="0">
			<?php
foreach ($included as $file): ?>
				<tr>
					<td><code><?php
    echo MY_Exceptions::debug_path($file) ?></code></td>
				</tr>
			<?php
endforeach ?>
			</table>
		</div>

	<?php
$included = get_loaded_extensions() ?>
		<h3><a href="#<?php
echo $env_id = $error_id . 'environment_loaded' ?>" onclick="return koggle('<?php
echo $env_id ?>')"><?php
echo 'Loaded extensions' ?></a> (<?php
echo count($included) ?>)</h3>
		<div id="<?php
echo $env_id ?>" class="collapsed">
			<table cellspacing="0">
			<?php
foreach ($included as $file): ?>
				<tr>
					<td><code><?php
    echo MY_Exceptions::debug_path($file) ?></code></td>
				</tr>
			<?php
endforeach ?>
			</table>
		</div>
		
	<?php
foreach (array(
    '_SESSION',
    '_GET',
    '_POST',
    '_FILES',
    '_COOKIE',
    '_SERVER'
) as $var): ?>
		<?php
    if (empty($GLOBALS[$var]) OR !is_array($GLOBALS[$var])) continue ?>
		<h3><a href="#<?php
    echo $env_id = $error_id . 'environment' . strtolower($var) ?>" onclick="return koggle('<?php
    echo $env_id ?>')">$<?php
    echo $var ?></a></h3>
		<div id="<?php
    echo $env_id ?>" class="collapsed">
			<table cellspacing="0">
			<?php
    foreach ($GLOBALS[$var] as $key => $value): ?>
				<tr>
					<td><code><?php
        echo $key ?></code></td>
					<td><pre><?php
        echo print_r($value, TRUE) ?></pre></td>
				</tr>
			<?php
    endforeach ?>
			</table>
		</div>
	<?php
endforeach ?>
	</div>
</div>