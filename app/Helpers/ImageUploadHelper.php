<?php

namespace App\Helpers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class ImageUploadHelper
{
    

    public static function uploadImages($images)
    {
        $imageUrls = [];
        foreach ($images as $image) {
            try {
                $uploadedFileUrl = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'dgougzdd8',
                    'transformation' => [
                        'width' => 800,
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ])->getSecurePath();
    
                // デバッグ用: ログにアップロードされた画像のURLを出力
                Log::info('Uploaded Image URL:', ['url' => $uploadedFileUrl]);
    
                $imageUrls[] = $uploadedFileUrl;
            } catch (\Exception $e) {
                Log::error('Failed to upload image:', ['error' => $e->getMessage()]);
                throw new \Exception('Failed to upload image: ' . $e->getMessage());
            }
        }
        return $imageUrls;
    }

}
