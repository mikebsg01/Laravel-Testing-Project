<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Exception;

class Team extends Model
{
    protected $fillable = [
        'name',
        'max_size'
    ];
   
    /**
     * Get all the members of the team.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function members() {
        return $this->hasMany('App\User');
    }

    /**
     * Get the count of members of the team.
     * 
     * @return int
     */
    public function countMembers() {
        return $this->members->count();
    }

    /**
     * Return 'true' if the object is an user.
     * 
     * @param mixed $object
     * 
     * @return bool
     */
    public function isUser($object) {
        return $object instanceof User;
    }

    /**
     * Filter the users in a given parameter.
     * 
     * @param mixed $users
     * 
     * @return Illuminate\Support\Collection
     */
    protected function filterUsers($users) {
        $users = $this->isUser($users) ? collect([$users]) : $users;

        if (! $users instanceof SupportCollection) {
            return collect([]); // returns an empty collection
        }

        $users = $users->filter(function($value, $key) {
            return $this->isUser($value);
        });

        return $users;
    }

    /**
     * Guard against adding more members than
     * the maximum size allowed for the team.
     * 
     * @param mixed $users
     * 
     * @throws \Exception
     */
    protected function guardAgainstTooManyMembers($users) {
        $users = $this->filterUsers($users);
        $numMembersToAdd = $users->count();
        $newMembersCount = $this->countMembers() + $numMembersToAdd;

        if ($newMembersCount > $this->max_size) {
            throw new Exception('The number of team members is the maximum.');
        }
    }

    /**
     * Add one or multiple members to the team.
     * 
     * @param mixed $users
     * 
     * @return bool Return 'true' if the users have been 
     *              successfully added.
     */
    public function add($users) {
        // Guard
        $this->guardAgainstTooManyMembers($users);
        $users = $this->filterUsers($users);

        return $this->members()->saveMany($users);
    }

    /**
     * Return 'true' if the user is a
     * member of the team.
     * 
     * @param mixed $user
     * 
     * @return bool
     */
    public function isMember($user) {
        return $this->isUser($user) and $this->id == $user->team_id;
    }

    /**
     * Remove one or multiple members of the team.
     * 
     * @param mixed $users
     * 
     * @return bool Return 'true' if the users have been 
     *              successfully removed.
     */
    public function remove($users) {
        $users = $users instanceof User ? collect([$users]) : $users;

        if (! $users instanceof SupportCollection) {
            return false;
        }

        $users = $users->filter(function($value, $key) {
            return $this->isMember($value);
        });

        return $this->members()
                    ->whereIn('id', $users->pluck('id'))
                    ->update(['team_id' => null]);
    }

    /**
     * Remove all the members of the team.
     * 
     * @param mixed $users
     * 
     * @return bool Return 'true' if all the users of the team
     *              have been successfully removed.
     */
    public function reset() {
        return $this->members()
                    ->update(['team_id' => null]);
    }
}
