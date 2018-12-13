<?php

namespace SeanDowney\BackpackGalleryCrud\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;

class ImageController extends Controller
{
    protected $server;

    public function __construct(Filesystem $filesystem)
    {
        $this->server = ServerFactory::create([
            'response'          => new LaravelResponseFactory(app('request')),
            // 'source' => $filesystem->getDriver(),
            'source'            => app('filesystem')->disk('local')->getDriver(),
            'cache'             => $filesystem->getDriver(),
            'cache_path_prefix' => '.cache',
            'base_url'          => 'image',
        ]);
    }


    public function show($path)
    {
        return $this->server->getImageResponse($path, request()->all());
    }

    public static function base64($path, $params = ['w' => 15, 'h' => 10, 'fit' => 'crop'])
    {
        $filesystem = app()->make('Illuminate\Contracts\Filesystem\Filesystem');
        $disk = config('seandowney.gallerycrud.disk');

        $server = ServerFactory::create([
            'response'          => new LaravelResponseFactory(app('request')),
            // 'source' => $filesystem->getDriver(),
            'source'            => app('filesystem')->disk('local')->getDriver(),
            'cache'             => $filesystem->getDriver(),
            'cache_path_prefix' => '.cache',
            'base_url'          => 'image',
        ]);
        return $server->getImageAsBase64($disk . '/' . $path, $params);
    }
}