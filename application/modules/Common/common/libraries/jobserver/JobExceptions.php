<?php

/**
 * Description of JobExceptions
 * @author ashish
 */

/**
 * Exception to be thrown at connection failure
 */
class JobConnectionException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}

/**
 * Exception to be thrown at job queuing failure
 */
class JobAddException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}

/**
 * Exception to be thrown at method registration failure
 */
class JobMethodRegisterException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}


/**
 * Exception to be thrown at job queuing failure
 */
class QueuePurgeException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
?>
