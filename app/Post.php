<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable; 
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;

    // Decimos a laravel que acepte datos de forma masiva
    protected $fillable = [
        'title', 'body', 'iframe', 'image', 'user_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     * Toma el campo titulo y lo convierte en slug
     * @return array
    */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => 'true', 
                
            ]
        ];
    }

    // Se hace la relacion de un usuario puede tener varios posts
    public function user()
    {
        return $this->belongsto(User::class);
    }

    // Extracto hasta 140 carácteres
    public function getGetExcerptAttribute()
    {
        return substr($this->body, 0, 140);
    }

    // Para mostrar la imagen a traves de enlace simbolico
    public function getGetImageAttribute()
    {
        if($this->image)
            return url("storage/$this->image");
    }



}
