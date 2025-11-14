<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 


    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:150',
        'description' => 'nullable|string|max:1000',
        'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
        'duration' => 'nullable|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins 3 caractères.',
            'audio_file.mimes' => 'Le fichier audio doit être au format mp3, wav ou ogg.',
            'audio_file.max' => 'La taille maximale du fichier audio est de 10 Mo.',
        ];
    }
}
