<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Poster extends BaseModel {

    public $id, $name, $publisherid, $artist, $price, $location, $height, $width,
            $image, $sold, $publishername;

    public function __construct($attributes = null) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Poster p, User u WHERE p.publisher=u.id');
        $query->execute();
        $rows = $query->fetchAll();
        $posters = array();

        foreach ($rows as $row) {
            $posters[] = new Poster(array(
                'id' => $row['id'],
                'name' => $row['p.name'],
                'publisher' => $row['publisher'],
                'artist' => $row['artist'],
                'price' => $row['price'],
                'location' => $row['location'],
                'height' => $row['height'],
                'width' => $row['width'],
                'image' => $row['image'],
                'sold' => $row['sold'],
                'publishername' => $row['u.name']
            ));
        }

        return $posters;
    }

    public static function allFromUser($publisher) {
        $query = DB::connection()->prepare('SELECT * FROM Poster WHERE publisher = :publisher');
        $query->execute(array('publisher' => $publisher));
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

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Poster (name, publisher, artist, price, location, height, width) VALUES (:name, :publisher, :artist, :price, :location, :height, :width) RETURNING id');
        $query->execute(array('name' => $this->name, 'publisher' => $this->publisher, 'artist' => $this->artist, 'price' => $this->price, 'location' => $this->location, 'height' => $this->height, 'width' => $this->width));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    

}
