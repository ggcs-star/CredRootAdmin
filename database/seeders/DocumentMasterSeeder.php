<?php

namespace Database\Seeders;

use App\Models\DocumentMaster;
use Illuminate\Database\Seeder;

class DocumentMasterSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
          
            [
                'document_code' => 'PAN_CARD',
                'name' => 'Personal PAN Card',
                'description' => 'Upload a clear picture of the front side of your Personal PAN Card.',
                'document_level' => 'user',
                'applicable_entities' => null, 
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
                'document_level' => 'user',
                'applicable_entities' => null,
                'sides_required' => 2, 
                'allowed_formats' => 'jpg,jpeg,png,pdf',
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],
            [
                'document_code' => 'APPLICANT_PHOTO',
                'name' => 'Applicant Passport Photo',
                'description' => 'Upload a recent passport size photograph.',
                'document_level' => 'user', // Naya Column
                'applicable_entities' => null,
                'sides_required' => 1, 
                'allowed_formats' => 'jpg,jpeg,png',
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],

            // ==========================================
            // LEVEL 2: COMPANY LEVEL (Business KYC - Uploaded Once per Company)
            // ==========================================
            [
                'document_code' => 'BANK_STATEMENT_6M',
                'name' => 'Last 6 Months Bank Statement',
                'description' => 'Upload PDF format of your company current account statement.',
                'document_level' => 'company', // Naya Column
                'applicable_entities' => null,
                'sides_required' => 0, 
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],
            [
                'document_code' => 'GST_CERT',
                'name' => 'GST Registration Certificate',
                'description' => 'Full GST certificate including annexures.',
                'document_level' => 'company', // Naya Column
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
                'document_level' => 'company', // Naya Column
                'applicable_entities' => null,
                'sides_required' => 1,
                'allowed_formats' => 'pdf,jpg,png',
                'is_mandatory' => false,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'ITR_2YRS',
                'name' => 'ITR for last 2 years',
                'description' => 'Income Tax Returns with computation of the company.',
                'document_level' => 'company', // Naya Column
                'applicable_entities' => null,
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],

            // Entity Specific Company Docs
            [
                'document_code' => 'PARTNERSHIP_DEED',
                'name' => 'Partnership Deed',
                'description' => 'Registered partnership deed.',
                'document_level' => 'company', // Naya Column
                'applicable_entities' => ['Partnership'], 
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
                'document_level' => 'company', // Naya Column
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
                'document_level' => 'company', // Naya Column
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
                'document_level' => 'company', // Naya Column
                'applicable_entities' => ['LLP'],
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],

            // ==========================================
            // LEVEL 3: LEAD LEVEL (Specific to every New Loan)
            // ==========================================
            [
                'document_code' => 'BOARD_RES',
                'name' => 'Board Resolution for Loan',
                'description' => 'Resolution authorizing this specific loan application.',
                'document_level' => 'lead', // Naya Column - Kyunki har loan ke liye naya resolution chahiye
                'applicable_entities' => ['Pvt Ltd'],
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'PROFORMA_INVOICE',
                'name' => 'Machinery Proforma Invoice',
                'description' => 'Quotation or invoice for the machinery/equipment being purchased.',
                'document_level' => 'lead', // Naya Column
                'applicable_entities' => null, // Valid for all entities
                'sides_required' => 0,
                'allowed_formats' => 'pdf,jpg,png',
                'is_mandatory' => false, // Will be made mandatory for Machinery Loan type later
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'document_code' => 'PROJECT_REPORT',
                'name' => 'CMA Data / Project Report',
                'description' => 'Detailed project report and future projections for this loan.',
                'document_level' => 'lead', // Naya Column
                'applicable_entities' => null,
                'sides_required' => 0,
                'allowed_formats' => 'pdf',
                'is_mandatory' => false,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
        ];

        foreach ($documents as $doc) {
            DocumentMaster::updateOrCreate(
                ['document_code' => $doc['document_code']], 
                $doc
            );
        }
    }
}