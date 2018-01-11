<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikesTest extends TestCase
{
    use DatabaseTransactions;

    protected $post;
    protected $user;

    public function signIn(User $user = null) {
        $this->user = $user ?: factory(App\User::class)->create();

        $this->actingAs($this->user);

        return $this;
    }

    protected function userLikeAPost() {
        // Given I have a post
        // and an user
        // and that user is logged in
        $this->post = createPost();

        // when they like a post
        $this->signIn();
        $this->post->like();
    }

    /** @test */
    public function a_user_can_like_a_post() {
        $this->userLikeAPost();

        // then we should see evidence in the database, 
        // and the post should be liked
        $this->seeInDatabase('likes', [
            'user_id'       => $this->user->id,
            'likeable_id'   => $this->post->id,
            'likeable_type' => 'post'
        ]);

        $this->assertTrue($this->post->isLiked());
    }

    /** @test */
    public function a_user_can_unlike_a_post() {
        $this->userLikeAPost();

        $this->post->unlike();

        $this->notSeeInDatabase('likes', [
            'user_id'       => $this->user->id,
            'likeable_id'   => $this->post->id,
            'likeable_type' => 'post'
        ]);

        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_user_can_toggle_a_posts_like_status() {
        $this->userLikeAPost();

        $this->post->toggleLike();

        $this->notSeeInDatabase('likes', [
            'user_id'       => $this->user->id,
            'likeable_id'   => $this->post->id,
            'likeable_type' => 'post'
        ]);

        $this->assertFalse($this->post->isLiked());

        $this->post->toggleLike();

        $this->seeInDatabase('likes', [
            'user_id'       => $this->user->id,
            'likeable_id'   => $this->post->id,
            'likeable_type' => 'post'
        ]);

        $this->assertTrue($this->post->isLiked());
    }

    /** @test */
    public function a_post_knows_how_many_likes_it_has() {
        $this->userLikeAPost();

        $this->assertEquals(1, $this->post->likesCount);
    }
}
