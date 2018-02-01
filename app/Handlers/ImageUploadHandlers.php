<?php
namespace App\Handlers;
use Intervention\Image\Facades\Image;

class ImageUploadHandlers
{
    protected $allow_ext = ['png','jpg','gif','jpeg'];

    public function save($file,$folder,$file_prefix,$max_width = null,$max_height = null)
    {

        $folder_name = 'uploads/images/' . $folder . '/' . date('Ym',time()) . '/' . date('d',time());


        $upload_path = public_path() . '/' . $folder_name;

        $ext = strtolower($file->getClientOriginalExtension()) ?: 'png';

        $filename = $file_prefix . '_' . md5(time()) . '.' . $ext;

        if (! in_array($ext,$this->allow_ext)){
            return false;
        }

        $file->move($upload_path,$filename);

        if ($max_width && $ext != 'gif'){

            $this->reduceSize($upload_path. '/' . $filename,$max_width,$max_height);
        }

        return [
            'path' => config('app.url') . '/' . $folder_name . '/' .$filename,
        ] ;

    }


    public function ave_base64_image($file,$folder,$file_prefix,$max_width = null,$max_height = null)
    {

    }

    public function reduceSize($file_path,$max_width,$max_height)
    {
        // 先实例化，参数是文件的磁盘物理路径
        $image = Image::make($file_path);

        //进行大小调整
        $image->resize($max_width,null,function ($constraint){

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止截图时候，尺寸变大
            $constraint->upsize();

        });
//       $image->resize($max_width,$max_height);

       $image->save();

    }
}