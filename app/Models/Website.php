<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Website extends Model
{
    use HasFactory;

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

        static::creating(function ($website) {
            if (empty($website->created_by_user_id)) {
                $website->created_by_user_id = auth()->user()->getKey();
            }

            if (empty($website->slug)) {
                $website->slug = Str::slug($website->name).'-'.strtolower(Str::random(12));
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
     * Get the 'is_monitor_active' attribute.
     */
    public function getIsMonitorActiveAttribute()
    {
        return $this->website_status_id !== WebsiteStatus::PAUSED;
    }

    /**
     * Get the user that the website belongs to.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'user_id');
    }

    /**
     * The monitor queues that belong to the monitor type.
     */
    public function monitorQueues()
    {
        return $this->hasMany(MonitorQueue::class, 'website_id', 'website_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the stacks that the website belongs to,
     */
    public function stacks()
    {
        return $this->belongsToMany(Stack::class, 'pivot_stacks_websites', 'website_id', 'stack_id')
            ->withTimestamps()
            ->using(Pivot\StackWebsite::class);
    }

    /**
     * Get the website status that the website belongs to.
     */
    public function websiteStatus()
    {
        return $this->belongsTo(WebsiteStatus::class, 'website_status_id', 'website_status_id');
    }
}
