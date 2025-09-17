<?php

namespace App\Model\Landloard;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;


class Domain extends BaseDomain
{

    //need improvemnt  use native Tenents Setting

    protected $connection = 'mysql_landlord';

    protected $fillable = [
        'domain',
        'tenant_id',
        'config', 
    ];
}
