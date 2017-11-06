<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6
 * Time: 15:53
 */

namespace App\Handlers;


use Intervention\Image\Facades\Image;

class ImageUploadHandler
{
     protected $allowed_ext=['png','jpg','gif','jpeg'];

     public function save($file,$folder,$file_prefix,$max_width=false){
         //构建存储的文件夹规则
         //文件夹切割能让查找效率更高
         $folder_name="uploads/images/$folder/".date('Ym',time()). '/'.date("d", time()).'/';
         // 文件具体存储的物理路径
         $upload_path=public_path().'/'.$folder_name;
         // 获取文件的后缀名
         $extension=strtolower($file->getClientOriginalExtension())?:'png';
         // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
         $file_name=$file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;
         // 如果上传的不是图片将终止操作
         if (!in_array($extension,$this->allowed_ext))
         {
             return false;
         }
         $file->move($upload_path,$file_name);
         if ($max_width && $extension!='gif')
         {
             $this->reduseSize($upload_path . '/' . $file_name, $max_width);
         }
          return
          [
              'path'=>config('app.url')."/$folder_name/$file_name"
          ];
     }

     public function reduseSize($file_path,$max_width)
     {
         // 先实例化，传参是文件的磁盘物理路径
         $image= Image::make($file_path);
         // 进行大小调整的操作
         $image->resize($max_width,null,function ($constraint){
             // 设定宽度是 $max_width，高度等比例双方缩放
             $constraint->aspectRatio();
             // 防止裁图时图片尺寸变大
             $constraint->upsize();
         });
         // 对图片修改后进行保存
         $image->save();
     }
}