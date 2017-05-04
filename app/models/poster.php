<?php

/*
 */

class Poster extends BaseModel {

    public $id, $name, $publisher, $artist, $price, $location, $height, $width,
            $image, $sold, $publisherName, $categories, $publisherEmail;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_artist', 'validate_height',
            'validate_location', 'validate_name', 'validate_price',
            'validate_width');
    }

    public static function allUnsoldPosters() {
        $query = DB::connection()->prepare('SELECT p.id AS id, '
                . 'p.name AS name, p.publisher as publisher, '
                . 'p.artist AS artist, p.price as price, '
                . 'p.location AS location, p.height AS height, '
                . 'p.width AS width, p.image AS image, '
                . 'p.sold AS sold, u.name AS publishername '
                . 'FROM Poster p, Username u '
                . 'WHERE p.publisher=u.id AND p.sold = FALSE');
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
                'image' => Image::findImageByPoster($row['id']),
                'sold' => $row['sold'],
                'publisherName' => $row['publishername'],
                'categories' => Category::posterCategories($row['id'])
            ));
        }

        return $posters;
    }

    public static function allUnsoldPostersFromUser($publisher) {
        $query = DB::connection()->prepare('SELECT Poster.id AS id, '
                . 'Poster.name AS name, Poster.publisher as publisher,'
                . 'Poster.artist AS artist, Poster.price as price, '
                . 'Poster.location AS location, Poster.height AS height,'
                . 'Poster.width AS width, Poster.image AS image, Poster.filetype AS filetype, '
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
                'image' => Image::findImageByPoster($row['id']),
                'sold' => $row['sold'],
                'publisherName' => $row['publishername'],
                'categories' => Category::posterCategories($row['id'])
            ));
        }

        return $posters;
    }

    public static function allSoldPostersFromUser($publisher) {
        $query = DB::connection()->prepare('SELECT Poster.id AS id, '
                . 'Poster.name AS name, Poster.publisher as publisher,'
                . 'Poster.artist AS artist, Poster.price as price, '
                . 'Poster.location AS location, Poster.height AS height,'
                . 'Poster.width AS width, Poster.image AS image, Poster.filetype AS filetype, '
                . 'Poster.sold AS sold, Username.name AS publishername '
                . 'FROM Poster, Username '
                . 'WHERE Poster.publisher=Username.id '
                . 'AND publisher = :publisher AND Poster.sold = TRUE');
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
                'image' => Image::findImageByPoster($row['id']),
                'sold' => $row['sold'],
                'publisherName' => $row['publishername'],
                'categories' => Category::posterCategories($row['id'])
            ));
        }

        return $posters;
    }

    public static function find($id) {
        $query = DB::connection()->prepare(' SELECT p.id AS id, p.name AS name, p.publisher AS publisher, p.artist AS artist, p.price AS price, p.location AS location, p.height AS height, p.width AS width, p.image AS image, p.filetype AS filetype, p.sold AS sold, u.name AS publishername FROM Poster p LEFT JOIN Username u ON p.publisher=u.id LEFT JOIN PosterCategory pc ON pc.poster=p.id WHERE p.id = :id');
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
                'image' => Image::findImageByPoster($row['id']),
                'sold' => $row['sold'],
                'publisherName' => $row['publishername'],
                'publisherEmail' => User::find($row['publisher'])->email,
                'categories' => Category::posterCategories($row['id'])
            ));

            return $poster;
        }

        return null;
    }

    public static function findPostersByCategory($category) {
        $query = DB::connection()->prepare('SELECT p.id AS id, '
                . 'p.name AS name, p.publisher as publisher,'
                . 'p.artist AS artist, p.price as price, '
                . 'p.location AS location, p.height AS height,'
                . 'p.width AS width, p.image AS image, '
                . 'p.sold AS sold, u.name AS publishername '
                . 'FROM Poster p, PosterCategory pc, Username u '
                . 'WHERE p.id=pc.poster '
                . 'AND p.publisher = u.id AND p.sold=FALSE '
                . 'AND pc.category = :category');
        $query->execute(array('category' => $category));
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
                'image' => Image::findImageByPoster($row['id']),
                'sold' => $row['sold'],
                'publisherName' => $row['publishername'],
                'categories' => Category::posterCategories($row['id'])
            ));
        }

        return $posters;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Poster '
                . '(name, publisher, artist, price, location, height, width, image, filetype) '
                . 'VALUES (:name, :publisher, :artist, :price, :location, '
                . ':height, :width, :image, :filetype) RETURNING id');

        $query->execute(array('name' => $this->name, 'publisher' => $this->publisher,
            'artist' => $this->artist, 'price' => $this->price,
            'location' => $this->location, 'height' => $this->height,
            'width' => $this->width, 'image' => $this->image->image, 'filetype' => $this->image->fileType));

        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Poster SET name = :name, '
                . 'artist = :artist, price = :price, location = :location, '
                . 'height = :height, width = :width WHERE id = :id');

        $query->execute(array('name' => $this->name, 'artist' => $this->artist,
            'price' => $this->price, 'location' => $this->location,
            'height' => $this->height, 'width' => $this->width, 'id' => $this->id));
    }

    public static function markAsSold($id) {
        $query = DB::connection()->prepare('UPDATE Poster SET sold = TRUE WHERE id = :id');

        $query->execute(array('id' => $id));
    }

    public function destroy() {
        Category::destroyPosterCategoryByPosterId($this->id);

        $query = DB::connection()->prepare('DELETE FROM Purchase WHERE poster = :posterid');
        $query->execute(array('posterid' => $this->id));

        $query = DB::connection()->prepare('DELETE FROM Poster WHERE id = :posterid');
        $query->execute(array('posterid' => $this->id));
    }

    public function validate_height() {
        return parent::validate_whole_number('Height', $this->height);
    }

    public function validate_width() {
        return parent::validate_whole_number('Width', $this->width);
    }

    public function validate_artist() {
        $errors = array();
        
        $errors = array_merge($errors, parent::validate_string_length('Artist', $this->artist, 5));
        $errors = array_merge($errors, parent::validate_characters('Artist', $this->artist));
        
        return $errors;
    }

    public function validate_location() {
        $errors = array();
        
        $errors = array_merge($errors, parent::validate_string_length('Location', $this->location, 2));
        $errors = array_merge($errors, parent::validate_characters('Location', $this->location));
        
        return $errors;
    }

    public function validate_name() {
        $errors = array();
        
        $errors = array_merge($errors, parent::validate_string_length('Name', $this->name, 3));
        $errors = array_merge($errors, parent::validate_characters('Name', $this->name));
        
        return $errors;
    }

    public function validate_price() {
        return parent::validate_whole_number('Price', $this->price);
    }

    

}
