<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Contact extends Pivot
{
    public $timestamps = false;

    protected $table = 'contacts';

    //################### Relations ####################

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registeredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mobile_number', 'mobile_number');
    }
}
