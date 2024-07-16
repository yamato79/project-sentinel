<?php

namespace Database\Seeders\Production;

use App\Models\NotificationType;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Website Status Changed', 'slug' => 'website-status-changed', 'description' => 'Notifies once when a website\'s status has changed.', 'is_active' => true],
        ])->each(function ($data) {
            NotificationType::firstOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                // ...
            ]));
        });
    }
}
