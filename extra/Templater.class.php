<?php
include_once dirname(dirname(__FILE__)).'/libs/Smarty.class.php';
class Template{
    private static $instance;

    public static  function getInstance(){
        if(!self::$instance){
            self::$instance=new Smarty;
            self::$instance->force_compile = true;
            self::$instance->caching = false;
            $rootRirName = dirname(dirname(__FILE__));
            self::$instance->setTemplateDir($rootRirName.'/templates');
            self::$instance->setCompileDir($rootRirName.'/templates_c');
            self::$instance->setCacheDir($rootRirName.'/cache');
            self::$instance->setConfigDir($rootRirName.'/configs');
        }
        return self::$instance;
    }

    private function __sleep(){}

    private function __wakeup(){}

    private function __clone(){}


}