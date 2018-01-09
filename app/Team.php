<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class Team extends Model
{
    protected $fillable = [
        'name',
        'max_size'
    ];
   
    /**
     * Get all the members of the team.
     */
    public function members() {
        return $this->hasMany('App\User');
    }

    /**
     * Add a member to the team.
     * 
     * @param App\User $user
     * 
     * @return void
     */
    public function add($users) {
        // Guard
        $this->guardAgainstTooManyMembers();

        if ($users instanceof User) {
            $this->members()->save($users);
        }

        if ($users instanceof Collection) {
            $this->members()->saveMany($users);
        }
    }

    /**
     * Get the count of members of the team.
     * 
     * @return int
     */
    public function count() {
        return $this->members->count();
    }

    /**
     * Guard against adding more members than
     * the maximum size allowed for the team.
     * 
     * @throws \Exception
     */
    protected function guardAgainstTooManyMembers() {
        if ($this->count() >= $this->max_size) {
            throw new Exception;
        }
    }
}
