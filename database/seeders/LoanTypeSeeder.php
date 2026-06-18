<?php

namespace Database\Seeders;

use App\Models\LoanType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LoanTypeSeeder extends Seeder
{
    public function run(): void
    {
        $loanTypes = [
            [
                'name' => 'Working Capital Loan',
                'description' => 'Short-term loan for daily business operations and cash flow.',
                'icon_path' => 'icons/working-capital.png',
                'status' => 1
            ],
            [
                'name' => 'Term Loan',
                'description' => 'Long-term loan for business expansion or fixed assets.',
                'icon_path' => 'icons/term-loan.png',
                'status' => 1
            ],
            [
                'name' => 'Machinery & Equipment Loan',
                'description' => 'Specific loan to purchase new machinery or equipment.',
                'icon_path' => 'icons/machinery-loan.png',
                'status' => 1
            ],
            [
                'name' => 'Business Credit Card',
                'description' => 'Revolving credit facility for business expenses.',
                'icon_path' => 'icons/credit-card.png',
                'status' => 1
            ]
        ];

        foreach ($loanTypes as $type) {
            $type['slug'] = Str::slug($type['name']);
            LoanType::firstOrCreate(['slug' => $type['slug']], $type);
        }
    }
}