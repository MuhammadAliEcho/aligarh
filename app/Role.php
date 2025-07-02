<?php

namespace App;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\Auth;

class Role extends SpatieRole {

	protected static function boot()
	{

		parent::boot();

		static::creating(function ($model) {
			$model->created_by = Auth::user()->id;
		});

		static::updating(function ($model) {
			$model->updated_by  =   Auth::user()->id;
		});

	}

	// public function User()
	// {
	// 	return 	$this->belongsTo(User::class, 'created_by');
	// }

	public function scopeNotDeveloper($query){
		return $query->whereKeyNot(1);
	}
}