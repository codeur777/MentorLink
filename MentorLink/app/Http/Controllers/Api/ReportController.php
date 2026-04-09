<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * @OA\Post(path="/api/reports", summary="Signaler un utilisateur", tags={"Signalements"}, security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"reported_id","reason"},
     *     @OA\Property(property="reported_id", type="integer"),
     *     @OA\Property(property="reason", type="string"),
     *     @OA\Property(property="description", type="string")
     *   )),
     *   @OA\Response(response=201, description="Signalement enregistré"),
     *   @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(StoreReportRequest $request): JsonResponse
    {
        $report = Report::create([
            'reporter_id' => $request->user()->id,
            'reported_id' => $request->reported_id,
            'reason'      => $request->reason,
            'description' => $request->description,
        ]);

        return response()->json($report, 201);
    }

    /**
     * @OA\Get(path="/api/admin/reports", summary="[Admin] Liste des signalements", tags={"Signalements","Admin"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"en_attente","traite","rejete"})),
     *   @OA\Response(response=200, description="Liste des signalements")
     * )
     */
    public function index(): JsonResponse
    {
        $reports = Report::with(['reporter', 'reported'])
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->latest()
            ->paginate(15);

        return response()->json($reports);
    }

    /**
     * @OA\Put(path="/api/admin/reports/{id}", summary="[Admin] Traiter un signalement", tags={"Signalements","Admin"}, security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"status"},
     *     @OA\Property(property="status", type="string", enum={"traite","rejete"})
     *   )),
     *   @OA\Response(response=200, description="Signalement mis à jour")
     * )
     */
    public function update(Report $report): JsonResponse
    {
        request()->validate(['status' => 'required|in:traite,rejete']);

        $report->update(['status' => request('status')]);

        return response()->json($report);
    }
}
