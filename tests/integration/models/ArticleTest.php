<?php

use App\Article;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArticleTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  public function it_fetches_articles() {
    // Given
    factory(App\Article::class, 3)->create();
    factory(App\Article::class)->create(['reads' => 10]);
    $mostPopular = factory(App\Article::class)->create(['reads' => 20]);
    // When
    $articles = Article::trending()->get();
    // Then
    $this->assertEquals($mostPopular->id, $articles->first()->id);
    $this->assertCount(3, $articles);
  }
}