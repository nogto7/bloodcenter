<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function resizeAndSave(
        $file,
        string $path,
        int $maxWidth = 1200,
        int $quality = 75
    ): string {
        // $filename = time().'_'.uniqid().'.jpg'; // JPG-ээр хадгалах

        // $image = $this->manager
        //     ->read($file->getRealPath())  // 👈 realPath заавал өгнө
        //     ->orient()                     // iPhone rotation автомат
        //     ->scaleDown($maxWidth)
        //     ->toJpeg($quality);

        // $fullPath = public_path($path.'/'.$filename);
        // $image->save($fullPath);

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $image = $this->manager
            ->read($file)
            ->scaleDown($maxWidth)   // 🔥 aspect ratio хадгална
            ->toJpeg($quality);      // 🔥 compress

        $fullPath = public_path($path . '/' . $filename);
        $image->save($fullPath);

        return $path . '/' . $filename;
    }
}
