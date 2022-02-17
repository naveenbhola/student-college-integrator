<?php
/**
 * Description of JobManagerRepository
 * @author ashish
 */
class JobManagerFactory {

    private static $type = "AMQP";

    //private static $type = "Beanstalkd";
    //private static $type = "AMQP";

    public static function getClientInstance() {
        //include abstract class definition
        require_once(__DIR__ . '/JobClient.php');

        //include exception classes
        require_once(__DIR__ . '/JobExceptions.php');

        if (self::$type == "Gearman") {
            require_once(__DIR__ . '/GearmanJobClient.php');
            $class = "GearmanJobClient";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        } else if (self::$type == "Beanstalkd") {
            //include Pheanstalk library
            require_once(FCPATH . APPPATH . 'libraries/pheanstalk/pheanstalk_init.php');

            require_once(__DIR__ . '/BeanstalkdJobClient.php');
            $class = "BeanstalkdJobClient";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        } else if (self::$type == "AMQP") {
            //include amqp library
  //          require_once(FCPATH . APPPATH . 'libraries/amqp/amqp.inc');

            require_once(__DIR__ . '/AMQPJobClient.php');
            $class = "AMQPJobClient";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        } else if (self::$type == "NetGearman") {
            //include net_gearman client library
            require_once ('Net/Gearman/Client.php');

            require_once(__DIR__ . '/NetGearmanJobClient.php');
            $class = "NetGearmanJobClient";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        }
    }

    public static function getWorkerInstance() {
        //include abstract class definition
        require_once(__DIR__ . '/JobWorker.php');

        //include exception classes
        require_once(__DIR__ . '/JobExceptions.php');

        if (self::$type == "Gearman") {
            require_once(__DIR__ . '/GearmanJobWorker.php');
            $class = "GearmanJobWorker";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        } else if (self::$type == "Beanstalkd") {
            //include Pheanstalk library
            require_once(FCPATH . APPPATH . 'libraries/pheanstalk/pheanstalk_init.php');

            require_once(__DIR__ . '/BeanstalkdJobWorker.php');
            $class = "BeanstalkdJobWorker";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        } else if (self::$type == "AMQP") {
            //include amqp library
    //        require_once(FCPATH . APPPATH . 'libraries/amqp/amqp.inc');

            require_once(__DIR__ . '/AMQPJobWorker.php');
            $class = "AMQPJobWorker";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        } else if (self::$type == "NetGearman") {
            //include net_gearman worker library
            require_once ('Net/Gearman/Worker.php');

            require_once(__DIR__ . '/NetGearmanJobWorker.php');
            $class = "NetGearmanJobWorker";
            if (!class_exists($class)) {
                throw new Exception("$class not found.");
            }
            return new $class;
        }
    }

}

?>
