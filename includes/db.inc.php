<?php

$env = parse_ini_file('.env');
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
function getData(): array
{
    $sql = "SELECT * FROM dishes";

    $stmt = connectToDB()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
