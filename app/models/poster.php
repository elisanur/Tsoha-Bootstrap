<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Poster extends BaseModel {

    public $id, $name, $publisher, $artist, $price, $location, $height, $width,
            $image, $sold, $publishername;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_artist', 'validate_height', 'validate_location', 'validate_name', 'validate_price', 'validate_width');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT Poster.id AS id, '
                . 'Poster.name AS name, Poster.publisher as publisher,'
                . 'Poster.artist AS artist, Poster.price as price, '
                . 'Poster.location AS location, Poster.height AS height,'
                . 'Poster.width AS width, Poster.image AS image, '
                . 'Poster.sold AS sold, Username.name AS publishername '
                . 'FROM Poster, Username '
                . 'WHERE Poster.publisher=Username.id');
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
                'publishername' => $row['publishername']
            ));
        }

        return $posters;
    }

    public static function allFromUser($publisher) {
        $query = DB::connection()->prepare('SELECT Poster.id AS id, '
                . 'Poster.name AS name, Poster.publisher as publisher,'
                . 'Poster.artist AS artist, Poster.price as price, '
                . 'Poster.location AS location, Poster.height AS height,'
                . 'Poster.width AS width, Poster.image AS image, '
                . 'Poster.sold AS sold, Username.name AS publishername '
                . 'FROM Poster, Username '
                . 'WHERE Poster.publisher=Username.id '
                . 'AND publisher = :publisher AND Poster.sold = FALSE');
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
                'publishername' => $row['publishername']
            ));
        }

        return $posters;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT Poster.id AS id, '
                . 'Poster.name AS name, Poster.publisher as publisher,'
                . 'Poster.artist AS artist, Poster.price as price, '
                . 'Poster.location AS location, Poster.height AS height,'
                . 'Poster.width AS width, Poster.image AS image, '
                . 'Poster.sold AS sold, Username.name AS publishername '
                . 'FROM Poster, Username '
                . 'WHERE Poster.publisher=Username.id '
                . 'AND Poster.id = :id LIMIT 1');
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
                'publishername' => $row['publishername']
            ));

            return $poster;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Poster '
                . '(name, publisher, artist, price, location, height, width) '
                . 'VALUES (:name, :publisher, :artist, :price, :location, '
                . ':height, :width) RETURNING id');
        $query->execute(array('name' => $this->name, 'publisher' => $this->publisher, 
            'artist' => $this->artist, 'price' => $this->price, 
            'location' => $this->location, 'height' => $this->height, 
            'width' => $this->width));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public static function savePosterCategory($posterId, $category){
        //tarkista ensin onko kategoria olemassa!!
        
        
        
        $query = DB::connection()->prepare('INSERT INTO PosterCategory (category, poster) '
                . 'VALUES (:category, :poster)');
        $query->execute(array('category' => $category, 'poster' => $posterId));
    }
    
    public function update(){
        $query = DB::connection()->prepare('UPDATE Poster SET name = :name, '
                . 'artist = :artist, price = :price, location = :location, '
                . 'height = :height, width = :width WHERE id = :id');
        $query->execute(array('name' => $this->name, 'artist' => $this->artist, 
            'price' => $this->price, 'location' => $this->location, 
            'height' => $this->height, 'width' => $this->width, 'id' => $this->id));
        
    }
    
    public function destroy(){
        $query = DB::connection()->prepare('DELETE FROM PosterCategory '
                . 'WHERE poster = :posterid');
        $query->execute(array('posterid' => $this->id));
        
        $query = DB::connection()->prepare('DELETE FROM Purchase WHERE poster = :posterid');
        $query->execute(array('posterid' => $this->id));
        
        $query = DB::connection()->prepare('DELETE FROM Poster WHERE id = :posterid');
        $query->execute(array('posterid' => $this->id));
    }
    
    public function validate_height(){
        return parent::validate_whole_number('Height', $this->height);
    }
    
    public function validate_width() {
        return parent::validate_whole_number('Width', $this->width);
    }

    public function validate_artist() {
        return parent::validate_string_length('Artist', $this->artist, 5);
    }
    
    public function validate_location(){
        return parent::validate_string_length('Location', $this->location, 2);
    }
    
    public function validate_name(){
        return parent::validate_string_length('Name', $this->name, 3);
    }
    
    public function validate_price(){
        return parent::validate_whole_number('Price', $this->price);
    }
}
