<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the stacks that the website belongs to,
     */
    public function stacks()
    {
        return $this->belongsToMany(Stack::class, 'pivot_stacks_users', 'user_id', 'stack_id')
            ->using(Pivot\StackUser::class)
            ->withTimestamps()
            ->withPivot([
                'can_view',
                'can_edit',
                'joined_at',
            ]);
    }

    /**
     * Get the websites that belongs to the user.
     */
    public function websites()
    {
        return $this->hasMany(Website::class, 'created_by_user_id', 'user_id')
            ->orderBy('name', 'asc');
    }
}
