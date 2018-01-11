<?php

namespace App\Traits;

use App\Like;
use Auth;

trait Likeability {
   /**
     * Get the 'likes' of this.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function likes() {
      return $this->morphMany('App\Like', 'likeable');
  }

  /** 
   * Add a 'like' to 'this' with the 
   * current 'user' id.
   * 
   * @return bool returns 'true' if 'this' has been
   *              successfully liked.
   */
  public function like() {
      return $this->likes()->save(new Like(['user_id' => Auth::id()]));
  }

  /** 
   * Remove a 'like' to 'this' with the 
   * current 'user' id.
   * 
   * @return bool returns 'true' if 'this' has been
   *              successfully unliked.
   */
  public function unlike() {
      return $this->likes()
                  ->where('user_id', Auth::id())
                  ->delete();
  }

  /** 
   * Check if 'this' is liked 
   * by the current user.
   * 
   * @return bool returns 'true' if 'this' is liked
   *              by the current user.
   */
  public function isLiked() {
      return $this->likes()
                  ->where('user_id', Auth::id())
                  ->exists();
  }

  /** 
   * Toggle the like status of the current user
   * over this.
   * 
   * @return bool returns 'true' if the corresponding 
   *              action has been successful
   */
  public function toggleLike() {
      return $this->isLiked() ? $this->unlike() : $this->like();
  }

  /**
   * Get the number of likes it has.
   * 
   * @return int
   */
  public function getLikesCountAttribute() {
      return $this->likes()->count();
  }
}