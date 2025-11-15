<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



 /**
     * @OA\Schema(
     *     schema="Podcast",
     *     type="object",
     *     title="Podcast",
     *     required={"id", "title", "description", "user_id"},
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="title", type="string"),
     *     @OA\Property(property="description", type="string"),
     *     @OA\Property(property="image", type="string", nullable=true),
     *     @OA\Property(property="user_id", type="integer")
     * )
     */
class Podcast extends Model
{
    use HasFactory;
     protected $fillable = [
        'title',
        'description',
        'image',
        'user_id'
      
    ];



    public function user()
{
    return $this->belongsTo(User::class);
}

public function episodes()
{
    return $this->hasMany(Episode::class);
}

}
