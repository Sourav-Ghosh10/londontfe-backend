<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OurClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ClientApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/clients",
     *      operationId="getClients",
     *      tags={"Clients"},
     *      summary="Get list of our clients",
     *      description="Returns list of active clients with logos.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="alt_text", type="string", example="Company Name"),
     *                  @OA\Property(property="logo", type="string", example="https://bucket.s3.amazonaws.com/clients/logo.jpg"),
     *                  @OA\Property(property="order", type="integer", example=1)
     *              ))
     *          )
     *      )
     * )
     */
    public function index()
    {
        $cacheKey = 'api_clients_v1';
        $cacheTtl = 3600; // 1 hour

        // Retrieve from Redis Cache
        $clients = Cache::store('redis')->remember($cacheKey, $cacheTtl, function () {
            return OurClient::where('status', '1')
                ->orderBy('order', 'asc')
                ->get()
                ->map(function ($client) {
                    $clientArr = $client->toArray();
                    
                    if (!empty($clientArr['logo']) && !filter_var($clientArr['logo'], FILTER_VALIDATE_URL)) {
                        $imagePath = $clientArr['logo'];
                        // If it's just a filename from legacy data, assume 'clients/' folder
                        if (strpos($imagePath, '/') === false) {
                            $imagePath = 'clients/' . $imagePath;
                        }
                        $clientArr['logo'] = Storage::disk('s3')->url($imagePath);
                    } else if (!empty($clientArr['logo'])) {
                        // In case it's already a full URL
                        $clientArr['logo'] = $clientArr['logo'];
                    }

                    // Return only needed fields
                    return [
                        'id' => $clientArr['id'],
                        'alt_text' => $clientArr['alt_text'],
                        'logo' => $clientArr['logo'],
                        'order' => $clientArr['order']
                    ];
                })
                ->toArray();
        });

        return response()->json([
            'success' => true,
            'data' => $clients
        ])->header('Cache-Control', "public, max-age={$cacheTtl}");
    }
}
