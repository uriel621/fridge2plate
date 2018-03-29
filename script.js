$(document).ready(function() {
    getRecipe();
    navIngredientButtons();
    getIngredients();
    buttonsPushedToMainDisplay();
    titleImgToModal();

});
/**
 * Global Variables
 */
var ingredientsObjForAutocomplete;
var updatedIngredientsArray;
var newIngredients;
var ingredientsID = [];
var selectedIngredients = {};

/**
 * getIngredients - Ajax call, auto complete, auto complete filter
 * @returns - data from get_ingredients.php
 */
var getIngredients = function() {
    // -----------Auto Complete-----------
    $.ajax({
        url: "./db/recipe/ingredients.json",
        method: "post",
        dataType: "json",
        cache: false,
        success: function(resp) {
            ingredientsObjForAutocomplete = resp;
            updatedIngredientsArray = [];
            newIngredients = resp.data;
            for (var key in newIngredients) {
                if (newIngredients.hasOwnProperty(key)) {
                    updatedIngredientsArray.push(key);
                }
            }
            autoCompleteFilter();

            //-----Input Field Ingredient Invert to ID Numbers-----

            //-----On Go Button-----
            // $(".btn.btn-danger").click(function() {
            //     var ingredientInputSelected = $(".ingredientInput").val();
            //     var objectData = ingredientsObjForAutocomplete.data[ingredientInputSelected];
            //     ingredientCheck(objectData);
            //     //console.log("Ingredients Added to Fridge From Input", ingredientsID);
            //     $(".ingredientInput").val("");
            // });
            // //-----On KeyPress Enter-----
            // $('.ingredientInput').bind('keypress', function (enter) {
            //     if (enter.keyCode == 13) {
            //         var ingredientInputSelected = $(".ingredientInput").val();
            //         var objectData = ingredientsObjForAutocomplete.data[ingredientInputSelected];
            //         ingredientCheck(objectData);
            //         console.log("Ingredients Added to Fridge From Input", ingredientsID);
            //         $(".ingredientInput").val("");
            //     }

            // });
        }
    });
};
/**
 * getRecipe - Ajax call, dom creation when called
 * @returns - recipes from get_recipes.php
 */
var getRecipe = function() {
    //console.log('ingredients ids:', ingredientsID);
    loadStart();
    $.ajax({
        //No ingredients to parse recipe, display feature recipes instead
        url: (ingredientsID.length) ? "./db/get_recipes.php" : "./db/recipe/featuredRecipeList.json",
        dataType: "json",
        method: "post",
        data: {
            ingredients: ingredientsID
        },
        success: function(response) {
            loadStop();
            //console.log("data from get_recipes.php\n", response);
            clear();
            var authorName;
            var recipeName;
            var imgSrc;
            var url;

            var ingName;
            var amount;
            var amountType;
            var instructions;
            var designatedIngredients;

            for (var i = 0; i < response.data.length; i++) {

                authorName = response.data[i].author;
                recipeName = response.data[i].name;
                imgSrc = response.data[i].img;
                url = response.data[i].url;
                instructions = response.data[i].instructions;

                var theDiv = $("<div>", {
                    class: "col-md-3 col-sm-6 col-xs-12"
                });
                var outterDiv = $("<div>", {
                    class: "card"
                });
                var img = $("<img>", {
                    src: imgSrc,
                    class: "cover",
                    width: "100%",
                    height: "286px",
                    'data-toggle': "modal",
                    'data-target': "#myModal"
                });
                var innerDiv = $("<div>", {
                    class: "card-block",
                    height: "100px" //set the height of card-block cards in following rows will line up correctly
                });
                var h3 = $("<h3>", {
                    class: "card-title",
                    html: recipeName
                });
                var recipeUrl = $("<p>", {
                    html: "<h3>Recipe Link</h3>" + '<a href="' + url + '" target="_blank">Click me!</a>'
                });

                var ingDiv = $('<div>', {
                    class: 'ingDiv',
                    style: 'height: 0; overflow: hidden'
                });

                var steps = $("<div>", {
                    class: "steps-style",
                    html: "<h3>Instructions</h3>" + instructions
                });

                $("#stuff").append(theDiv);
                theDiv.append(outterDiv);
                outterDiv.append(img, innerDiv);
                innerDiv.append(h3);
                var $ingList = $("<ul>");

                for (var j = 0; j < response.data[i].ingredient.length; j++) {

                    ingName = response.data[i].ingredient[j].name;
                    amount = response.data[i].ingredient[j].amount;
                    amountType = response.data[i].ingredient[j].amountType;
                    // designatedIngredients = Math.round(amount) + " " + amountType + " " + ingName;
                    designatedIngredients = response.data[i].ingredient[j].string;

                    var listItem = $("<li>", {
                        class: "card-text",
                        html: designatedIngredients
                    });

                    $ingList.append(listItem);
                }
                ingDiv.append($ingList);
                innerDiv.append(ingDiv.append(steps, recipeUrl));
            }
        },
        error: function() {
            console.log("Well, this is embarrassing");
        }
    });
};
/**
 * noExist - Dynamically Displays a Modal Telling user Their Ingredient does not exist
 */
