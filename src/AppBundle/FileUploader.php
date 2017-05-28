<?php
namespace AppBundle;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\FileResizer;
class FileUploader {
    
    private $fileDir;
    private $avatarResize;
    public function __construct($fileDir, FileResizer $avatarResize) {
        $this->fileDir = $fileDir;
        $this->avatarResize = $avatarResize;
    }
    public function upload(UploadedFile $file) {
        
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->fileDir, $fileName);
        $this->avatarResize->resizeImage($fileName);
        return $fileName;
    }
}