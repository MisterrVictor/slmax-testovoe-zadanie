<?php

require_once 'Person.php';
require_once 'PeopleList.php';
require_once 'Database.php';

// Пример использования task1
$person = new Person(null, 'John', 'Doe', '1990-05-15', 0, 'New York');
$person->save();
var_dump($person);

$person_formated = $person->format('2002-02-12', 1);
var_dump($person_formated);
echo 'New Instance of Person' . "<br>";
echo "ID: " . $person_formated->id . "<br>";
echo "Name: " . $person_formated->first_name . " " . $person_formated->last_name . "<br>";
echo "Formated Birthdate: " . $person_formated->birthdate . "<br>";
echo "Formated Gender: " . $person_formated->gender . "<br>";
echo "Birth City: " . $person_formated->birth_city . "<br>";

// Пример использования task2
$ids = array();
$conn = (new Database())->getConnection();
$stmt = $conn->prepare("SELECT `id` FROM `people`");
$stmt->execute();
$row = $stmt->fetch();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $ids[] = $row['id'];
}
var_dump($ids);
$people_list = new PeopleList($ids);
$people = $people_list->getPeople();
var_dump($people);
$people_list->deletePeople();
 
