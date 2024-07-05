<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitorLocation extends Model
{
    use HasFactory;

    const AFRICA = 1;        // Africa

    const ANTARCTICA = 2;    // Antarctica

    const ASIA = 3;          // Asia

    const EUROPE = 4;        // Europe

    const NORTH_AMERICA = 5; // North America

    const OCEANIA = 6;       // Oceania

    const SOUTH_AMERICA = 7; // South America

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'monitor_locations';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'monitor_location_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'agent_url',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('orderByName', function ($builder) {
            $builder->orderBy('name');
        });
    }

    /**
     * Get the websites that belong to the monitor location.
     */
    public function websites()
    {
        return $this->belongsToMany(Website::class, 'pivot_monitor_locations_websites', 'monitor_location_id', 'website_id')
            ->withTimestamps()
            ->using(Pivot\MonitorLocationWebsite::class);
    }
}
