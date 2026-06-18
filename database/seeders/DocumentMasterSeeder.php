<?php

namespace Database\Seeders;

use App\Models\DocumentMaster;
use Illuminate\Database\Seeder;

class DocumentMasterSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            // --- STAGE 1: Pre-Qualification (Subke liye mandatory) ---
            [
                'document_code' => 'PAN_CARD',
                'name' => 'PAN Card',
                'description' => 'Upload a clear picture of the front side of your PAN Card.',
                'applicable_entities' => null, // null means ALL
                'sides_required' => 1,
                'allowed_formats' => 'jpg,jpeg,png,pdf',
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],
            [
                'document_code' => 'AADHAAR_CARD',
                'name' => 'Aadhaar Card',
                'description' => 'Upload both front and back sides of your Aadhaar Card.',
                'applicable_entities' => null,
                'sides_required' => 2, // Frontend ko 2 boxes dikhane hain
                'allowed_formats' => 'jpg,jpeg,png,pdf',
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],
            [
                'document_code' => 'BANK_STATEMENT_6M',
                'name' => 'Last 6 Months Bank Statement',
                'description' => 'Upload PDF format of your current account statement.',
                'applicable_entities' => null,
                'sides_required' => 0, // 0 means Multi-page file (usually PDF)
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],

            // --- STAGE 2: Final Application (Common) ---
            [
                'document_code' => 'ITR_2YRS',
                'name' => 'ITR for last 2 years',
                'description' => 'Income Tax Returns with computation.',
                'applicable_entities' => null,
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'GST_CERT',
                'name' => 'GST Registration Certificate',
                'description' => 'Full GST certificate including annexures.',
                'applicable_entities' => null,
                'sides_required' => 0,
                'allowed_formats' => 'pdf,jpg,png',
                'is_mandatory' => false,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'UDYAM_CERT',
                'name' => 'Udyam Registration',
                'description' => 'MSME/Udyam Certificate.',
                'applicable_entities' => null,
                'sides_required' => 1,
                'allowed_formats' => 'pdf,jpg,png',
                'is_mandatory' => false,
                'collection_stage' => 'final_application',
                'status' => 1
            ],

            // --- STAGE 2: Specific to Entity Type (JSON Arrays) ---
            [
                'document_code' => 'PARTNERSHIP_DEED',
                'name' => 'Partnership Deed',
                'description' => 'Registered partnership deed.',
                'applicable_entities' => ['Partnership'], // Array casted to JSON
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'CERT_INCORPORATION',
                'name' => 'Certificate of Incorporation',
                'description' => 'Company registration certificate.',
                'applicable_entities' => ['Pvt Ltd'],
                'sides_required' => 1,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'MOA_AOA',
                'name' => 'MOA and AOA',
                'description' => 'Memorandum and Articles of Association.',
                'applicable_entities' => ['Pvt Ltd'],
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'BOARD_RES',
                'name' => 'Board Resolution',
                'description' => 'Resolution authorizing the loan application.',
                'applicable_entities' => ['Pvt Ltd'],
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'LLP_AGREEMENT',
                'name' => 'LLP Agreement',
                'description' => 'Incorporation agreement for LLP.',
                'applicable_entities' => ['LLP'],
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
        ];

        foreach ($documents as $doc) {
            DocumentMaster::firstOrCreate(
                ['document_code' => $doc['document_code']], 
                $doc
            );
        }
    }
}