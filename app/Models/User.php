<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function videos()
    {
        return $this->hasMany(video::class , 'trainer_id' );
    }

    public function role()
    {
        return $this->belongsTo(role::class, 'role_id');
    }


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('public/' . $this->image) : null;
    }

    public function isMemberOfGroup( $userId , $groupId ) {
        return GroupMember::where('id', $groupId)->where('user_id' , $userId)->exists();
    }
}
