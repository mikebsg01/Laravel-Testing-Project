<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Laracasts\TestDummy\Factory;

class UserTest extends TestCase
{
    /** @test */
    public function it_fetches_the_users_full_name()
    {
        $user = Factory::build('App\User', ['first_name' => 'John', 'last_name' => 'Doe']);

	    $this->assertEquals('John Doe', $user->full_name);
    }
}
