<?php
require_once __DIR__.'/paths.cfg.php';
require_once __DIR__.'/security.cfg.php';

class Config
{
    public $paths;
    public $security;
    public function __construct()
    {
        $this->paths = new Paths();
        $this->security = new Secure_config();
    }
}
