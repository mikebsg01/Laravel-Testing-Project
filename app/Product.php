<?php

namespace App;

class Product 
{
  protected $name;

  public function __construct($name, $cost = 0) {
    $this->name = $name;
    $this->cost = $cost;
  }

  public function name() {
    return $this->name;
  }

  public function cost() {
    return $this->cost;
  }
}