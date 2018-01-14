<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'team_id'
    ];

    public function getFullNameAttribute() {
        return "$this->first_name $this->last_name";
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the team of the user.
     * 
     * @return App\Team
     */
    public function team() {
        return $this->belongsTo('App\Team');
    }

    /**
     * Remove the relationship between
     * the user and its team.
     * 
     * @return bool Return 'true' if the user has left
     *              the team successfully.
     */
    public function leaveTeam() {
        $this->team()->dissociate();

        return $this->save();
    }
}
