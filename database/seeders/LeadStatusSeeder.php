<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'New Application', 'internal_code' => 'NEW', 'color' => '#3b82f6', 'sort_order' => 1, 'is_system_locked' => true],
            ['name' => 'Screening In Progress', 'internal_code' => 'SCREENING', 'color' => '#f59e0b', 'sort_order' => 2, 'is_system_locked' => true],
            ['name' => 'Pre-Qualified', 'internal_code' => 'PRE_QUALIFIED', 'color' => '#10b981', 'sort_order' => 3, 'is_system_locked' => true],
            ['name' => 'Pending Final Documents', 'internal_code' => 'PENDING_DOCS', 'color' => '#6366f1', 'sort_order' => 4, 'is_system_locked' => true],
            ['name' => 'Processing with Bank', 'internal_code' => 'BANK_PROCESSING', 'color' => '#8b5cf6', 'sort_order' => 5, 'is_system_locked' => true],
            ['name' => 'Approved', 'internal_code' => 'APPROVED', 'color' => '#22c55e', 'sort_order' => 6, 'is_system_locked' => true],
            ['name' => 'Disbursed', 'internal_code' => 'DISBURSED', 'color' => '#14b8a6', 'sort_order' => 7, 'is_system_locked' => true],
            ['name' => 'Rejected', 'internal_code' => 'REJECTED', 'color' => '#ef4444', 'sort_order' => 8, 'is_system_locked' => true],
        ];

        foreach ($statuses as $status) {
            LeadStatus::firstOrCreate(['internal_code' => $status['internal_code']], $status);
        }
    }
}