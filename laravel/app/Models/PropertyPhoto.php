<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class PropertyPhoto extends Model
{
    use HasFactory;

    public $fillable = [
        'image_url',
        'image_url_2x',
        'thumb_url',
        'thumb_2x_url',
    ];

    public function listing() {
        return $this->belongsTo('App\Models\Listing', 'key');
    }

    /**
     * Delete the photo's files on disk and the row itself.
     * Missing files are ignored; throws if the row cannot be deleted.
     */
    public function deleteWithFiles() {
        foreach (['image_url', 'image_2x_url', 'thumb_url', 'thumb_2x_url'] as $field) {
            $path = $this->{$field};
            if ($path && File::exists(public_path($path))) {
                File::delete(public_path($path));
            }
        }
        $this->delete();
    }
}