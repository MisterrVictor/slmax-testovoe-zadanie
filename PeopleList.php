<?php

require_once 'Database.php';
require_once 'Person.php';

class PeopleList {

    private $ids;

    function __construct($ids) {
        if (!class_exists('Person')) {
            echo 'Класс Person не определен';
            return;
        }
        $this->ids = $ids;
    }

    function getPeople() {
        $people_list = array();
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare('SELECT * FROM people WHERE id in (' . implode(",", $this->ids) . ')');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $person = new Person($row['id'], $row['first_name'], $row['last_name'], $row['birthdate'], $row['gender'], $row['birth_city']);
            array_push($people_list, $person);
        }
        return $people_list;
    }

    function deletePeople() {
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare('DELETE FROM people WHERE id in (' . implode(",", $this->ids) . ')');
        $stmt->execute();
    }

}