var noExist = function() {

    var modal = $("<div>", {
        class: "modal fade",
        id: "noIng",
        role: "dialog"
    });
    var modalDialog = $("<div>", {
        class: "modal-dialog"
    });
    var modalContent = $("<div>", {
        class: "modal-content"
    });
    var modalHeader = $("<div>", {
        class: "modal-header"
    });
    var buttonH = $("<button>", {
        class: "close",
        'data-dismiss': "modal",
        html: "&times"
    });
    var h3 = $("<h3>", {
        class: "modal-title",
        html: "Ingredient Not Found"
    });
    var modalBody = $("<div>", {
        class: "modal-body"
    });
    var p = $("<p>", {
        html: "Your ingredient is not found"
    });
    var modalFooter = $("<div>", {
        class: "modal-footer"
    });
    var buttonF = $("<button>", {
        class: "btn btn-default",
        'data-dismiss': "modal",
        html: "Close"
    });

    $("body").append(modal);

    modal.append(modalDialog);
    modalDialog.append(modalContent);
    modalContent.append(modalHeader, modalBody, modalFooter);

    modalHeader.append(buttonH, h3);
    modalBody.append(p);
    modalFooter.append(buttonF);
    $('#noIng').modal('toggle');
};
/**
 * navIngredientButtons - Creates Buttons from mostCommonIngredients.js and displays them on Nav menu
 */
var navIngredientButtons = function() {
    $.ajax({
        url: './db/recipe/popularIngredients.json',
        dataType: 'json',
        method: 'post',
        cache: false,
        success: function(response) {
            //console.log("navIngredientButtons()");
            var mostCommonIngredients2 = response.data;
            var mostCommonIngredientsKeyNameArray = [];
            var mostCommonIngredientsKeyValueArray = [];

            for (var key in mostCommonIngredients2) {
                if (mostCommonIngredients2.hasOwnProperty(key)) {
                    mostCommonIngredientsKeyNameArray.push(key);
                    mostCommonIngredientsKeyValueArray.push(mostCommonIngredients2[key]);
                }
            }
            (function() {
                var ingredientValue;
                var ingredientName;

                for (var i = 0; i < mostCommonIngredientsKeyValueArray.length; i++) {
                    ingredientValue = mostCommonIngredientsKeyValueArray[i];
                }

                for (var j = 0; j < mostCommonIngredientsKeyNameArray.length; j++) {
                    ingredientName = mostCommonIngredientsKeyNameArray[j];
                    ingredientValue = mostCommonIngredientsKeyValueArray[j];

                    var button = $("<button>", {
                        class: "btn btn-info topIng",
                        html: ingredientName,
                        value: ingredientValue
                    });
                    $("#ingredientButtons").append(button);
                }
            })();
        }
    });
};
/**
 * buttonsPushedToMainDisplay - Buttons On NAV to Main Display
 */
var buttonsPushedToMainDisplay = function() {
    //$(".btn.btn-info.topIng").click(function() {
    $("#ingredientButtons").on("click", ".btn.btn-info.topIng", function() {
        $(this).addClass('selected'); //turns gray

        var val = $(this).attr("value");
        var txt = $(this).text();

        txtArr.push(txt);
        ingredientsID.push(val);
        var newButton = newButtonCreation();
        var returnObject = {
            list_button: $(this),
            included_button: newButton
        };
        selectedIngredients[txt] = returnObject;
        getRecipe();
        $(this).prop('disabled', true);
    });
};
/**
 * getValue - Pushes Buttons to Container Using the "GO" Button
 */
