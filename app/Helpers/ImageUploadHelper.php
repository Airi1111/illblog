<?php

namespace App\Helpers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
                $imageUrls[] = $uploadedFileUrl;
            } catch (\Exception $e) {
                throw new \Exception('Failed to upload image: ' . $e->getMessage());
            }
        }
        return $imageUrls;
    }
}
