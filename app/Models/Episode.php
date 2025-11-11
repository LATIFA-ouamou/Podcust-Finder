<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    use HasFactory;
     protected $fillable = [
        'title',
        'description',
        'audio_file',
        'duration'
    ];


    public function podcast()
{
    
    return $this->belongsTo(Podcast::class);


}

}

            