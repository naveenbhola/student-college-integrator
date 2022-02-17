<?php

class Doctrine
{
    public static $entityManagers = array();
    public static $config;
    public static $modules = array('User/user','User/registration','OnlineForms/onlineFormEnterprise','OnlineForms/Online','MMM/mailer');

    public function __construct()
    {
         // include Doctrine's fancy ClassLoader class
        require_once APPPATH.'orm/Doctrine/Common/ClassLoader.php';
        // load the Doctrine classes
        $doctrineClassLoader = new \Doctrine\Common\ClassLoader('Doctrine', APPPATH.'orm');
        $doctrineClassLoader->register();
           
        $doctrineClassLoader = new \Doctrine\Common\ClassLoader('PhpAmqpLib', APPPATH.'libraries/amqp');
        $doctrineClassLoader->register();
           
        // load Symfony2 helpers
        // Don't be alarmed, this is necessary for YAML mapping files
        $symfonyClassLoader = new \Doctrine\Common\ClassLoader('Symfony', APPPATH.'orm/Doctrine');
        $symfonyClassLoader->register();

        foreach(self::$modules as $module) {
            
            list($moduleContainer,$moduleName) = explode('/',$module);
            
            $entityClassLoader = new \Doctrine\Common\ClassLoader($moduleName, APPPATH.'modules/'.$moduleContainer);
            $entityClassLoader->register();
        }
        
        // load the proxy entities
        $proxyClassLoader = new \Doctrine\Common\ClassLoader('Proxies', APPPATH.'orm');
        $proxyClassLoader->register();  
    }
    
    public static function getEntityManager($module,$mode='read')
    {   
        $CI = &get_instance();
        $CI->load->library('common/DbLibCommon');
        $DBLib = new DbLibCommon;
        
        $DBConnectionForModule = $DBLib->getDBSettings($module,$mode);
           
        if(isset(self::$entityManagers[$DBConnectionForModule])) {
            return self::$entityManagers[$DBConnectionForModule];
        }
        else {
            
            $config = self::getConfig();
            
            // include our CodeIgniter application's database configuration
            include APPPATH.'config/'.ENVIRONMENT.'/database.php';
            
            $connectionOptions = array(
                'driver' => 'pdo_mysql',
                'user' => $db[$DBConnectionForModule]['username'],
                'password' => $db[$DBConnectionForModule]['password'],
                'host' => $db[$DBConnectionForModule]['hostname'],
                'dbname' => $db[$DBConnectionForModule]['database'],
                'unix_socket' => $db[$DBConnectionForModule]['socket'],
                'port' => $db[$DBConnectionForModule]['port']
            );
            
            // create the EntityManager
            $entityManager = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
            
            self::$entityManagers[$DBConnectionForModule] = $entityManager;
            return $entityManager;
        }
    }
    
    public static function getConfig()
    {
        if(self::$config) {
            return self::$config;
        }
        else {
              
            // set up the configuration 
            $config = new \Doctrine\ORM\Configuration;
        
            if(1 || ENVIRONMENT == 'development') {
                // set up simple array caching for development mode
                $cache = new \Doctrine\Common\Cache\ArrayCache;
            }
            else {
                // set up caching with APC for production mode
                $cache = new \Doctrine\Common\Cache\ApcCache;
            }
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);
    
            // set up proxy configuration
            $config->setProxyDir(APPPATH.'orm/Proxies');
            $config->setProxyNamespace('Proxies');
            
            // auto-generate proxy classes if we are in development mode
            $config->setAutoGenerateProxyClasses(ENVIRONMENT == 'development');
    
            $mappings = array();
            foreach(self::$modules as $module) {
                $mappings[] = APPPATH.'modules/'.$module.'/Mappings';
            }
            
            // set up annotation driver
            //$yamlDriver = new \Doctrine\ORM\Mapping\Driver\YamlDriver($mappings);
            //$config->setMetadataDriverImpl($yamlDriver);
            
            $xmlDriver = new \Doctrine\ORM\Mapping\Driver\XmlDriver($mappings);
            $config->setMetadataDriverImpl($xmlDriver);
            
            $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger);
            self::$config = $config;
            return $config;
        }
    }

    public static function getRepository($module,$entity,$mode)
    {
        $entityManager = self::getEntityManager($module,$mode); 
        $repository = $entityManager->getRepository($entity);
        return $repository;
    }
}
