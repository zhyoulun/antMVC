<?php

namespace ant\core;


abstract class BaseModel
{
    abstract public function dbName();
    abstract public function tableName();
}