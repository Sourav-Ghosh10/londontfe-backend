<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OurClient;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = OurClient::orderBy('order', 'asc')->get();
        return view('admin.website.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.website.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'alt_text' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'required|string',
            'logo' => 'required|image|max:10240', // 10MB max
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->storePublicly('clients', 's3');
            
            if (!$logoPath) {
                return response()->json(['success' => false, 'error' => 'Failed to upload logo to S3.'], 500);
            }
        }

        $client = OurClient::create([
            'alt_text' => $request->alt_text,
            'order' => $request->order ?? 0,
            'status' => $request->status === 'Active' ? 1 : 0,
            'logo' => $logoPath,
        ]);

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_clients_v1');

        return response()->json(['success' => true, 'client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $client = OurClient::findOrFail($id);
        return view('admin.website.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $client = OurClient::findOrFail($id);

        $request->validate([
            'alt_text' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'required|string',
            'logo' => 'nullable|image|max:10240', // Logo is optional on update
        ]);

        $logoPath = $client->logo;
        if ($request->hasFile('logo')) {
            if ($client->logo && !str_starts_with($client->logo, 'http')) {
                Storage::disk('s3')->delete($client->logo);
            }
            $logoPath = $request->file('logo')->storePublicly('clients', 's3');
            if (!$logoPath) {
                return response()->json(['success' => false, 'error' => 'Failed to upload logo to S3.'], 500);
            }
        }

        $client->update([
            'alt_text' => $request->alt_text,
            'order' => $request->order ?? 0,
            'status' => $request->status === 'Active' ? 1 : 0,
            'logo' => $logoPath,
        ]);

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_clients_v1');

        return response()->json(['success' => true, 'client' => $client]);
    }

    /**
     * Toggle the active status of the client.
     */
    public function toggleStatus($id)
    {
        $client = OurClient::findOrFail($id);
        $client->status = $client->status == 1 ? 0 : 1;
        $client->save();

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_clients_v1');

        return response()->json([
            'success' => true,
            'status' => $client->status == 1 ? 'Active' : 'Inactive'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = OurClient::findOrFail($id);
        
        if ($client->logo && !str_starts_with($client->logo, 'http')) {
            Storage::disk('s3')->delete($client->logo);
        }
        
        $client->delete();

        \Illuminate\Support\Facades\Cache::store('redis')->forget('api_clients_v1');

        return response()->json(['success' => true]);
    }
}
