<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Image extends BaseModel {
    
    public $posterId, $image, $fileType;
    
    function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function findImageByPoster($posterId){
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
    
}