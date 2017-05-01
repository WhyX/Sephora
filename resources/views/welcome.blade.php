<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SephoraTest</title>

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

        .product {
            width: 250px;
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            margin: 0;
        }

        .viewBtn {
            position: absolute;
            background: white;
            border: none;
        }

        .viewBtn:active {
            background: white;
        }

        .viewBtn:focus {
            background: darkgrey;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row right">
        <div style="display: table; padding: 10px">
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
            <div style="display: table-cell; padding-left: 10px"
                 id="pagination_container">
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col s5 m3 l2">
            <div style="margin-top: 15px">
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
                                    <input name="price" type="radio" id="price_under_15" value="1500"/>
                                    <label for="price_under_15">Under $15</label>
                                </p>
                                <p>
                                    <input name="price" type="radio" id="price_under_30" value="3000"/>
                                    <label for="price_under_30">Under $30</label>
                                </p>
                                <p>
                                    <input name="price" type="radio" id="price_under_50" value="5000"/>
                                    <label for="price_under_50">Under $50</label>
                                </p>
                                <p>
                                    <input name="price" type="radio" id="price_under_100" value="10000"/>
                                    <label for="price_under_100">Under $100</label>
                                </p>
                                <p>
                                    <input name="price" type="radio" id="price_under_250" value="25000"/>
                                    <label for="price_under_250">Under $250</label>
                                </p>
                                <p>
                                    <input name="price" type="radio" id="price_under_1000" value="100000"/>
                                    <label for="price_under_1000">Under $1000</label>
                                </p>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col s7 m9 l10">
            <div class="row" id="product_container" style="display: flex; flex-wrap: wrap; justify-content: center">
                <div class="progress">
                    <div class="indeterminate"></div>
                </div>
            </div>

        </div>
    </div>

</div>
<script async src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script async src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script async src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
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
            pagination = pagination + '<span style="padding-left: 10px; padding-right: 10px"><a class="' + className + '" data-value="' + page + '" href="#" onclick="' + aFunction + '">' + page + '</a></span>';
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

        $('#price_filter_form *').filter(':input').each(function () {
            if ($(this).is(':checked')) {
                params = params + '&filter[price_lt]=' + $(this).val();
            }
        });

        return params;
    }

    function renderPage(pageNumber) {
        $('.progress').show();
        var postfix = constructParams(pageNumber);
        var url = 'https://sephora-api-frontend-test.herokuapp.com/products?' + postfix;
        console.log('url: ', url);
        $.ajax({
            url: url,
            success: function (data) {
                console.log(data);
                var productContainer = $('#product_container');
                var products = data.data;

                productContainer.empty();
                for (var i = 0; i < products.length; i++) {
                    var productAttributes = products[i].attributes;
                    var id = products[i].id;
                    var name = productAttributes.name;
                    var category = productAttributes.category;
                    var price = productAttributes.price / 100;
                    var availabilityDisplay;

                    if (productAttributes.under_sale) {
                        availabilityDisplay = productAttributes.sold_out === true ? '<p style="font-weight: bold; color: red">Out of Stock</p>' : '<p style="font-weight: bold; color: green">Available</p>';
                    } else {
                        availabilityDisplay = '<p style="font-weight: bold; color: darkgrey">Not for Sale</p>';
                    }

                    var productCard = '<div class="product" id="product_' + i + '">' +
                        '<div>' +
                        '<button class="viewBtn" value="' + id + '" onclick="viewProduct(' + id + ')">View</button>' +
                        '<img src="https://placehold.it/230x150">' +
                        '</div>' +
                        '<div>' +
                        '<p style="font-weight: bold">' + name + '</p>' +
                        '<p style="text-transform: capitalize">' + category + '</p>' +
                        '<p style="font-weight: bold">$' + price + '</p>' +
                        availabilityDisplay +
                        '</div>' +
                        '</div>';
                    productContainer.append(productCard);
                }
                $('.progress').hide();

                showPagination(data.links);
            },
            error: function (xhr, status, error) {
                alert(error);
            }
        });
    }

    function viewProduct(id) {
        var url = 'https://sephora-api-frontend-test.herokuapp.com/products/' + id;
        $('.progress').show();
        $.ajax({
            url: url,
            success: function (data) {
                var productContainer = $('#product_container');
                productContainer.empty();

                var product = data.data;

                var productAttributes = product.attributes;
                var name = productAttributes.name;
                var category = productAttributes.category;
                var price = productAttributes.price / 100;
                var salePrice = productAttributes.sale_price / 100
                var availabilityDisplay;

                if (productAttributes.under_sale) {
                    availabilityDisplay = productAttributes.sold_out === true ? '<p style="font-weight: bold; color: red">Out of Stock</p>' : '<p style="font-weight: bold; color: green">Available</p>';
                } else {
                    availabilityDisplay = '<p style="font-weight: bold; color: darkgrey">Not for Sale</p>';
                }

                var productCard = '<div class="product">' +
                    '<div>' +
                    '<button class="viewBtn" onclick="renderPage(1)">X</button>' +
                    '<img src="https://placehold.it/230x150">' +
                    '</div>' +
                    '<div>' +
                    '<p style="font-weight: bold">' + name + '</p>' +
                    '<p style="text-transform: capitalize">' + category + '</p>' +
                    '<p style="font-weight: bold">$' + price + '</p>' +
                    '<p style="font-weight: bold">Sale Price: $' + salePrice + '</p>' +
                    availabilityDisplay +
                    '</div>' +
                    '</div>';

                productContainer.append(productCard);
                $('.progress').hide();
            },
            error: function (xhr, status, error) {
                alert(error);
            }
        })
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

