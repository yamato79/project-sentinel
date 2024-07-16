<?php

namespace Database\Seeders\Production;

use App\Models\NotificationChannelDriver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use NotificationChannels\Webhook\WebhookChannel;

class NotificationChannelDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'name' => 'Webhook', 
                'slug' => 'webhook', 
                'class' => WebhookChannel::class,
                'description' => 'Sends an HTTP POST request to the target URL.',
                'is_active' => true,
                'fields' => [
                    [
                        'type' => 'text',
                        'label' => 'Target URL',
                        'name' => 'webhook_url',
                        'placeholder' => 'https://acme.com/api/receive',
                        'container_class' => 'col-span-full',
                        'required' => true,
                    ],
                ],
                'validator_rules' => [
                    'webhook_url' => 'required|url:http,https',
                ],
                'validator_messages' => [
                    'webhook_url.required' => 'The webhook url is required.',
                    'webhook_url.url' => 'The webhook url field must be a valid URL.',
                ],
            ],
        ])->each(function ($data) {
            NotificationChannelDriver::updateOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                // ...
            ]));
        });
    }
}
