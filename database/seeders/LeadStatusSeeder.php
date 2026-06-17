<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'New Application', 'color' => '#3b82f6', 'sort_order' => 1], // Blue
            ['name' => 'Screening In Progress', 'color' => '#f59e0b', 'sort_order' => 2], // Amber
            ['name' => 'Pre-Qualified', 'color' => '#10b981', 'sort_order' => 3], // Green
            ['name' => 'Pending Final Documents', 'color' => '#6366f1', 'sort_order' => 4], // Indigo
            ['name' => 'Processing with Bank', 'color' => '#8b5cf6', 'sort_order' => 5], // Purple
            ['name' => 'Approved', 'color' => '#22c55e', 'sort_order' => 6], // Bright Green
            ['name' => 'Disbursed', 'color' => '#14b8a6', 'sort_order' => 7], // Teal
            ['name' => 'Rejected', 'color' => '#ef4444', 'sort_order' => 8], // Red
        ];

        foreach ($statuses as $status) {
            LeadStatus::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}