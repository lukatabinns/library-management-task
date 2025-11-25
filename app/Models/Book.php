<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title','author','genre','isbn','published_at','copies_total','copies_available'
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    // Ensure copies_available never exceeds copies_total on model events
    protected static function booted()
    {
        static::saving(function (Book $book) {
            if ($book->copies_available > $book->copies_total) {
                $book->copies_available = $book->copies_total;
            }
        });
    }
}

