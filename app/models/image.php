<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Image extends BaseModel {

    public $posterId, $image, $fileType, $filePath;

    function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_fileType', 'validate_image', 'validate_size');
    }

    public static function findImageByPoster($posterId) {
        $query = DB::connection()->prepare('SELECT Poster.image, Poster.filetype '
                . 'FROM Poster WHERE Poster.id = :poster ');
        $query->execute(array('poster' => $posterId));
        $row = $query->fetch();

        if ($row) {
            $image = new Image(array(
                'posterId' => $posterId,
                'image' => $row['image'],
                'fileType' => $row['filetype'],
            ));

            return $image;
        }

        return null;
    }

    public function validate_fileType() {
        $errors = array();

        $fileTypes = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/bmp');

        if (!in_array($this->fileType, $fileTypes)) {
            $errors[] = "Image upload failed, file format not accepted. Allowed formats: " . implode(', ', $fileTypes);
        }

        return $errors;
    }

    public function validate_image() {
        $errors = array();
        $check = getimagesize($this->filePath);
        
        if ($check == FALSE) {
            $errors[] = 'Attachement is not an image';
        }
        
        return $errors;
    }

    public function validate_size() {
        $errors = array();
        $check = filesize($this->filePath);
        $maxsize = 2;
        if ($check > $maxsize*1000000 ) {
            $errors[] = 'Attachement size is too big, image maximum size is '.$maxsize.' MB';
        }
        
        return $errors;
    }
}
