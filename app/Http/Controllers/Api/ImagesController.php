<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandlers;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Transformers\ImageTransformer;

class ImagesController extends Controller
{
    public function store(ImageRequest $request,ImageUploadHandlers $imageUploadHandlers)
    {
        $user = $this->user();

        $size = $request->type == 'avatar' ? 362 : 1024;

        $result = $imageUploadHandlers->save($request->image,str_plural($request->type),$user->id,$size);

        $image = new Image();
        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();

        return $this->response->item($image,new ImageTransformer());
    }
}
