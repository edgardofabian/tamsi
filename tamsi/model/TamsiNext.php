<?php
class TamsiNext
{
    public $url;
    public $clear;
    public $silent;
    public $container;
    public $confirm;
    
    public function __construct($next=array())
    {
        $this->url='';
        $this->clear='';
        $this->silent='';
        $this->container='';
        $this->confirm='';
        foreach($next as $field=>$value)
        {
            $this->$field=$value;
        }
    }
}
