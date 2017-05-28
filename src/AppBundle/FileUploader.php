<?php
namespace AppBundle;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class FileUploader {
    
    private $fileDir;
    
    public function __construct($fileDir) {
        $this->fileDir = $fileDir;
    }
    public function upload(UploadedFile $file) {
        
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->fileDir, $fileName);
        return $fileName;
    }
}