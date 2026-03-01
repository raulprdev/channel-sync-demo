<?php

namespace Database\Seeders;

use App\Enums\ChannelCode;
use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ChannelCode::cases() as $code) {
            Channel::firstOrCreate(
                ['code' => $code],
                ['name' => $code->name()]
            );
        }
    }
}