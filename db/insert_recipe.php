<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 12/11/16
 * Time: 2:50 PM
 */

/**
 * Insert Recipes and Ingredients
 */
function insertRecipesAndItsIngredients($connect, $recipesList){
    //prepare for recipe insert
    $queryRecipe = "INSERT IGNORE INTO `recipes` (`given_ID`, `name`, `author`, `url`, `picture_url`, `instructions`, `cookTime`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $queryInsertRecipe = $connect->prepare($queryRecipe);

    //set recipe binding parameters
    $queryInsertRecipe->bind_param('isssssi', $r_id, $r_name, $r_author, $r_url, $r_img, $r_instruct, $r_time);

    //prepare for ingredient insert
    $queryIngred = "INSERT IGNORE INTO `ingredientsToRecipe` (`ingred_ID`, `recipe_ID`, `name`, `name_str`, `count_type`, `count`) VALUES (?, ?, ?, ?, ?, ?)";
    $queryInsertIngred = $connect->prepare($queryIngred);

    //set ingredients binding parameters
    $queryInsertIngred->bind_param('iisssi', $ing_iId, $ing_rId, $ing_name, $ing_nameStr, $ing_type, $ing_count);

    //assign for each recipe in list
    foreach ($recipesList as $recipe) {
        //user-added recipes need id
        if(!isset($recipe['givenId'])){
            //echo "Giving given_id";
            $lastGivenIdResult = $connect->query("SELECT `given_ID` FROM `recipes`
                                            GROUP BY `given_ID` DESC ORDER BY `recipes`.`given_ID` ASC LIMIT 1");
            $givenIdResultRow = $lastGivenIdResult->fetch_assoc();
            $recipe['givenId'] = $givenIdResultRow["given_ID"];
            $recipe['givenId']--;

        //Skip if recipe already added
        }
        if($connect->query("SELECT `recipe_ID` FROM `recipe` WHERE `given_ID`=" . $recipe['givenId'])){
            continue;
        }
        //recipe data to insert to recipe table
        $r_id = $recipe['givenId'];
        $r_name = trim($recipe['name']);
        $r_author = trim($recipe['author']);
        $r_url = trim($recipe['url']);
        $r_img = ($recipe['img']==="")?"./images/placeholder_360.jpg":trim($recipe['img']);
        $r_instruct = trim($recipe['instructions']);
        $r_time = $recipe['cookingTime'];
        //insert recipe and proceed if insert is successful
        if($queryInsertRecipe->execute()){
            //get last recipe id
            $sqlRecipeId = $queryInsertRecipe->insert_id;

            //check if recipe has property featureRecipe
            if(isset($recipe["featureRecipe"])){
                //add recipe via id to featuredRecipe table
                //echo "Adding to Featured";
                require_once("add_featured_recipe.php");
            }

            foreach ($recipe['ingredients'] as $ingredient) {
                $ingNameForSql = $ingredient['name'];
                //Get ingredient id that matches recipe ingredient
                if($result = $connect->query("SELECT `ingred_ID` FROM `ingredient` WHERE `name`='$ingNameForSql'")){
                    $ingFromSql = $result->fetch_assoc();
                    $sqlIngID = $ingFromSql['ingred_ID'];
                    //print_r("Ingredient ID for " . $ingredient['name'] . " : ".$sqlIngID);
                }

                //individual ingredients from recipe to insert into ingredientsToRecipe table
                $ing_iId = $sqlIngID;
                $ing_rId = $sqlRecipeId;
                $ing_name = trim($ingredient['name']);
                $ing_nameStr = trim($ingredient['originStr']);
                $ing_type = $ingredient['amountType'];
                $ing_count = $ingredient['amount'];
                //insert into table
                $queryInsertIngred->execute();
            }
        }else{
            $queryInsertRecipe->close();
            $queryInsertIngred->close();
            $output = [
                "success" => false,
                "data" => "Failed to add recipe"
            ];
            print(json_encode($output));
            exit();
        }
    }
    $queryInsertRecipe->close();
    $queryInsertIngred->close();
}
