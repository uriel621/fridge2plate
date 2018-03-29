<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 12/11/16
 * Time: 12:29 PM
 */

require_once("../config/connect.php");

if($conn->connect_errno){
    die("Failed to Connect: " . $conn->connect_error);
}
//Gets id of featured recipe from featuredRecipe table and get matching recipes from recipes table
$query_temp = "
  SELECT r.`recipe_ID`, r.`name`, r.`author`, r.`url`, r.`picture_url`, r.`instructions`, r.`cookTime` 
  FROM `featuredRecipes` f 
  JOIN recipes r 
  ON f.`recipe_ID`=r.`recipe_ID` 
  ORDER BY f.`recipe_ID` DESC
";

//this part can be templated from get_recipes.php
if ($result = $conn->query($query_temp)) {
    //print('Query okay');
    while ($row = $result->fetch_assoc()) {
        $recipe = [
            'id'=>$row['recipe_ID'],
            'name'=>$row['name'],
            'author'=>$row['author'],
            'url'=>$row['url'],
            'img'=>$row['picture_url'],
            'instructions'=>$row['instructions'],
            'cookTime'=>$row['cookTime'],
            'ingredient'=>[]
        ];
        $r_ID = $row['recipe_ID'];

        //print_r($recipe);
        //Gets all ingredients with their quantities matching recipe ID
        if($ingredients = $conn->query("SELECT `name`,`name_str`,`count_type`,`count` FROM `ingredientsToRecipe` WHERE `recipe_id`='$r_ID'")){
            while($ing = $ingredients->fetch_assoc()){
                $recipe['ingredient'][] = [
                    'name'=>$ing['name'],
                    'string'=>$ing['name_str'],
                    'amountType'=>$ing['count_type'],
                    'amount'=>$ing['count']
                ];
            }
        }
        $output['data'][]=$recipe;
    }

    $output['success'] = true;
    $result->close();
}else{
    $output['data'] = 'Failed to connect to DB';
}

if($output['success']) {
    $output_json = json_encode($output);
    $featuredRecipeFile = fopen(__DIR__.'/featuredRecipeList.json', "w") or die("Unable to open file");
    fwrite($featuredRecipeFile, $output_json);
    fclose($featuredRecipeFile);
}
