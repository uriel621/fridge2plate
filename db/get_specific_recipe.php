<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 11/28/16
 * Time: 12:26 PM
 * Gets the specific recipe by name for sharing and direct accessing.
 */

require_once('config/connect.php');

$output = [
    'success' => false,
    'data' => []
];

if ($conn->connect_errno) {
    $output = [
        'success' => false,
        'data' => "Connect failed: %s\n", $conn->connect_error
    ];
    print(json_encode($output));
    exit();
}

//expecting array of id numbers.
$specificRecipeID = $_GET['recipe'];

$recipeID_cleaned = filter_var($specificRecipeID, FILTER_SANITIZE_NUMBER_INT);

$query_temp = "SELECT r.`recipe_ID`, r.`name`, r.`author`, r.`url`, r.`picture_url`, r.`instructions`, r.`cookTime` FROM recipes as r WHERE r.`name` = $recipeID_cleaned";

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

print(json_encode($output));
