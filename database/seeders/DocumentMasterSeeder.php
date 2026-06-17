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
                'name' => 'PAN Card',
                'entity_type' => null, // null means applicable to ALL
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],
            [
                'name' => 'Aadhaar Card',
                'entity_type' => null,
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],
            [
                'name' => 'Last 6 Months Bank Statement',
                'entity_type' => null,
                'is_mandatory' => true,
                'collection_stage' => 'pre_qualification',
                'status' => 1
            ],

            // --- STAGE 2: Final Application (Common) ---
            [
                'name' => 'ITR for last 2 years',
                'entity_type' => null,
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'name' => 'GST Registration Certificate',
                'entity_type' => null,
                'is_mandatory' => false,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'name' => 'Udyam Registration',
                'entity_type' => null,
                'is_mandatory' => false,
                'collection_stage' => 'final_application',
                'status' => 1
            ],

            // --- STAGE 2: Final Application (Specific to Entity Type) ---
            [
                'name' => 'Partnership Deed',
                'entity_type' => 'Partnership', // Sirf Partnership ko dikhega
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'name' => 'Certificate of Incorporation',
                'entity_type' => 'Pvt Ltd', // Sirf Pvt Ltd ko dikhega
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'name' => 'MOA and AOA',
                'entity_type' => 'Pvt Ltd',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'name' => 'Board Resolution',
                'entity_type' => 'Pvt Ltd',
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
            [
                'name' => 'LLP Agreement',
                'entity_type' => 'LLP', // Sirf LLP ko dikhega
                'is_mandatory' => true,
                'collection_stage' => 'final_application',
                'status' => 1
            ],
        ];

        foreach ($documents as $doc) {
            DocumentMaster::firstOrCreate(
                ['name' => $doc['name'], 'entity_type' => $doc['entity_type']], 
                $doc
            );
        }
    }
}