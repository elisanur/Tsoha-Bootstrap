<?php

/*
 */

class User extends BaseModel {

    public $id, $firstName, $lastName, $address, $postalCode, $city, $name, $password, $email;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_address', 'validate_city', 'validate_firstName', 'validate_lastName', 'validate_password', 'validate_postalCode', 'validate_username', 'validate_email');
    }

    public static function authenticate($username, $pass) {
        $query = DB::connection()->prepare('SELECT * FROM Username WHERE name = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $username, 'password' => $pass));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstName' => $row['firstname'],
                'lastName' => $row['lastname'],
                'address' => $row['address'],
                'postalCode' => $row['postalcode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password'],
                'email' => $row['email']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Username(firstname, lastname, address, postalcode, city, name, password, email) VALUES (:firstname, :lastname, :address, :postalcode, :city, :name, :password, :email) RETURNING id');
        $query->execute(array('firstname' => $this->firstName, 'lastname' => $this->lastName, 'address' => $this->address, 'postalcode' => $this->postalCode, 'city' => $this->city, 'name' => $this->name, 'password' => $this->password, 'email' => $this->email));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Username SET firstname = :firstname, '
                . 'lastname = :lastname, address = :address, postalcode = :postalcode, '
                . 'city = :city, name = :name, password = :password, email = :email WHERE id = :id');
        $query->execute(array('id' => $this->id, 'firstname' => $this->firstName, 'lastname' => $this->lastName,
            'address' => $this->address, 'postalcode' => $this->postalCode,
            'city' => $this->city, 'name' => $this->name, 'password' => $this->password, 'email' => $this->email));
    }

    public function destroy() {
        $con = DB::connection();
        $con->beginTransaction();

        try {

            $stmt = $con->prepare('DELETE FROM PosterCategory pc USING Poster p, Username u WHERE u.id=p.publisher AND p.id=pc.poster AND u.id = :id');
            $stmt->execute(array('id' => $this->id));

            $stmt = $con->prepare('DELETE FROM Purchase WHERE username = :id');
            $stmt->execute(array('id' => $this->id));

            $posters = Poster::allUnsoldPostersFromUser($this->id);

            foreach ($posters as $poster) {
                $stmt = $con->prepare('DELETE FROM Purchase WHERE poster = :id');
                $stmt->execute(array('id' => $poster->id));
            }

            $stmt = $con->prepare('DELETE FROM Poster p USING Username u WHERE u.id=p.publisher AND u.id = :id');
            $stmt->execute(array('id' => $this->id));

            $stmt = $con->prepare('DELETE FROM Username u WHERE u.id = :id');
            $stmt->execute(array('id' => $this->id));

            $con->commit();
        } catch (PDOException $ex) {
            $con->rollBack();
            die($ex);
        }
    }

    public static function find($user_id) {
        $query = DB::connection()->prepare('SELECT * FROM Username WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $user_id));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstName' => $row['firstname'],
                'lastName' => $row['lastname'],
                'address' => $row['address'],
                'postalCode' => $row['postalcode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password'],
                'email' => $row['email']
            ));

            return $user;
        }
        return null;
    }

    public static function findByEmail($email) {
        $query = DB::connection()->prepare('SELECT * FROM Username WHERE email = :email LIMIT 1');
        $query->execute(array('email' => $email));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstName' => $row['firstname'],
                'lastName' => $row['lastname'],
                'address' => $row['address'],
                'postalCode' => $row['postalcode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password'],
                'email' => $row['email']
            ));

            return $user;
        }
        return null;
    }

    public static function findByUsername($username) {
        $query = DB::connection()->prepare('SELECT * FROM Username WHERE name = :name LIMIT 1');
        $query->execute(array('name' => $username));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstName' => $row['firstname'],
                'lastName' => $row['lastname'],
                'address' => $row['address'],
                'postalCode' => $row['postalcode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password'],
                'email' => $row['email']
            ));

            return $user;
        }
        return null;
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Username');
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[] = new User(array(
                'id' => $row['id'],
                'firstName' => $row['firstname'],
                'lastName' => $row['lastname'],
                'address' => $row['address'],
                'postalCode' => $row['postalcode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password'],
                'email' => $row['email']
            ));
        }

        return $users;
    }

    public static function topSellers() {
        $query = DB::connection()->prepare('SELECT p.publisher AS publisher, COUNT(p.publisher) AS amount FROM Poster p INNER JOIN Username u ON p.publisher = u.id WHERE p.sold=TRUE GROUP BY publisher ORDER BY amount DESC LIMIT 10');
        $query->execute();
        $rows = $query->fetchAll();
        $top = array();

        foreach ($rows as $row) {

            $top[] = array(
                'publisher' => User::find($row['publisher']),
                'amount' => $row['amount']
            );
        }

        return $top;
    }

    public static function sales() {
        $userId = BaseController::get_user_logged_in_id();
        $query = DB::connection()->prepare('SELECT Purchase.poster FROM Purchase, Poster '
                . 'WHERE Poster.id = Purchase.poster AND poster.publisher = :username ORDER BY Purchase.id DESC');
        $query->execute(array('username' => $userId));
        $rows = $query->fetchAll();

        $posters = array();

        foreach ($rows as $row) {           
            $poster = Poster::find($row['poster']);
            $posters[] = $poster;
        }

        return $posters;
    }

    public static function orders() {
        $userId = BaseController::get_user_logged_in_id();
        $query = DB::connection()->prepare('SELECT * FROM Purchase '
                . 'WHERE username = :username ORDER BY Purchase.id DESC');
        $query->execute(array('username' => $userId));
        $rows = $query->fetchAll();
        
        $posters = array();

        foreach ($rows as $row) {           
            $poster = Poster::find($row['poster']);
            $posters[] = $poster;
        }

        return $posters;
    }

    public function validate_username() {
        $errors = array();
        $errors = parent::validate_string_length('Username', $this->name, 5);

        $old = self::findByUsername($this->name);

        if ($old) {
            $errors[] = 'Username is already in use';
        }

        return $errors;
    }

    public function validate_firstName() {
        return parent::validate_string_length('First name', $this->firstName, 2);
    }

    public function validate_lastName() {
        return parent::validate_string_length('Last name', $this->lastName, 2);
    }

    public function validate_address() {
        return parent::validate_string_length('Address', $this->address, 5);
    }

    public function validate_postalCode() {
        return parent::validate_string_length_exact('Postalcode', $this->postalCode, 5);
    }

    public function validate_password() {
        return parent::validate_string_length('Password', $this->password, 8);
    }

    public function validate_city() {
        return parent::validate_string_length('City', $this->city, 2);
    }

    public function validate_email() {
        $errors = array();

        $old = BaseController::get_user_logged_in();
        $person = self::findByEmail($this->email);

        if (!$old) {
            if ($person) {
                $errors[] = 'Email address is already in use!';
            }
        } elseif ($person && $old->email != $this->email) {
            $errors[] = 'Email address is already in use!';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) == FALSE) {
            $errors[] = 'Email address is faulty!';
        }

        return $errors;
    }

}
