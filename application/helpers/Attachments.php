<?php
/**
 * @class Sch_Controller_Action_Helper_Attachments
 */
class Helper_Attachments extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @param Entities\Media $attachment
     * @param null $attachmentsPath
     * @param bool $useAutoname
     * @param bool $autoThumbnail
     * @return array
     */
    public function upload(\Entities\Media $attachment, $attachmentsPath = null, $useAutoname = false, $autoThumbnail = false)
    {
        $files = basename($attachment->getPath());
        $uploadPath = dirname($attachment->getPath());
        $attachments = array();
        //Создаем фильтр для переименования файлов
        $renameFilter = new Sch_Filter_File_Rename($attachmentsPath);
        $translitFilter = new Sch_Filter_Translit();
        foreach ((array)$files as $attachmentFile) {
            //Переименовываем файл вложения...
            $newAttachmentFile = (!$useAutoname)?$translitFilter->filter($attachmentFile):'';
            $attachmentFile = $renameFilter->setFile(
                array(
                     'target' => $attachmentsPath . DIRECTORY_SEPARATOR . $newAttachmentFile,
                     'overwrite' => true
                )
            )->filter($uploadPath . DIRECTORY_SEPARATOR . $attachmentFile);
            //Задаем имя файла вложения...
            $attachment->setPath($attachmentFile);
            //Если картинка, то нужно сделать уменьшенную копию
            if (in_array(strtolower(pathinfo($attachmentFile, PATHINFO_EXTENSION)), array('gif', 'jpeg', 'jpg', 'png'))) {
                if ($autoThumbnail) {
                    $this->thumbnail(array($attachment), $attachmentsPath);
                }
            }
            $attachments[] = $attachment;
        }
        return $attachments;
    }

    /**
     * @param $photos
     * @param $source
     * @param null $target
     * @param array $size
     * @return array
     * @throws Zend_Controller_Action_Exception
     */
    public function thumbnail($photos, $source, $target = null, $size = array())
    {
        if (!is_array($photos)) {
            $photos = array($photos);
        }
        if (!is_dir($source)) {
            throw new Zend_Controller_Action_Exception('Path not found', 500);
        }
        if (!is_dir($target)) {
            if (!$target) {
                $result = @mkdir($target, 0777, true);
            }
            if (!isset($result) || !$result) {
                $target = $source;
            }
        }
        $thumbnailtFilenameFilter = new Sch_Filter_ThumbFilename();
        $files = array();
        foreach ($photos as /** @var $photo \Entities\Image */&$photo) {
            $thumbnails = array();
            if (($thumbSettings = \Entities\Media::getSizes())) {
                if (empty($size) || !is_array($size)) {
                    $size = $thumbSettings;
                }
                foreach ($size as $type => $setting) {
                    if (is_string($setting)) {
                        if (!array_key_exists($setting, $thumbSettings)) {
                            continue;
                        }
                        $setting = $thumbSettings[$setting];
                    }
                    $thumbnailtFilenameFilter->setSuffix($type);

                    $filename = basename($photo->getPath());
                    $thumbFile = $thumbnailtFilenameFilter->filter($filename);
                    $sourcePath = $source . DIRECTORY_SEPARATOR . $filename;
                    $targetPath = $target . DIRECTORY_SEPARATOR . $thumbFile;

                    if ($targetPath != $sourcePath) {
                        if (file_exists($targetPath)) {
                            @unlink($targetPath);
                        }
                        @copy($sourcePath, $targetPath);
                    }

                    $thumbnailFilter = new ZFEngine_Filter_File_ImageResize($setting);
                    $thumbnails[] = $thumbnailFilter->filter($targetPath);
                }
            }

            $files[] = array(
                'file' => $photo,
                'thumbnails' => $thumbnails
            );
        }

        return $files;
    }

    /**
     * @param $path
     * @throws Zend_Controller_Action_Exception
     */
    public function recursiveClearFolder($path)
    {
        if (!is_dir($path)) {
            throw new Zend_Controller_Action_Exception('Path not found', 505);
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );
        set_error_handler(function($code, $message) {
            restore_error_handler();
            throw new Zend_Controller_Action_Exception($message, $code);
        });
        foreach ($iterator as /** @var $file SplFileInfo */$file) {
            unlink($file->getPathname());
        }
        rmdir($path);
        restore_error_handler();
    }

}
