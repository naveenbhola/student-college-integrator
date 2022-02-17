<?php

class AlsoViewedFactory
{
    /**
     * Get the also viewed generator object
     * The generator depends upon the also viewed algo used
     * context provides extra information e.g. studyabroad
     */
    public static function getAlsoViewedGenerator($alsoViewedAlgo, $context = NULL)
    {
        $CI = & get_instance();

        switch($alsoViewedAlgo) {
            case 'SHIKSHA_ALSO_VIEWED':
                $CI->load->library('recommendation/alsoviewed/ShikshaAlsoViewed');
                return new ShikshaAlsoViewed;
            case 'COLLABORATIVE_FILTERING':
                $CI->load->library('recommendation/alsoviewed/CollaborativeFilteringAlsoViewed');
                return new CollaborativeFilteringAlsoViewed;
        }
    }
}
