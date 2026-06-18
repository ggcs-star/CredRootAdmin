<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentMaster;
use App\Models\LoanType;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    // 1. Get Active Loan Types (Frontend Dropdown ke liye)
    public function getLoanTypes()
    {
        // Ab naye fields bhi select kar rahe hain (slug, icon_path)
        $loanTypes = LoanType::where('status', 1)->get([
            'id', 'slug', 'name', 'description', 'icon_path'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $loanTypes
        ], 200);
    }

    // 2. Get Required Documents (Based on Entity Type & Stage)
    public function getRequiredDocuments(Request $request)
    {
        $request->validate([
            'entity_type' => 'nullable|string', // e.g., 'Pvt Ltd', 'Proprietorship'
            'stage' => 'required|in:pre_qualification,final_application'
        ]);

        $documents = DocumentMaster::where('status', 1)
            ->where('collection_stage', $request->stage)
            ->where(function ($query) use ($request) {
                // Condition 1: Agar applicable_entities NULL hai (Matlab sabke liye mandatory hai)
                $query->whereNull('applicable_entities');
                
                // Condition 2: Agar user ki entity match karti hai, toh JSON Array ke andar check karo
                if ($request->has('entity_type') && !empty($request->entity_type)) {
                    $query->orWhereJsonContains('applicable_entities', $request->entity_type);
                }
            })
            // Frontend UX ke liye ab saare naye rules API mein bhej rahe hain
            ->get([
                'id', 
                'document_code', 
                'name', 
                'description', 
                'is_mandatory', 
                'sides_required', 
                'allowed_formats', 
                'max_size_kb', 
                'sample_image_url'
            ]);

        return response()->json([
            'status' => 'success',
            'data' => $documents
        ], 200);
    }
}