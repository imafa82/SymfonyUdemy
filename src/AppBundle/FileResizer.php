<?php



namespace AppBundle;
use Symfony\Component\Filesystem\Filesystem;
class FileResizer {
    private $fileDir;
    private $sizes;
    private $fs;
    
    public function __construct($fileDir, $sizes, Filesystem $fs) {
        $this->fileDir = $fileDir;
        $this->sizes = $sizes;
        $this->fs = $fs;
    }   
    
    public function resizeImage($fileName) {
        $name= strstr($fileName, '.', true);
        $extension= strstr($fileName, '.');
        $fullPath= $this->fileDir.'/'.$fileName;
        
        foreach ($this->sizes as $size){
            //Prende entrambe le dimensioni
            list($imgWidth, $imgHeight) =@getimagesize($fullPath);
            $newfilePath = $this->fileDir.'/'.$name.'_'.$size['name'].$extension;
            $scale= max(
                    $size['max_height'] / $imgWidth,
                    $size['max_width'] / $imgHeight
                    );
            if($scale >= 1){
                if($fullPath !== $newfilePath){
                    return $this->fs->copy($fullPath, $newfilePath);
                    //return copy($fullPath, $newfilePath);
                }
                continue;
            }
            
            //scala le immagini mantenendo le proporzioni
            $newWidth = $imgWidth * $scale;
            $newHeight = $imgHeight * $scale;
            
            $newImg = @imagecreatetruecolor($newWidth, $newHeight);
            switch (strtolower(substr(strrchr($extension, '.'), 1))){
                case 'jpg':
                case 'jpeg':
                    $srcImg = @imagecreatefromjpeg($fullPath);
                    $writeImage = 'imagejpeg';
                    $imageQuality = 75;
                    break;
                case 'gif':
                    @imagecolortransparent($newImg, @imagecolorallocate($newImg, 0, 0, 0));
                    $srcImg = @imagecreatefromgif($fullPath);
                    $writeImage = 'imagegif';
                    $imageQuality = null;
                    break;
                case 'png':
                    @imagecolortransparent($newImg, @imagecolorallocate($newImg, 0, 0, 0));
                    @imagealphablending($newImg, false);
                    @imagesavealpha($newImg, true);
                    $srcImg = @imagecreatefrompng($fullPath);
                    $writeImage = 'imagepng';
                    $imageQuality = 9;
                    break;
                default:
                    $srcImg = null;
            }
            imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $imgWidth, $imgHeight);
            $writeImage($newImg, $newfilePath, $imageQuality);
            
            //Libero la memoria
            @imagedestroy($srcImg);
            @imagedestroy($newImg);
        }
    }
}
