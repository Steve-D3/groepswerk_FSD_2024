<?php
include_once "../includes/db.inc.php";

?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dish</title>
 </head>
 <body>
    <h1>Edit Dish</h1>
    <form action="edit.php" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="Pizza">
        <label for="short_description">Short Description</label>
        <input type="text" name="short_description" id="short_description" value="dish description">
        <label for="long_description">Long Description</label>
        <input type="text" name="long_description" id="long_description" value="dish long description">
        <label for="img_url">Image URL</label>
        <input type="text" name="img_url" id="img_url" value="test.jpg">
        <label for="country">Country</label>
        <select name="country" id="country">
            <option value="1">test</option>
            <option value="2">test</option>
            <option value="3">test</option>
        </select>
        <label for="continent">Continent</label>
        <select name="continent" id="continent">
            <option value="1">test</option>
            <option value="2">test</option>
            <option value="3">test</option>
        </select>
        <button type="submit">Save</button>
    </form>
 </body>
 </html>
