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

    <style>
        a {
            cursor: pointer;
            text-decoration: none !important;
        }

        a:hover {
            opacity: 0.6;
        }

        .collapsible-body {
            padding: 1rem;
        }

        a.active {
            color: #FF1300;
        }

        a.inactive {
            color: #AAAAAA;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col hide-on-small-only m3 l2">
            <div style="position: fixed; margin-top: 65px">
                <ul class="collapsible" data-collapsible="expandable">
                    <li>
                        <div class="collapsible-header" style="font-weight: bold">Category</div>
                        <div class="collapsible-body">
                            <div style="margin-bottom: 15px">
                                <a href="#" onclick="showAllCategories()"
                                   style="color: black; font-size: 14px">Show
                                    All</a>
                            </div>

                            <form id="category_filter_form">
                                <p>
                                    <input type="checkbox" id="brushes_category" value="brushes"/>
                                    <label for="brushes_category">Brushes</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="tools_category" value="tools"/>
                                    <label for="tools_category">Tools</label>
                                </p>
                                <p>
                                    <input type="checkbox" id="markup_category" value="markup"/>
                                    <label for="markup_category">Markup</label>
                                </p>
                            </form>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header" style="font-weight: bold">Price</div>
                        <div class="collapsible-body">
                            <form id="price_filter_form">
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
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col sm12 m9 l10">
            <div class="row" style="position: fixed; right: 0; width: 100%; background-color: white; z-index: 999">
                <div class="right" style="display: table; padding: 10px">
                    <div style="display: table-cell">
                        <span style="padding-left: 10px; padding-right: 10px; font-weight: bold">Sort By:</span>
                    </div>
                    <div style="display: table-cell">
                        <select class="browser-default" id="sort_selector">
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
                    <div class="hide-on-small-only" style="display: table-cell; padding-left: 10px"
                         id="pagination_container">
                    </div>
                </div>

            </div>
            <div class="row" style="margin-top: 65px" id="product_container">

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
    $(function () {
        renderPage(1);
    });

    $('form :input').change(function () {
        renderPage(1);
    });

    $('#view_selector').change(function () {
        renderPage(1);
    });

    $('#sort_selector').change(function () {
        retrieveProducts();
    });

    function showAllCategories() {
        $('#category_filter_form *').filter(':input').each(function () {
            $(this).prop('checked', false);
        });
        retrieveProducts();
    }

    function retrieveProducts() {
        var paginationContainer = $('#pagination_container');
        var page = paginationContainer.find('.active');
        var pageNumber = page.length === 0 ? 1 : page.text();
        renderPage(pageNumber);
    }

    function showPagination(links) {
        var paginationContainer = $('#pagination_container');
        paginationContainer.empty();

        if ($.isEmptyObject(links)) {
            return;
        }
        var pageSize = $('#view_selector').val();
        var self = decodeURIComponent(links.self);
        var last = decodeURIComponent(links.last);
        var currPage = getParameterByName('page[number]', self);
        var numOfPages = links.last === undefined ? currPage : getParameterByName('page[number]', last);
        var pagination = '';

        if (links.prev !== undefined) {
            var prev = decodeURIComponent(links.prev);
            var prevPage = getParameterByName('page[number]', prev);
            var prevFunction = "renderPage(" + prevPage + ")";
            pagination = '<span><a href="#" onclick="' + prevFunction + '"style="color: black">◀</a></span>';
        }

        for (var i = 0; i < numOfPages; i++) {
            var page = i + 1;
            var aFunction = "renderPage(" + page + ")";
            var className = page == currPage ? 'active' : 'inactive';
            pagination = pagination + '<span style="padding-left: 10px; padding-right: 10px"><a class="' + className + '" href="#" onclick="' + aFunction + '">' + page + '</a></span>';
        }

        if (links.next !== undefined) {
            var next = decodeURIComponent(links.next);
            var nextPage = getParameterByName('page[number]', next);
            var nextFunction = "renderPage(" + nextPage + ")";
            pagination = pagination + '<span><a href="#" onclick="' + nextFunction + '"style="color: black">▶</a></span>';
        }

        paginationContainer.append(pagination);
    }

    function constructParams(pageNumber) {
        var params = 'page[number]=' + pageNumber;
        var sortVal = $('#sort_selector').val();
        var viewVal = $('#view_selector').val();
        if (sortVal === 'none') {
            params = params + '&page[size]=' + viewVal;
        } else {
            sortVal = sortVal === 'priceHighToLow' ? '-price' : 'price';
            params = params + '&page[size]=' + viewVal + '&sort=' + sortVal;
        }

        var categoryVal = [];

        $('#category_filter_form *').filter(':input').each(function () {
            if ($(this).is(':checked')) {
                categoryVal.push($(this).val());
            }
        });

        if (categoryVal.length > 0) {
            var prefix = categoryVal.length > 1 ? '&filter[category_in]=' : '&filter[category_eq]=';
            params = params + prefix + categoryVal.toString();
        }

        return params;
    }

    function renderPage(pageNumber) {
        var postfix = constructParams(pageNumber);
        var url = 'http://sephora-api-frontend-test.herokuapp.com/products?' + postfix;
        console.log('url: ', url);
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
                for (var i = 0; i < products.length; i++) {
                    if (count == 0) {
                        productContainer.append('<div class="row" id="' + currRow + '"></div>')
                    }
                    var productAttributes = products[i].attributes;
                    var name = productAttributes.name;
                    var category = productAttributes.category;
                    var price = productAttributes.price / 100;
                    var availabilityDisplay;

                    if (productAttributes.under_sale) {
                        availabilityDisplay = productAttributes.sold_out === true ? '<p style="font-weight: bold; color: red">Out of Stock</p>' : '<p style="font-weight: bold; color: green">Available</p>';
                    } else {
                        availabilityDisplay = '<p style="font-weight: bold; color: darkgrey">Not for Sale</p>';
                    }


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
                        availabilityDisplay +
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

                showPagination(data.links);
            },
            error: function (xhr, status, error) {
                alert(error);
            }
        });
    }

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
</script>
</body>

</html>