var txtArr = [];
var getValue = function() {
    $('.ingredientInput').each(function() {
        var theValue;
        theValue = $(this).val();
        txtArr.push(theValue);
        newButtonCreation();
    });
};
/**
 * removeIng - Removes Buttons off the Main Display and ingredientsID Array
 */
var removeIng = function() {

    var text = $(this).text();

    var indexS = txtArr.indexOf(text);
    txtArr.splice(indexS, 1);
    ingredientsID.splice(indexS, 1);

    $(this).closest("button").remove();
    //console.log("Selected: ", $(this).text());
    //console.log("Current Items in Fridge", ingredientsID);

    if (ingredientsID.length > 0) {
        getRecipe();
    }
    getBackItems();

    /*removes class that was added when buttons from NAV are duplicated to Main Display;
     Then deletes text property in selectedIngredient */
    selectedIngredients[text].list_button.removeClass('selected').prop('disabled', false); /*sets button back to blue and enables it again*/
    delete selectedIngredients[text];
};
var addClickHandlerToRemovableIngredient = function(element) {
    element.on('click', removeIng);
};
//-----Creates Button-----
var newButtonCreation = function() {
    var fridgeButton = $("<button>", {
        html: txtArr[txtArr.length - 1],
        class: "btn btn-info fridgeButton"
    });
    addClickHandlerToRemovableIngredient(fridgeButton);
    $(".container-fluid .fridge").append(fridgeButton);
    return fridgeButton;
};
/**
 * ingredientCheck - CHECK IF ELEMENT IN INPUT FIELD MATCHES WITH ingredientID ARRAY
 */
var ingredientCheck = function(ingredient) {
    if (ingredient === undefined) {
        noExist();
    } else {
        ingredientsID.push(ingredient);
        getValue();
        getRecipe();
    }
};
/**
 * getBackItems - Checks length of ingredientsID array and if equates to 0;
 * If true then clears row to display main recipes in Main Display
 */
var getBackItems = function() {
    if (ingredientsID.length === 0) {
        //console.log("getBackItems()");
        clear();
        getRecipe();
    }
};
/**
 * Loading - Starts and then ends loading image for Ajax Calls
 */
var loadStart = function() {
    $("#loading").show();
};
var loadStop = function() {
    $("#loading").hide();
};
/**
 * clear - clears row of recipes
 */
var clear = function() {
    $("#stuff").empty();
};
/**
 * autoCompleteFilter - Filter For Auto Complete
 */
var autoCompleteFilter = function() {
    //var ingred = ingredientsObjForAutocomplete.data;
    $(".ingredientInput").autocomplete({
        //source: ingred,
        source: updatedIngredientsArray,
        select: function(e, ui) {
            //console.log("SELECTED");

            var ingredient = ui.item.value;
            var value = ingredientsObjForAutocomplete.data[ingredient];
            //console.log(ingredient);
            //console.log(value);

            txtArr.push(ingredient);
            ingredientsID.push(value);

            newButtonCreation();
            getRecipe();

            $(this).val('');
            return false;

        }
    });

    // Overrides the default autocomplete filter function to search only from the beginning of the string
    $.ui.autocomplete.filter = function(array, term) {
        var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(term), "i");
        return $.grep(array, function(value) {
            return matcher.test(value.label || value.value || value);
        });
    };
};
/**
 * titleImgToModal - Title and Image Inside Modal
 */
var titleImgToModal = function() {
    $('#stuff').on('click', 'img', function() {
        var image = $(this).attr('src');
        var recipeTitle = $(this).parent().find(".card-title").text();

        $("#myModal .showImage").attr("src", image);
        $('#myModal .ingContainer').html('<h3>' + "Ingredients" + '</h3>' + $(this).next().find('.ingDiv').html());
        $("#myModal .modal-header").html("<h3>" + recipeTitle + "</h3>");
    });
};
/**
 * toggleNav - Pushes Nav Bar to the Side
 */
$(function() {
    // Toggle Nav on Click
    $('.toggle-nav').click(toggleNav);
    $('#main-container').click(closeNav);
});

function toggleNav() {
    $('#site-wrapper').toggleClass('show-nav');
}

function closeNav() {
    if ($('#site-wrapper').hasClass('show-nav')) {
        $('#site-wrapper').toggleClass('show-nav');
    }
}
