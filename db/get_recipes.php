<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 11/28/16
 * Time: 12:26 PM
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
$userIngredients = $_POST['ingredients'];
//TODO Sanitize for numbers only
//print_r($selected_ingredients);

/**
 * Review this code
 * this scores recipes depending how many ingredients are matched
 */
$query_scoring_part = '0';

foreach ($userIngredients as $ingredient_id) {
    $query_scoring_part .= ' + (
                                SELECT COUNT(*)
                                FROM ingredientsToRecipe itr
                                WHERE itr.recipe_id=r.recipe_id
                                    AND itr.ingred_id=' . $ingredient_id . '
                               )';
}

$query_temp = 'SELECT r.`recipe_ID`, r.`name`, r.`author`, r.`url`, r.`picture_url`, r.`instructions`, r.`cookTime`,' . $query_scoring_part . ' AS match_count
               FROM recipes r
               HAVING `match_count` > 0
               ORDER BY match_count DESC
               LIMIT 20
              ';
//Sample
//SELECT r.*,
//                    (
//                        0
//                        + (
//                        SELECT COUNT(*)
//                        FROM ingredientsToRecipe itr
//                        WHERE itr.recipe_id=r.recipe_ID
//                            AND itr.ingred_id=1
//                        )
//                        + (
//                        SELECT COUNT(*)
//                        FROM ingredientsToRecipe itr
//                        WHERE itr.recipe_id=r.recipe_ID
//                            AND itr.ingred_id=2
//                        )
//
//                    ) AS match_count
//               FROM recipes r
//               ORDER BY match_count DESC LIMIT 20

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