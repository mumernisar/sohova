<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    /**
     * Receive an (already enriched) lead from the n8n workflow.
     *
     * Uses updateOrCreate keyed on email so that retries from n8n
     * never create duplicate records — the endpoint is idempotent.
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $data = $request->validated();

        $lead = Lead::updateOrCreate(
            ['email' => $data['email']],
            $data + ['enrichment_status' => $data['enrichment_status'] ?? 'pending'],
        );

        return response()->json([
            'status'  => 'ok',
            'lead_id' => $lead->id,
            'created' => $lead->wasRecentlyCreated,
        ], $lead->wasRecentlyCreated ? 201 : 200);
    }
}
