<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sephora</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col hide-on-small-only m3 l2">
            <div id="side_nav_wrapper" style="top: 0px;">
                <div style="height: 1px">
                    <ul class="collapsible" data-collapsible="expandable">
                        <li>
                            <div class="collapsible-header">Category</div>
                            <div class="collapsible-body">
                                <p>
                                    <input type="checkbox" id="all_categories"/>
                                    <label for="all_categories">All</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="brushes_category"/>
                                    <label for="brushes_category">Brushes</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="tools_category"/>
                                    <label for="tools_category">Tools</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="markup_category"/>
                                    <label for="markup_category">Markup</label>
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">Price</div>
                            <div class="collapsible-body">
                                <p>
                                    <input type="checkbox" id="price_under_25"/>
                                    <label for="price_under_25">Under $25</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="price_25_to_50"/>
                                    <label for="price_25_to_50">$25-$50</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="price_50_to_100"/>
                                    <label for="price_50_to_100">$50-$100</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="price_100_to_150"/>
                                    <label for="price_100_to_150">$100-$150</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="price_150_to_300"/>
                                    <label for="price_150_to_300">$150-$300</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="price_above_300"/>
                                    <label for="price_above_300">Above $300</label>
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col sm12 m9 l10">
            <div class="row right" style="width: 100%">
                <div class="right" style="display: table; padding: 10px">
                    <div style="display: table-cell">
                        <span style="padding-left: 10px; padding-right: 10px; font-weight: bold">Sort By:</span>
                    </div>
                    <div style="display: table-cell">
                        <select class="browser-default" id="price_selector">
                            <option value="none" selected>None</option>
                            <option value="priceHighToLow">Price: High to Low</option>
                            <option value="priceLowToHigh">Price: Low to High</option>
                        </select>
                    </div>
                    <span style="padding-left: 10px; padding-right: 10px; font-weight: bold">View:</span>
                    <div style="display: table-cell">
                        <select class="browser-default" id="view_selector">
                            <option value="30" selected>30</option>
                            <option value="60">60</option>
                            <option value="120">120</option>
                            <option value="240">240</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="row" id="product_container">

            </div>

        </div>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
<script>
    $("#price_selector").change(function () {
        retrieveProducts(1, this.value);
    });

    $("#view_selector").change(function () {
        retrieveProducts(1, this.value);
    });

    $(function () {
        retrieveProducts(1, 30);
    });

    function retrieveProducts(pageNumber, pageSize) {
        var url = 'http://sephora-api-frontend-test.herokuapp.com/products?page[number]=' + pageNumber + '&page[size]=' + pageSize;
        $.ajax({
            type: "GET",
            url: url,
            contentType: false,
            success: function (data) {
                console.log(data);
                var productContainer = $('#product_container');
                var products = data.data;
                var rowPrefix = 'row_';
                var rowIndex = 0;
                var count = 0;
                var currRow = rowPrefix + rowIndex;

                productContainer.empty();
                for (i = 0; i < products.length; i++) {
                    if (count == 0) {
                        productContainer.append('<div class="row" id="' + currRow + '"></div>')
                    }
                    var productAttributes = products[i].attributes;
                    var name = productAttributes.name;
                    var category = productAttributes.category;
                    var price = productAttributes.price / 100;

                    var targetRow = $('#' + currRow);
                    var productCard = '<div class="col s4 m4"> ' +
                        '<div class="card">' +
                        '<div class="card-image">' +
                        '<img src="http://placehold.it/350x150">' +
                        '</div>' +
                        '<div class="card-content">' +
                        '<p style="font-weight: bold">' + name + '</p>' +
                        '<p style="text-transform: capitalize">' + category + '</p>' +
                        '<p style="font-weight: bold">$' + price + '</p>' +
                        '</div>' +
//                        '<div class="card-action">' +
//                        '<a href="#">View More</a>' +
//                        '</div>' +
                        '</div>' +
                        '</div>';
                    targetRow.append(productCard);

                    count++;
                    if (count == 3) {
                        count = 0;
                        rowIndex++;
                        currRow = rowPrefix + rowIndex;
                    }
                }

            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    }

</script>
</body>

</html>

