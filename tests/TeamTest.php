<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Team;
use App\User;
use App\Article;

class TeamTest extends TestCase
{
    use DatabaseTransactions;
    /** @test */
    public function it_has_a_name()
    {
        $team = new Team(['name' => 'Acme']);
        $this->assertEquals('Acme', $team->name);
    }

    /** @test */
    public function it_can_add_members() {
        $team = factory(Team::class)->create();
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $team->add($user1);
        $team->add($user2);

        $team = $team->fresh('members');

        $this->assertEquals(2, $team->countMembers());
    }

    /** @test */
    public function it_has_a_maximum_size() {
        $team = factory(Team::class)->create(['max_size' => 2]);
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $team->add($user1);
        $team->add($user2);

        $team = $team->fresh('members');

        $this->assertEquals(2, $team->countMembers());

        $this->setExpectedException('Exception');

        $user3 = factory(User::class)->create();
        $team->add($user3);
    }

    /** @test */
    public function it_can_add_multiple_members_at_once() {
        $team = factory(Team::class)->create(['max_size' => 3]);
        $users = factory(User::class, 3)->create();

        $team->add($users);

        $team = $team->fresh('members');

        $this->assertEquals(3, $team->countMembers());
    }

    /** @test */
    public function it_can_ignore_invalid_parameters_when_adding() {
        $team = factory(Team::class)->create();

        $team->add([1, 2, 3]);
        $team->add([]);
        $team->add(2);
        $team->add(0);
        $team->add(-1);
        $team->add(collect([1, 2, 3]));
        $team->add(factory(Article::class)->make());
        $team->add(true);
        $team->add(false);
        $team->add(collect([1, -1, factory(Article::class)->make()]));

        $this->assertEquals(0, $team->countMembers());
    }

    /** @test */
    public function it_can_remove_members() {
        $team = factory(Team::class)->create(['max_size' => 2]);
        $users = factory(User::class, 2)->create();

        $team->add($users);
        $team = $team->fresh();

        $this->assertEquals(2, $team->countMembers());

        $team->remove($users[0]);
        $team = $team->fresh();

        $this->assertEquals(1, $team->countMembers());
    }

    /** @test */
    public function it_can_remove_multiple_members_at_once() {
        $team = factory(Team::class)->create(['max_size' => 6]);
        $users = factory(User::class, 6)->create();

        $team->add($users);
        $team = $team->fresh();

        $this->assertEquals(6, $team->countMembers());

        $team->remove($users->slice(0, 4));
        $team = $team->fresh();

        $this->assertEquals(2, $team->countMembers());
    }

    /** @test */
    public function it_can_ignore_invalid_parameters_when_removing() {
        $team = factory(Team::class)->create(['max_size' => 12]);
        $team->add(factory(User::class, 12)->create());

        $team->remove([1, 2, 3]);
        $team->remove([]);
        $team->remove(2);
        $team->remove(0);
        $team->remove(-1);
        $team->remove(collect([1, 2, 3]));
        $team->remove(factory(Article::class)->make());
        $team->remove(true);
        $team->remove(false);
        $team->remove(collect([1, -1, factory(Article::class)->make()]));
        $team = $team->fresh();

        $this->assertEquals(12, $team->countMembers());
    }

    /** @test */
    public function it_can_remove_all_members_at_once() {
        $team = factory(Team::class)->create(['max_size' => 6]);
        $users = factory(User::class, 6)->create();

        $team->add($users);
        $team = $team->fresh();

        $this->assertEquals(6, $team->countMembers());

        $team->reset();
        $team = $team->fresh();

        $this->assertEquals(0, $team->countMembers());
    }

    /** @test */
    public function it_cant_add_multiple_members_that_exceed_the_maximum_size() {
        $team = factory(Team::class)->create(['max_size' => 2]);
        $users = factory(User::class, 3)->create();

        $this->setExpectedException('Exception');

        $team->add($users);
    }
}
