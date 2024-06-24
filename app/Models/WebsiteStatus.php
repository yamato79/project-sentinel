<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Traits\HasSlug;

class WebsiteStatus extends Model
{
    use HasSlug;

    const DEFAULT = 1;
    const ONLINE  = 2;
    const OFFLINE = 3;
    const PAUSED  = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'website_statuses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'website_status_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
    ];

    /**
     * The booted function of the model.
     */
    protected static function booted()
    {
        parent::booted();
        static::bootHasSlug();
    }
}
