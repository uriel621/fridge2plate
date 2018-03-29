<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 12/11/16
 * Time: 2:51 PM
 *
 * uses both insert_ingredients and insert recipe to insert
 * both ingredients and recipe at once.
 */

$ingredientsListData = $_POST['ingredientData'];
$recipeListData = $_POST['recipeData'];

require_once("./config/connect.php");

if($conn->connect_error){
    die("Failed to Connect: " . $conn->connect_error);
}

require_once("insert_ingredients.php");

require_once("insert_recipe.php");

insertIngredientsIntoIngTable($conn, $ingredientsListData);

insertRecipesAndItsIngredients($conn, $recipeListData);