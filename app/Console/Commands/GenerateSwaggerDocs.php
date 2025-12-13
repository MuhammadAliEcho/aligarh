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
        $this->info('Publishing Swagger documentation...');

        try {
            $outputDir = storage_path('api-docs');
            $publicDir = public_path('api-docs');

            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }

            $jsonPath = $outputDir . '/swagger.json';
            $publicJsonPath = $publicDir . '/swagger.json';
            
            // Check if swagger.json exists
            if (!file_exists($jsonPath)) {
                $this->error('Swagger JSON not found at: ' . $jsonPath);
                $this->line('Please ensure swagger.json exists in storage/api-docs/');
                return 1;
            }

            // Copy to public directory for web access
            copy($jsonPath, $publicJsonPath);
            
            $this->info("✓ Swagger documentation published");
            $this->line("  Storage: $jsonPath");
            $this->line("  Public:  $publicJsonPath");
            $this->info('✓ Documentation ready!');
            $this->line('Access at: ' . config('app.url') . '/api/documentation');

            return 0;
        } catch (\Exception $e) {
            $this->error('Error publishing documentation: ' . $e->getMessage());
            return 1;
        }
    }
}
