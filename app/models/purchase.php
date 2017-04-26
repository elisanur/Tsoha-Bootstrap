<?php

class Purchase extends BaseModel {

    public $id, $posterId, $userId;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Purchase');
        $query->execute();
        $rows = $query->fetchAll();
        $purchase = array();
        foreach ($rows as $row) {
            $purchase[] = new Purchase(array(
                'id' => $row['id'],
                'posterId' => $row['poster'],
                'userId' => $row['username']
            ));
        }
        return $purchase;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Purchase WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $purchase = new Purchase(array(
                'id' => $row['id'],
                'posterId' => $row['poster'],
                'userId' => $row['username']
            ));
        }
        return $purchase;
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
