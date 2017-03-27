<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Poster extends BaseModel {

    public $id, $name, $publisher, $artist, $price, $location, $heigth, $width,
            $image, $sold;

    public function __construct($attributes = null) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Poster');
        $query->execute();
        $rows = $query->fetchAll();
        $posters = array();

        foreach ($rows as $row) {
            $posters[] = new Poster(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'publisher' => $row['publisher'],
                'artist' => $row['artist'],
                'price' => $row['price'],
                'location' => $row['location'],
                'height' => $row['height'],
                'width' => $row['width'],
                'image' => $row['image'],
                'sold' => $row['sold'],
            ));
        }

        return $posters;
    }
    
    public static function allFromUser($publisher) {
        $query = DB::connection()->prepare('SELECT * FROM Poster WHERE publisher = :publisher');
        $query->execute();
        $rows = $query->fetchAll();
        $posters = array();

        foreach ($rows as $row) {
            $posters[] = new Poster(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'publisher' => $row['publisher'],
                'artist' => $row['artist'],
                'price' => $row['price'],
                'location' => $row['location'],
                'height' => $row['height'],
                'width' => $row['width'],
                'image' => $row['image'],
                'sold' => $row['sold'],
            ));
        }

        return $posters;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Poster WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $poster = new Poster(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'publisher' => $row['publisher'],
                'artist' => $row['artist'],
                'price' => $row['price'],
                'location' => $row['location'],
                'height' => $row['height'],
                'width' => $row['width'],
                'image' => $row['image'],
                'sold' => $row['sold'],
            ));

            return $poster;
        }

        return null;
    }

}
