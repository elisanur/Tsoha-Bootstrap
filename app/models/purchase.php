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
        $purchase = array();
        if ($row) {
            $purchase = new Purchase(array(
                'id' => $row['id'],
                'posterId' => $row['poster'],
                'userId' => $row['username']
            ));
        }
        return $purchase;
    }

    public static function save($poster, $user) {
        $query = DB::connection()->prepare('INSERT INTO Purchase (poster, username) VALUES (:poster, :username) RETURNING id');
        $query->execute(array('poster' => $poster, 'username' => $user));
        $row = $query->fetch();
        $purchase = array();
        if ($row) {
            $purchase = new Purchase(array(
                'id' => $row['id'],
                'posterId' => $poster,
                'userId' => $user
            ));
        }
        return $purchase;
    }
}
