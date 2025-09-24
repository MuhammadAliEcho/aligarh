<?php

namespace App\Model\Landloard;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $connection = 'mysql_landlord';
    // public $timestamps = false;

    protected $casts = [
        'active' => 'boolean',
    ];

    //need improvemnt  use native Tenents Setting



    /**
     * Define which columns are stored as dedicated columns vs the data JSON column
     * These should match the actual columns in your tenants table
     */


    //need improvemnt  

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'ninja_id',
            'active',
            'contact_name',
            'address',
            'contact_number',
        ];
    }
}
