<?php

class DetectMobile {

    /**
     * Check to see if the Http User Agent is an iPhone.
     *
     * @access  public
     * @return  bool
     */
    public function is_iphone()
    {
        return (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone');
    }

    /**
     * Check to see if the Http User Agent is an iPod.
     *
     * @access  public
     * @return  bool
     */
    public function is_ipod()
    {
        return (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPod');
    }

    /**
     * Check to see if the Http User Agent is an iPad.
     *
     * @access  public
     * @return  bool
     */
    public function is_ipad()
    {
        return (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad');
    }

    /**
     * Check to see if the Http User Agent is an Android.
     *
     * @access  public
     * @return  bool
     */
    public function is_android()
    {
        return (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'Android');
    }

    /**
     * Check to see if the Http User Agent is an Palm Pre.
     *
     * @access  public
     * @return  bool
     */
    public function is_palmpre()
    {
        return (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'webOS');
    }

    /**
     * Check to see if the Http User Agent is an BlackBerry.
     *
     * @access  public
     * @return  bool
     */
    public function is_blackberry()
    {
        return (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry');
    }
}