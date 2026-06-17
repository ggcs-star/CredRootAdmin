<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentMaster;
use App\Models\LoanType;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function getLoanTypes()
    {
        $loanTypes = LoanType::where('status', 1)->get(['id', 'name', 'description']);

        return response()->json([
            'status' => 'success',
            'data' => $loanTypes
        ], 200);
    }


    public function getRequiredDocuments(Request $request)
    {

        $request->validate([
            'entity_type' => 'nullable|string',
            'stage' => 'required|in:pre_qualification,final_application'
        ]);


        $documents = DocumentMaster::where('status', 1)
            ->where('collection_stage', $request->stage)
            ->where(function ($query) use ($request) {
                $query->whereNull('entity_type');
                if ($request->has('entity_type')) {
                    $query->orWhere('entity_type', $request->entity_type);
                }
            })
            ->get(['id', 'name', 'is_mandatory']);

        return response()->json([
            'status' => 'success',
            'data' => $documents
        ], 200);
    }
}