<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stack extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stacks';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'stack_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by_user_id',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($stack) {
            if (empty($stack->created_by_user_id)) {
                $stack->created_by_user_id = auth()->user()->getKey();
            }

            if (empty($stack->slug)) {
                $stack->slug = Str::slug($stack->name).'-'.strtolower(Str::random(12));
            }
        });
    }

    /**
     * Get the user that the stack belongs to.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'user_id');
    }

    /**
     * Get the users that belong to the stack.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'pivot_stacks_users', 'stack_id', 'user_id')
            ->using(Pivot\StackUser::class)
            ->withTimestamps()
            ->withPivot([
                'can_view',
                'can_edit',
                'joined_at',
            ]);
    }

    /**
     * Get the websites that belong to the stack.
     */
    public function websites()
    {
        return $this->belongsToMany(Website::class, 'pivot_stacks_websites', 'stack_id', 'website_id')
            ->withTimestamps()
            ->using(Pivot\StackWebsite::class);
    }
}
