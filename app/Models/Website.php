<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Traits\HasSlug;

class Website extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'websites';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'website_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'website_status_id',
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

        static::created(function ($website) {
            \App\Jobs\Monitors\CheckResponseCode::dispatch($website);
            \App\Jobs\Monitors\CheckResponseTime::dispatch($website);
            \App\Jobs\Monitors\CheckSSLValid::dispatch($website);
            \App\Jobs\Monitors\CheckSSLExpiry::dispatch($website);
            \App\Jobs\Monitors\CheckDomainExpiry::dispatch($website);
            \App\Jobs\Monitors\CheckDomainNS::dispatch($website);
        });
    }

    /**
     * Get the isMonitorActive attribute.
     */
    public function getIsMonitorActiveAttribute()
    {
        return $this->website_status_id !== WebsiteStatus::PAUSED;
    }

    /**
     * Get the website status that the website belongs to.
     */
    public function websiteStatus()
    {
        return $this->belongsTo(WebsiteStatus::class, 'website_status_id', 'website_status_id');
    }
}
