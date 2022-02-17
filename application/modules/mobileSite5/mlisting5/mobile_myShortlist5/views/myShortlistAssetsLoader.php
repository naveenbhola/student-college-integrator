<?php
$headerComponent = array('mobilecss'  => array('mshortlist'),
                         'pageName'   => $boomr_pageid,
                         'js'         => array('AutoSuggestor'),
                         'jsMobile'   => array('myShortlistMobile'),
                         'm_meta_title'=> $m_meta_title,
                         'm_meta_description' => $m_meta_description
                         );
$this->load->view('mcommon5/header',$headerComponent);