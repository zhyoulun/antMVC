<?php

namespace ant\core;

use ant\models\DemoModel;

class Ant
{
    private $module;
    private $controller;
    private $action;

    public function run()
    {
        register_shutdown_function(array($this, 'catchFatalError'));

//        throw new ErrorException();

        try{
            $this->getRequest();
            if($this->module==='index'){
                $controllerClassString = "ant\\controllers\\{$this->controller}Controller";
            }else{
                $controllerClassString = "ant\\modules\\{$this->module}\\controllers\\{$this->controller}Controller";
            }

            $controllerClass = new $controllerClassString();
            $action = $this->action."Action";
            $controllerClass->$action();

//            new A();
//            new DemoModel(123,'abc');
//            echo "entry";
        }catch (Exception $e){
            $this->renderError($e);
        }
    }

    private function getRequest()
    {
        $this->module = (isset($_GET['m']) && strlen($_GET['m'])>0)?strtolower($_GET['m']):'index';
        $this->controller = (isset($_GET['c']) && strlen($_GET['c'])>0)?strtolower($_GET['c']):'index';
        $this->action = (isset($_GET['a']) && strlen($_GET['a'])>0)?strtolower($_GET['a']):'index';

        if(1!==preg_match("/^[a-z0-9]+$/i", $this->module.$this->controller.$this->action))
            throw new HttpException(404);
    }

    /**
     * catch fatal error
     */
    private function catchFatalError()
    {
        $error = error_get_last();
        $exception = new ErrorException('fatal error: '.$error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
        $this->renderError($exception);
    }

    /**
     * render error
     *
     * @param \Exception $exception
     */
    private function renderError($exception)
    {
        $statusCode = $exception->getCode()==0?500:$exception->getCode();

        if(!ANT_DEBUG){
            $response = new Response();
            $response->setStatusCode($statusCode);
            $content = "status code: ".$response->getStatusCode()."; status text: ".$response->getStatusText();
            $content = HtmlHelper::simpleHtml('error', $content);
            $response->content = $content;
            $response->send();
            return;
        }

        $response = new Response();
        $response->setStatusCode($statusCode);
        $headerArray = array('file', 'line', 'class', 'type', 'function', 'args');
        $dataArray = array();
        $dataArray[] = array(
            'file'=>$exception->getFile(),
            'line'=>$exception->getLine(),
            'class'=>'',
            'type'=>'',
            'function'=>'',
            'args'=>'',
        );
        foreach ($exception->getTrace() as $trace){
            $dataArray[] = array(
                'file'=>$trace['file'],
                'line'=>$trace['line'],
                'class'=>$trace['class'],
                'type'=>$trace['type'],
                'function'=>$trace['function'],
                'args'=>implode(",",$trace['args']),
            );
        }

        $content = HtmlHelper::arrayToTable($dataArray, $headerArray);
        $content = str_replace("<table>","<table border='1'>", $content);
        $content = "<b>message</b>: ".$exception->getMessage().
            "<br /><b>code</b>: ".$exception->getCode().
            "<br />{$content}";
        $content = HtmlHelper::simpleHtml('error', $content);

        $response->content = $content;
        $response->send();
    }

    /**
     * auto load
     *
     * @param $className
     * @throws Exception
     */
    public static function autoload($className)
    {
        $file = substr($className, 4);//remove "ant\" head
        $file = str_replace('\\', '/', ANT_PATH."/{$file}.php");
        if(is_file($file))
            include($file);
        elseif(ANT_DEBUG){
            throw new Exception("class {$className} not found, file: ".$file);
        }
    }
}