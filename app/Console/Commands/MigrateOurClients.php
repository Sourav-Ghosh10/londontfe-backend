<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OurClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class MigrateOurClients extends Command
{
    protected $signature = 'migrate:our-clients';
    protected $description = 'Migrate client images from live site to S3 and database';

    public function handle()
    {
        $clients = [
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part1.png', 'alt' => 'foundation wind energy icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part2.png', 'alt' => 'petronas icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part4.png', 'alt' => 'ministry of finance icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part5.png', 'alt' => 'ministry of energy icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part6.png', 'alt' => 'indonesia financial services authority icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part7.png', 'alt' => 'federal mortgage bank of nigeria icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part8.png', 'alt' => 'epexspot icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part9.png', 'alt' => 'european central bank icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part10.png', 'alt' => 'saudi aramco icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part11.png', 'alt' => 'icrc icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part12.png', 'alt' => 'undp banner'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part13.png', 'alt' => 'public investment fund icon'],
            ['url' => 'https://www.londontfe.com//admin/assets/uploads/part14.png', 'alt' => 'technology and security ecosystem icon'],
        ];

        OurClient::truncate();

        foreach ($clients as $index => $clientData) {
            $this->info("Downloading " . $clientData['url']);
            try {
                $response = Http::timeout(10)->get($clientData['url']);
                if ($response->successful()) {
                    $filename = 'clients/' . basename(parse_url($clientData['url'], PHP_URL_PATH));
                    Storage::disk('s3')->put($filename, $response->body(), 'public');
                    
                    OurClient::create([
                        'logo' => $filename,
                        'alt_text' => $clientData['alt'],
                        'order' => $index + 1,
                        'status' => 1
                    ]);
                    $this->info("Saved {$filename}");
                } else {
                    $this->error("Failed to download {$clientData['url']}");
                }
            } catch (\Exception $e) {
                $this->error("Error on {$clientData['url']}: " . $e->getMessage());
            }
        }

        $this->info("Client migration complete!");
    }
}
