<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Traits\HasSlug;

class Stack extends Model
{
    use HasFactory;
    use HasSlug;

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
     * The booted function of the model.
     */
    protected static function booted()
    {
        parent::booted();
        static::bootHasSlug();

        static::creating(function ($model) {
            if (!$model->created_by_user_id) {
                $model->created_by_user_id = auth()->user()->getKey();
            }
        });
    }

    /**
     * Get the users that belong to the stack.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'pivot_stacks_users', 'user_id', 'stack_id')
            ->using(Pivot\StackUser::class)
            ->withPivot([
                'can_view',
                'can_edit',
            ]);
    }

    /**
     * Get the websites that belong to the stack.
     */
    public function websites()
    {
        return $this->belongsToMany(User::class, 'pivot_stacks_websites', 'website_id', 'stack_id')
            ->using(Pivot\StackWebsite::class);
    }
}
