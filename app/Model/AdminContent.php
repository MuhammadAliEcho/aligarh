<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminContent extends Model
{
	protected $casts = [
				'func'		=>	'object',
				'post_func'	=>	'object',
				'options'	=>	'object',
				];


	/**
	* Get the child content that owns the content.
	*/
	public function child_content() {
		return $this->hasMany('App\Model\AdminContent', 'p_id');
	}

	// Set Navigation Scope
	public function scopeNavigations($query) {
		return $query->where('type', 'parent-content')->orWhere('type', 'parent-label')->orderBy('order_no')->get();
	}

}
