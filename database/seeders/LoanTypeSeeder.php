<?php

namespace Database\Seeders;

use App\Models\LoanType;
use Illuminate\Database\Seeder;

class LoanTypeSeeder extends Seeder
{
    public function run(): void
    {
        $loanTypes = [
            [
                'name' => 'Working Capital Loan',
                'description' => 'Short-term loan for daily business operations and cash flow.',
                'status' => 1
            ],
            [
                'name' => 'Term Loan',
                'description' => 'Long-term loan for business expansion or fixed assets.',
                'status' => 1
            ],
            [
                'name' => 'Machinery & Equipment Loan',
                'description' => 'Specific loan to purchase new machinery or equipment.',
                'status' => 1
            ],
            [
                'name' => 'Business Credit Card',
                'description' => 'Revolving credit facility for business expenses.',
                'status' => 1
            ]
        ];

        foreach ($loanTypes as $type) {
            LoanType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}