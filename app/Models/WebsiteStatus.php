<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WebsiteStatus extends Model
{
    const DEFAULT = 1;

    const ONLINE = 2;

    const OFFLINE = 3;

    const PAUSED = 4;

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

        static::creating(function ($websiteStatus) {
            if (empty($websiteStatus->slug)) {
                $websiteStatus->slug = Str::slug($websiteStatus->name).'-'.strtolower(Str::random(12));
            }
        });
    }

    /**
     * The websites that belong to the website status.
     */
    public function websites()
    {
        return $this->hasMany(Website::class, 'website_status_id', 'website_status_id');
    }
}
