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

    public function validate_name() {
        $errors = array();
        $errors[] = parent::validate_string_length('Name', $this->name, 4);
        return $errors;
    }

}
