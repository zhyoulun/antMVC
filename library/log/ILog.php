<?php

namespace ant\library\log;

interface ILog
{
    public function trace();
    public function info();
    public function warning();
    public function error();
}