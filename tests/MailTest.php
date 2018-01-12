<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MailTest extends TestCase
{
    use MailTracking;

    /** @test */
    public function testExample()
    {
        $this->visit('/test/email')
             ->seeEmailWasSent()
             ->seeEmailSubject('Hi Freddy')
             ->seeEmailTo('foo@bar.com')
             ->seeEmailEquals('Hello, how are my friend?')
             ->seeEmailContains('friend');
    }
}