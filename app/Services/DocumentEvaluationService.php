<?php

namespace App\Services;

class DocumentEvaluationService
{
    public function evaluate($masterDocs, $uploadedDocsGrouped)
    {
        $pending = [];
        $completed = [];
        $mandatoryPendingCount = 0;

        foreach ($masterDocs as $doc) {
            $docData = [
                'id' => $doc->id,
                'document_code' => $doc->document_code,
                'name' => $doc->name,
                'description' => $doc->description,
                'is_mandatory' => $doc->is_mandatory,
                'sides_required' => $doc->sides_required,
                'allowed_formats' => $doc->allowed_formats,
                'max_size_kb' => $doc->max_size_kb,
                'sample_image_url' => $doc->sample_image_url ? asset('storage/' . $doc->sample_image_url) : null,
            ];

            $uploadsForThisDoc = $uploadedDocsGrouped->get($doc->id, collect());
            $uploadedSides = $uploadsForThisDoc->pluck('document_side')->toArray();

            $isComplete = false;
            if ($doc->sides_required == 0 && (in_array('single', $uploadedSides) || in_array('front', $uploadedSides))) {
                $isComplete = true;
            } elseif ($doc->sides_required == 1 && in_array('front', $uploadedSides)) {
                $isComplete = true;
            } elseif ($doc->sides_required == 2 && in_array('front', $uploadedSides) && in_array('back', $uploadedSides)) {
                $isComplete = true;
            }

            $docData['uploaded_sides'] = $uploadedSides;
            $docData['uploaded_files'] = $uploadsForThisDoc->map(function ($f) {
                return [
                    'id' => $f->id,
                    'side' => $f->document_side,
                    'url' => asset('storage/' . $f->file_path),
                    'status' => $f->verification_status
                ];
            });

            if ($isComplete) {
                $completed[] = $docData;
            } else {
                $pending[] = $docData;
                if ($doc->is_mandatory) {
                    $mandatoryPendingCount++;
                }
            }
        }

        return [
            'is_kyc_complete' => ($mandatoryPendingCount === 0), // Profile & Company ke liye
            'is_ready_for_submission' => ($mandatoryPendingCount === 0), // Lead ke liye
            'pending_mandatory_count' => $mandatoryPendingCount,
            'pending_documents' => array_values($pending),
            'completed_documents' => array_values($completed),
        ];
    }
}