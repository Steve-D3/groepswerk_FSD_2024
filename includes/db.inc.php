<?php

// $env = parse_ini_file('.env');

$env = parse_ini_file(__DIR__ . '/../.env');

$menuOptions = getMenuOptions();
// CONNECTIE MAKEN MET DE DB
function connectToDB()
{
    global $env;
    // CONNECTIE CREDENTIALS
    $db_host = $env["DB_HOST"];
    $db_user = $env["DB_USER"];
    $db_password = $env["DB_PASS"];
    $db_db = $env["DB_DATABASE"];
    $db_port = $env["DB_PORT"];

    try {
        $db = new PDO('mysql:host=' . $db_host . '; port=' . $db_port . '; dbname=' . $db_db, $db_user, $db_password);
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        die();
    }
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    return $db;
}


// HAAL ALLE NEWS ITEMS OP UIT DE DB
function getData($continentId = null): array
{
    $sql = "SELECT 
        dishes.id AS id,
        dishes.name AS dish, 
        dishes.description AS description,  
        dishes.img_url AS img,
        country.name AS Country,
        continent.name AS Continent 
    FROM dishes
    LEFT JOIN country_has_dishes
        ON dishes.id = country_has_dishes.dishes_id
    LEFT JOIN country
        ON country.id = country_has_dishes.country_id
    LEFT JOIN continent
        ON country.continent_id = continent.id";

    // Voeg een filter toe als een continentId is meegegeven
    if ($continentId) {
        $sql .= " WHERE continent.id = :continentId";
    }

    $stmt = connectToDB()->prepare($sql);

    if ($continentId) {
        $stmt->bindParam(':continentId', $continentId, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getMenuOptions() {
    $sql = "SELECT * FROM continent";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getExtraImages($dishId){
     $sql = "SELECT img_url FROM dish_images WHERE dish_id = :dish_id";

     $stmt = connectToDB()->prepare($sql);
     $stmt->bindParam(':dish_id', $dishId, PDO::PARAM_INT);
     $stmt->execute();
 
     return $stmt->fetchAll(PDO::FETCH_COLUMN);
}