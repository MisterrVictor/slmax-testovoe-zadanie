<?php

require_once 'Database.php';

class Person {

    private $id;
    private $first_name;
    private $last_name;
    private $birthdate;
    private $gender;
    private $birth_city;

    function __construct($id = null, $first_name = null, $last_name = null, $birthdate = null, $gender = null, $birth_city = null) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->birth_city = $birth_city;

        if ($id != null) {
            $this->fetch();
        } else {
            $this->save();
        }
    }

    function save() {
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("INSERT INTO people (first_name, last_name, birthdate, gender, birth_city) VALUES (:first_name, :last_name, :birthdate, :gender, :birth_city)");
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':birthdate', $this->birthdate);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':birth_city', $this->birth_city);
        $stmt->execute();
        $this->id = $conn->lastInsertId();
    }

    function delete() {
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("DELETE FROM people WHERE id=:id");
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    static function age($birthdate) {
        $today = new DateTime();
        $diff = $today->diff(new DateTime($birthdate));
        return $diff->y;
    }

    static function gender_text($gender) {
        if ($gender == 0) {
            return 'муж';
        } else {
            return 'жен';
        }
    }

    function fetch() {
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("SELECT * FROM people WHERE id=:id");
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->birthdate = $row['birthdate'];
            $this->gender = $row['gender'];
            $this->birth_city = $row['birth_city'];
        }
    }

    function format($birthdate = null, $gender = null) {
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("UPDATE people SET birthdate=:birthdate, gender=:gender WHERE id=:id");

        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $obj = new stdClass();
        $obj->id = $this->id;
        $obj->first_name = $this->first_name;
        $obj->last_name = $this->last_name;
        if ($birthdate) {
            $obj->birthdate = $birthdate;
        } else {
            $obj->birthdate = $this->birthdate;
        }
        if ($gender) {
            $obj->gender = $gender;
        } else {
            $obj->gender = $this->gender;
        }
        $obj->birth_city = $this->birth_city;

        return $obj;
    }

}

?>
