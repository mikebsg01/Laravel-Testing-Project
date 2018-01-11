<?php

function createPost($attributes = []) {
  return factory(App\Post::class)->create($attributes);
}