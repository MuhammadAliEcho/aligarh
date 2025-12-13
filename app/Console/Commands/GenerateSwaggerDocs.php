<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateSwaggerDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swagger:generate {--force : Force regeneration of docs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Swagger/OpenAPI documentation to public directory';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating Swagger documentation...');

        try {
            // Generate the docs using l5-swagger from annotations
            $this->call('l5-swagger:generate');
            
            $outputDir = storage_path('api-docs');
            $publicDir = public_path('api-docs');

            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }

            $jsonPath = $outputDir . '/api-docs.json';
            $publicJsonPath = $publicDir . '/api-docs.json';
            
            // Check if api-docs.json was generated
            if (!file_exists($jsonPath)) {
                $this->error('Swagger JSON not found at: ' . $jsonPath);
                $this->line('Please ensure annotations are properly configured in app/Http/Controllers/Api/Docs/');
                return 1;
            }

            // Copy to public directory for web access
            copy($jsonPath, $publicJsonPath);

            $this->info('✓ Swagger documentation generated successfully!');
            $this->info('  Generated: ' . $jsonPath);
            $this->info('  Published: ' . $publicJsonPath);
            $this->info('✓ Access at: /api/documentation');

            return 0;
            $this->line('Access at: ' . config('app.url') . '/api/documentation');

            return 0;
        } catch (\Exception $e) {
            $this->error('Error publishing documentation: ' . $e->getMessage());
            return 1;
        }
    }
}
