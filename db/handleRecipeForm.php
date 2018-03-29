<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 12/7/16
 * Time: 5:33 PM
 */

require_once("./config/connect.php");

require_once("./Insert_recipe_ingredients.php");

$output = [
    "success"=>false,
    "data"=>""
];
//Limits the amount of
if($conn->query("DELETE FROM `featuredRecipes`
              WHERE `featured_ID` NOT IN 
              (
                  SELECT `featured_ID`
                  FROM (
                      SELECT `featured_ID`
                      FROM `featuredRecipes`
                      ORDER BY `featured_ID` DESC
                      LIMIT 20
                      ) foo
              )")){
    $output["success"]=true;
}

//require_once("./recipe/get_featured_recipe.php");
//require_once("./recipe/get_ingredients_cron.php");

$output["data"]="Recipe added.";

$conn->close();
print(json_encode($output));