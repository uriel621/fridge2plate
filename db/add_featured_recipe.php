<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 12/11/16
 * Time: 12:28 PM
 *
 * Template for adding to featured recipe
 * To be require by insert_recipe.php when user add their recipe
 * Will use the recipe Id of dded recipe
 */

$preparedFeatured = $connect->prepare(
    "INSERT INTO `featuredRecipes` (`recipe_ID`) 
    VALUES (?) ON DUPLICATE KEY UPDATE `recipe_ID`=VALUES (`recipe_ID`)"
);

$preparedFeatured->bind_param('i', $sqlRecipeId);

$preparedFeatured->execute();