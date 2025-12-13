<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Tenant;

class UpdatePolymorphicTypes extends Command
{
    protected $signature = 'tenant:update-polymorphic-types {--force : Run without confirmation}';

    protected $description = 'Update polymorphic type columns for all tenants';

    public function handle()
    {
        $this->info('=== Updating Polymorphic Types for All Tenants ===');
        $this->newLine();

        $mappings = [
            'App\\Student' => 'student',
            'App\\Model\\Student' => 'student',
            'App\\Teacher' => 'teacher',
            'App\\Model\\Teacher' => 'teacher',
            'App\\Employee' => 'employee',
            'App\\Model\\Employee' => 'employee',
            'App\\Guardian' => 'guardian',
            'App\\Model\\Guardian' => 'guardian',
        ];

        $polymorphicTables = [
            ['table' => 'attendance_leaves', 'column' => 'person_type'],
        ];

        $tenants = Tenant::pluck('id');

        if ($tenants->isEmpty()) {
            $this->warn('No tenants found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$tenants->count()} tenant(s)");
        $this->newLine();

        $grandTotal = 0;

        tenancy()->runForMultiple($tenants, function ($tenant) use (
            $mappings,
            $polymorphicTables,
            &$grandTotal
        ) {
            $this->line("<fg=cyan>━━━ Tenant: {$tenant->id} ━━━</>");

            $tenantTotal = 0;

            foreach ($polymorphicTables as $config) {
                foreach ($mappings as $old => $new) {
                    $count = DB::table($config['table'])
                        ->where($config['column'], $old)
                        ->count();

                    if ($count === 0) {
                        continue;
                    }

                    if ($this->option('force') ||
                        $this->confirm("Update {$count} rows in {$config['table']} from {$old} → {$new}?", true)
                    ) {
                        DB::table($config['table'])
                            ->where($config['column'], $old)
                            ->update([$config['column'] => $new]);

                        $this->line("  <fg=green>✓</> {$count} rows updated");
                        $tenantTotal += $count;
                    }
                }
            }

            $this->line("  Tenant total updated: {$tenantTotal}");
            $this->newLine();

            $grandTotal += $tenantTotal;
        });

        $this->info('=== Completed ===');
        $this->info("Total records updated across all tenants: {$grandTotal}");

        return Command::SUCCESS;
    }
}
