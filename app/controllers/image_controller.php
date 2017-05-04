<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ImageController extends BaseController {
    
    public static function showImage($posterId) {
        
        $image = Image::findImageByPoster($posterId);
        Header('Content-type: '.$image->fileType);
        echo base64_decode($image->image);
        die();
    }
    
}

