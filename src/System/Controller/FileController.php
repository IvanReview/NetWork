<?php


namespace App\System\Controller;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileController
{
    protected array $imgArr = [];
    protected bool $uniqueFile = true;

    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }


}