<?php

namespace ant\core;

class Ant
{
    private $module;
    private $controller;
    private $action;

    //ini file absolute path
    private $iniFile;
    private $env;

    public function __construct($iniFile, $env='product')
    {
        $this->iniFile = $iniFile;
        $this->env = $env;
    }

    /**
     * run, run, run
     */
    public function run()
    {
        register_shutdown_function(array($this, 'catchFatalError'));

        try {
            $this->getRequest();
            if ($this->module === 'Index') {
                $controllerClassString = "ant\\controllers\\{$this->controller}Controller";
            } else {
                $controllerClassString = "ant\\modules\\{$this->module}\\controllers\\{$this->controller}Controller";
            }

            $this->initConfig();

            $controllerClass = new $controllerClassString();
            $action = $this->action . "Action";
            $controllerClass->$action();
        } catch (Exception $e) {
            $this->renderError($e);
        } catch (ErrorException $e){
            $this->renderError($e);
        }
    }

    /**
     * init application ini file
     *
     * @throws ErrorException
     */
    private function initConfig()
    {
        $configArray = parse_ini_file($this->iniFile, true);
        if(!is_array($configArray)){
            throw new ErrorException('parse ini file error');
        }

        if(!isset($configArray['common'])){
            throw new ErrorException('common section is required');
        }

        $subSection = '';
        foreach ($configArray as $session=>$valueArray){
            if($session==='common'){
                continue;
            }
            if(strpos($session, ':')!==false){
                $strings = explode(':', $session);
                if(count($strings)==2){
                    if(trim($strings[0])===$this->env && trim($strings[1])==='common'){
                        $subSection = $session;
                        break;
                    }
                }
            }
        }
        if($subSection===''){
            throw new ErrorException('section not found: '.$this->env);
        }

        Registry::set('config', array_merge($configArray['common'], $configArray[$subSection]));
    }

    /**
     * init MCA
     *
     * @throws HttpException
     */
    private function getRequest()
    {
        $this->module = (isset($_GET['module']) && strlen($_GET['module']) > 0) ?
            str_replace(' ', '-', ucwords(str_replace('-', ' ', $_GET['module']))) : 'Index';
        $this->controller = (isset($_GET['controller']) && strlen($_GET['controller']) > 0) ?
            str_replace(' ', '-', ucwords(str_replace('-', ' ', $_GET['controller']))) : 'Index';
        $this->action = (isset($_GET['action']) && strlen($_GET['action']) > 0) ?
            strtolower($_GET['action']) : 'index';

        if (1 !== preg_match("/^[-a-z0-9]+$/i", $this->module . $this->controller . $this->action))
            throw new HttpException(404);
    }

    /**
     * catch fatal error
     */
    private function catchFatalError()
    {
        $error = error_get_last();
        $exception = new ErrorException('fatal error: ' . $error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
        $this->renderError($exception);
    }

    /**
     * render error
     *
     * @param \Exception $exception
     */
    private function renderError($exception)
    {
        $statusCode = $exception->getCode() == 0 ? 500 : $exception->getCode();

        if (!ANT_DEBUG) {
            $response = new Response();
            $response->setStatusCode($statusCode);
            $content = "status code: " . $response->getStatusCode() . "; status text: " . $response->getStatusText();
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
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'class' => '',
            'type' => '',
            'function' => '',
            'args' => '',
        );
        foreach ($exception->getTrace() as $trace) {
            $dataArray[] = array(
                'file' => $trace['file'],
                'line' => $trace['line'],
                'class' => $trace['class'],
                'type' => $trace['type'],
                'function' => $trace['function'],
                'args' => implode(",", $trace['args']),
            );
        }

        $content = HtmlHelper::arrayToTable($dataArray, $headerArray);
        $content = str_replace("<table>", "<table border='1'>", $content);
        $content = "<b>message</b>: " . $exception->getMessage() .
            "<br /><b>code</b>: " . $exception->getCode() .
            "<br />{$content}";
        $content = HtmlHelper::simpleHtml('error', $content);

        $response->content = $content;
        $response->send();
    }
}