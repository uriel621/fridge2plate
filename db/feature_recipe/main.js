var count = 0;

/**
 * function addRow - adds individual ingredient form group when user requires additional rows
 */
function addRow(){
    var name = "name"+count;
    var amount = "amount"+count;
    var type = "type"+count;
    $('#ingredientArea').append(
        $('<div>',{
            class: "form-group ingredient-group",
            id: count
        })
            .append(
                $('<label class="control-label  col-sm-2" for="ingredient">Ingredient:</label>'),
                $('<div>', {class: 'col-sm-10', id:name})
                    .append(
                        $('<input class="form-control" type="text">')
                    ),
                $('<label class="control-label col-sm-2" for="quantity">Quantity:</label>'),
                $('<div>', {class:"col-sm-3 ", id:amount})
                    .append(
                        $('<input class="form-control col-sm-4" type="number" min="0" max="1000">')
                    ),
                $('<label class="control-label col-sm-1" for="type">Type:</label>'),
                $('<div>', {class:"col-sm-4", id:type})
                    .append(
                        $('<select class="form-control">')
                            .append(
                                $('<option value=" " selected>').text("-"),
                                $('<option value="teaspoon">').text("teaspoon"),
                                $('<option value="tablespoon">').text("tablespoon"),
                                $('<option value="fluid ounce">').text("fluid ounce"),
                                $('<option value="cup">').text("cup"),
                                $('<option value="pint">').text("pint"),
                                $('<option value="quart">').text("quart"),
                                $('<option value="ounce">').text("ounce"),
                                $('<option value="pound">').text("pound")
                            )
                    ),
                $('<button>', {
                        class: "btn btn-danger col-sm-offset-1 delete removeIngredient",
                        type: "button",
                        text: "Delete"
                    }
                )
            )
    );
    count++;
}
/**
 * function validateForm - checks that all required inputs are not empty
 */
function validateForm(){
    //console.log('Validating');
    var validForm = true;

    $('input').each(function(){
        //console.log('Input: ', this);
        if($(this).val().length === 0 || $(this).val() === 0){
            $(this).addClass("missing-input");
            validForm = false;
        }
    });

    if($('textarea').val().length === 0){
        $('textarea').addClass("missing-input");
        validForm = false;
    }

    if(!validForm){
        $(".container").append(
            $("<div id='alertBox' class='alert alert-danger alert-dismissable fade in col-xs-3 col-xs-offset-5'>").append(
                $("<a href='#' class='close' data-dismiss='alert' aria-label='close'>").text("x"),
                $("<strong>").text("Incomplete Form!")
            )
        );
        // setTimeout(function(){
        //     $("#alertBox").fadeOut(500);
        // }, 1500);
    }else{
        sendFormData();
    }
}
/**
 * function sendFormData - gathers all input data and creates object to be sent to be saved
 */
function sendFormData(){
    var ingredientListForAddingToDB = [];

    var recipe = {
        name: $('#recipeName').val(),
        author: 'Kevin Chau',//todo grab from login
        url: 'Not available',//todo need form
        img: '',//todo need file upload
        instructions: $('#instructions').val(),
        cookingTime: $('#cookTime').val(),
        ingredients: [],
        featureRecipe: true
    };
    for(var i = 0; i < count; i++){
        var name = '#name'+i+ ' input';
        var type = '#type'+i+ ' select';
        var amount = '#amount'+i+ ' input';
        //console.log(amount);
        var ingredient = {
            name: $(name).val(),
            amountType: $(type).val(),
            amount: $(amount).val(),
            originStr: ''
        };
        ingredient.originStr = ingredient.amount + ' ' + ingredient.amountType + ' ' + ingredient.name;
        recipe.ingredients.push(ingredient);

        ingredientListForAddingToDB.push(ingredient.name);
    }
    //Expecting list of ingredients and recipes
    //user recipes are feature thus featureRecipe property is created and sent as well
    var dataToSend = {ingredientData: ingredientListForAddingToDB, recipeData: [recipe]};

    $.ajax({
        url: "../handleRecipeForm.php",
        method: 'post',
        data: dataToSend,
        dataType: 'json',
        success: function(resp){
            if(resp.success){
                //Todo change to correct domain
                window.location = "https://fridge2plate.com";
            }
        }
    });
}
/**
 * function removeRow - removes ingredient form group according to delete button pressed
 */
function removeRow(){
    $(this).parent().remove();
}

/**
 * function clearForm - resets all inputs and select within the form
 */
function clearForm(){
    //console.log('clear!');
    $('input').val('');
    $('textarea').val('');
    $('select option[value=" "]').prop('selected', true);
}

/**
 * function removeMissingInput - removes the class "missing-input" when user focuses that input
 */
function removeMissingInput(){
    //return input to normal styling by removing the class
    $('input').on('focus', function(){
        $(this).removeClass("missing-input");
    });
    //resets textarea by removing class
    $('textarea').on('focus', function(){
        $(this).removeClass("missing-input");
    });
}

/**
 * function applyClickHandlers - initializes all click handlers and calls addRow() to add one row on start
 */
function applyClickHandlers(){
    //console.log('applying');
    addRow();
    removeMissingInput();
    $('#addIngredient').click(addRow);
    $('#ingredientArea').on("click",".removeIngredient",removeRow);
    $('#submitBtn').click(validateForm);
    $('#clearBtn').click(clearForm);
}

$(document).ready(applyClickHandlers);
