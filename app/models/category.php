<?php

class Category extends BaseModel {

    public $name;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Category');
        $query->execute();
        $rows = $query->fetchAll();
        $category = array();
        foreach ($rows as $row) {
            $category[] = new Category(array(
                'name' => $row['name']
            ));
        }
        return $category;
    }

    public static function find($name) {
        $query = DB::connection()->prepare('SELECT * FROM Category WHERE name = :name LIMIT 1');
        $query->execute(array('name' => $name));
        $row = $query->fetch();
        $category = array();
        if ($row) {
            $category = new Category(array(
                'name' => $row['name']
            ));
        }
        return $category;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Category (name) VALUES (:name) RETURNING name');
        $query->execute(array('name' => $this->name));
        $row = $query->fetch();
        $this->name = $row['name'];
    }

    public static function posterCategories($posterid) {
        $query = DB::connection()->prepare('SELECT * FROM PosterCategory WHERE poster = :poster');
        $query->execute(array('poster' => $posterid));
        $rows = $query->fetchAll();
        $categories = array();

        foreach ($rows as $row) {
            $categories[] = new Category(array(
                'name' => $row['category'],
            ));
        }

        return $categories;
    }

    public static function savePosterCategory($posterId, $category) {
        $category = Category::find($category);

        $query = DB::connection()->prepare('INSERT INTO PosterCategory (category, poster) '
                . 'VALUES (:category, :poster)');
        $query->execute(array('category' => $category->name, 'poster' => $posterId));
    }

    public static function destroyPosterCategoryByPosterId($posterId) {
        $query = DB::connection()->prepare('DELETE FROM PosterCategory '
                . 'WHERE poster = :posterid');
        $query->execute(array('posterid' => $posterId));
    }

    public function validate_name() {
        $errors = array();
        $errors = array_merge($errors, parent::validate_string_length('Name', $this->name, 4));
        $errors = array_merge($errors, parent::validate_characters('Name', $this->name));

        if (self::find($this->name)) {
            $errors[] = 'Category already exists!';
        }

        return $errors;
    }
}
