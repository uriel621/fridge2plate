<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 11/28/16
 * Time: 9:13 AM
 *
 * get all ingredients and its ID from db
 */

require_once('../config/connect.php');

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

if ($result = $conn->query("SELECT `ingred_ID`, `name` FROM `ingredient`")) {

    while ($row = $result->fetch_assoc()) {
        $output['data'][$row['name']] = $row['ingred_ID'];
    }
    $output['success'] = true;
}

$result->close();

//file write out test
if($output['success']) {
    $output_json = json_encode($output);
    $ingredientFile = fopen(__DIR__.'/ingredients.json', "w") or die("Unable to open file");
    fwrite($ingredientFile, $output_json);
    fclose($ingredientFile);
}
