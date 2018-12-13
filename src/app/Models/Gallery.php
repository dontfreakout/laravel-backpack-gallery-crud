<?php

namespace SeanDowney\BackpackGalleryCrud\app\Models;

use Backpack\CRUD\CrudTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Gallery extends Model
{
    use CrudTrait;
    use Sluggable, SluggableScopeHelpers;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'galleries';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'title', 'slug', 'body', 'images', 'captions', 'status',
    ];
    protected $casts = [
        'images' => 'array',
        'captions' => 'array',
    ];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug_or_title',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Return the URL to the post.
     *
     * @return string
     */
    public function url()
    {
        return route('view-gallery', $this->slug);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    // The slug is created automatically from the "name" field if no slug exists.
    public function getSlugOrTitleAttribute()
    {
        if ($this->slug != '') {
            return $this->slug;
        }

        return $this->title;
    }


    public function getImageItemsAttribute()
    {
        $disk = config('seandowney.gallerycrud.disk');
        $files = Storage::disk($disk)->files($this->slug);

        $slug = $this->slug;
        $files = array_map(function($value) use ($slug) {
            return str_replace($slug.'/', '', $value);
        }, $files);

        $image_items = [];
        if (isset($this->images) && !empty($this->images)) {
            foreach ($this->images as $file => $value) {
                // check if the file is actually in the gallery directory
                if (!in_array($file, $files)) {
                    continue;
                }

                $image_items[$file] = [
                    'image'      => $file,
                    'image_path' => $this->images[$file]['image_path'],
                    'live'       => isset($this->images[$file]) ? $this->images[$file]['live'] : -1,
                    'width'      => $this->images[$file]['width'],
                    'height'     => $this->images[$file]['height'],
                    'caption'    => isset($this->captions[$file]) ? $this->captions[$file] : '',
                ];
            }
        }

        // add any new files to the end of the list
        foreach ($files as $file) {
            $file_path = $this->slug.'/'.$file;
            $size_data = getimagesize(storage_path('app/'.$disk.'/'.$file_path));
            $image_items[$file] = [
                'image'      => $file,
                'image_path' => $file_path,
                'live'       => isset($this->images[$file]) ? $this->images[$file]['live'] : '',
                'width'      => $size_data[0],
                'height'     => $size_data[1],
                'caption'    => isset($this->captions[$file]) ? $this->captions[$file] : '',
            ];
        }

        return $image_items;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImagesAttribute($value)
    {
        $disk = config('seandowney.gallerycrud.disk');
        $attribute_name = "images";

        $files = Storage::disk($disk)->allFiles($this->slug);
        $image_items = [];

        // order the file as we want
        foreach ($value as $file => $live) {
            $image_items[$file]['live'] = $live;
        }

        foreach ($files as $key => $file_path) {
            $file = str_replace($this->slug.'/', '', $file_path);
            $size_data = getimagesize(storage_path('app/'.$disk.'/'.$file_path));
            $image_items[$file] = [
                'image' => $file,
                'image_path' => $file_path,
                'live' => isset($value[$file]) ? $value[$file] : 0,
                'width' => $size_data[0],
                'height' => $size_data[1],
            ];
        }

        $this->attributes[$attribute_name] = json_encode($image_items);
    }

}
