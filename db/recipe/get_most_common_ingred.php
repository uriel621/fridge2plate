<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 12/9/16
 * Time: 4:21 PM
 */

require_once('../config/connect.php');

if($conn->connect_errno){
    exit();
}
$output['success'] = false;

if($result = $conn->query("SELECT `name`, `ingred_ID`, COUNT(`ingred_ID`) AS appearance
FROM `ingredientsToRecipe` 
GROUP BY `name`, `ingred_ID` 
ORDER BY appearance 
DESC LIMIT 15")){

    while($row = $result->fetch_assoc()){
        $output['data'][$row['name']] = $row['ingred_ID'];
    }

    $output['success'] = true;
}

$result->close();

if($output['success']) {
    $output_json = json_encode($output);
    $ingredientFile = fopen('./popularIngredients.js', "w") or die("Unable to open file");
    fwrite($ingredientFile, "var mostCommonIngredients = $output_json;");
    fclose($ingredientFile);
}