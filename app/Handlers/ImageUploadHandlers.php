<?php

namespace App\Handlers;

use Intervention\Image\Facades\Image;

class ImageUploadHandlers
{
    protected $allow_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save($file, $folder, $file_prefix, $max_width = null, $max_height = null)
    {

        $folder_name = 'uploads/images/' . $folder . '/' . date('Ym', time()) . '/' . date('d', time());


        $upload_path = public_path() . '/' . $folder_name;

        $ext = strtolower($file->getClientOriginalExtension()) ?: 'png';

        $filename = $file_prefix . '_' . md5(time()) . '.' . $ext;

        if (!in_array($ext, $this->allow_ext)) {
            return false;
        }

        $file->move($upload_path, $filename);

        if ($max_width && $ext != 'gif') {

            $this->reduceSize($upload_path . '/' . $filename, $max_width, $max_height);
        }

        return [
            'path' => config('app.url') . '/' . $folder_name . '/' . $filename,
        ];

    }


    public function save_base64_image($file, $folder, $file_prefix, $max_width = null, $max_height = null)
    {
        $file_ext = 'jpg';

        $folder_name = sprintf("uploads/images/%s/%s/%s/", $folder, date('Ym', time()), date('d', time()));

        $upload_path = public_path() . '/' . $folder_name;

        $filename = $file_prefix . '_' . md5(time()) . '.' . $file_ext;

        if (stripos($file, 'data:image/jpeg;base64,') === 0) {
            $image = base64_decode(str_replace('data:image/jpeg;base64,', '', $file));
        } elseif (stripos($file, 'data:image/png;base64,') === 0) {
            $image = base64_decode(str_replace('data:image/png;base64,', '', $file));
        } else {
            return ['error' => '文件格式错误'];
        }

        // 创建文件夹
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }


        try {

            file_put_contents($upload_path . $filename, $image);

            // 对照片进行裁剪
            if ($max_width) {

                $this->reduceSize($upload_path . $filename, $max_width, $max_height);
            }

            // 0 代表上传成功
            return [
                'status' => 0,
                'path' => url($folder_name . $filename),
            ];

        } catch (\Exception $e) {
            \Log::error('上传头像失败');
            return [
                'error' => '写入文件失败，可能没有权限'
            ];
        }

    }

    public function reduceSize($file_path, $max_width, $max_height)
    {
        // 先实例化，参数是文件的磁盘物理路径
        $image = Image::make($file_path);

        //进行大小调整
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止截图时候，尺寸变大
            $constraint->upsize();

        });
//       $image->resize($max_width,$max_height);

        $image->save();

    }
}