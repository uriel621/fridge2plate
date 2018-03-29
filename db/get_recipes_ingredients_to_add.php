<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 11/20/16
 * Time: 3:31 PM
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>API Get Ingredients</title>
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="config/apikey.js"></script>
</head>
<body>
<button onclick="getRecipes();">Get and Send Recipes</button>
</body>
<script>
    function getRecipes(){
        //console.log('Getting recipes');
        $.ajax({
            dataType: 'json',
            method: 'get',
            url:'https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/random?limitLicense=true&number=25',
            //url:'./recipe/spoon_random.php',
            headers: spoonAccess,
            success: function(resp){
                console.log('Success', resp);
                var ingredientsList = [];
                var recipesList = [];
                var returnQuery = resp.recipes;
                //Iterate through recipes from resp
                for(var i = 0; i < returnQuery.length; i++) {
                    var currentRecipe = returnQuery[i];
                    //if no instruction, skip entire recipe
                    if(currentRecipe.instructions === undefined){ continue; }
                    //Creates recipe obj
                    var recipe = {
                        img: currentRecipe.image,
                        url: currentRecipe.sourceUrl,
                        author: currentRecipe.sourceName,
                        name: currentRecipe.title,
                        givenId: currentRecipe.id,
                        instructions: currentRecipe.instructions,
                        cookingTime: currentRecipe.readyInMinutes,
                        ingredients: []
                    };
                    //Iterates through ingredient in recipes from resp
                    for(var j = 0; j < returnQuery[i].extendedIngredients.length; j++) {
                        var currentIngredient = returnQuery[i].extendedIngredients[j];
                        //Creates ingredient obj
                        var theIngredient= {
                            amount: currentIngredient.amount,
                            amountType: currentIngredient.unit,
                            name: currentIngredient.name,
                            originStr: currentIngredient.originalString
                        };

                        recipe.ingredients.push(theIngredient);

                        if (ingredientsList.indexOf(currentIngredient.name) === -1) {
                            ingredientsList.push(currentIngredient.name);
                        }
                    }

                    recipesList.push(recipe);

                }
                console.log('Ingredients List Complete: ', ingredientsList, ' Recipes: ', recipesList);
                sendRecipe(ingredientsList, recipesList);
            }
        });
    }
    /**
     * sendRecipe - ajax call to php page to send data to mySQL
     * @param {Array} ingredients
     * @param {Object[]} recipes
     */
    function sendRecipe(ingredients, recipes){
        var dataToSend = {ingredientData: ingredients, recipeData: recipes};
        //console.log('ingredients: ', ingredients, ' recipes ', recipes);
        $.ajax({
            method: 'post',
            url: 'insert_recipe_ingredients.php',
            dataType: 'json',
            data: dataToSend,
            success: function(resp){
                console.log('From Insert', resp);
            }
        });
    }
</script>
</html>
