<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 11/20/16
 * Time: 11:57 AM
 */

/**
 * Insert Ingredients
 * param $connect - mysql connection config
 * param $ingredientList - [array] ingredientData containing ingredients
 */
function insertIngredientsIntoIngTable($connect, $ingredientsList) {
    $queryInsertStatement = $connect->prepare("INSERT INTO `ingredient` (`name`) VALUES (?) ON DUPLICATE KEY UPDATE `name` = VALUES(`name`)");

    $queryInsertStatement->bind_param('s', $ingName);

    foreach ($ingredientsList as $ingredient) {
        $ingName = $ingredient;
        $queryInsertStatement->execute();
    }
    $queryInsertStatement->close();
}
