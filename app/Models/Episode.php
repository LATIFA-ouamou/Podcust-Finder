<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Episode",
 *     type="object",
 *     title="Episode",
 *     required={"id", "title", "description", "podcast_id"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="audio", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", nullable=true),
 *     @OA\Property(property="podcast_id", type="integer")
 * )
 */

class Episode extends Model
{
    use HasFactory;
     protected $fillable = [
        'title',
        'description',
        'audio_file',
        'duration',
        'podcast_id',
    ];


    public function podcast()
{
    
    return $this->belongsTo(Podcast::class);


}

}

            