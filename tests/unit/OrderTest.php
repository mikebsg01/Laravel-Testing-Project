<?php

use App\Order;
use App\Product;

class OrderTest extends PHPUnit_Framework_TestCase 
{
  protected $order;

  public function setUp() {
    $this->order = new Order;
    $product1 = new Product('Fallout 4', 59);
    $product2 = new Product('Pillowcase', 7);

    $this->order->add($product1);
    $this->order->add($product2);
  }

  /** @test */
  public function an_order_constis_of_products() {
    $this->assertCount(2, $this->order->products());
  }

  /** @test */
  public function an_order_can_determine_the_total_cost_of_all_its_products() {
    $this->assertEquals(66, $this->order->total());
  }
}