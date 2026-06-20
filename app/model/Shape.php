<?php
class Shape
{
  public $name;
  public $color;
  public $vertices;
  public $length_labels;
  public $length_label_unit;
  
  public function __construct()
  {
    $this->name='';
    $this->vertices=array();
    $this->color=array();
    $this->length_labels=array(0,0,0,0);
    $this->length_label_unit='';
  }
}
