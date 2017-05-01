<?php 
$I = new AcceptanceTester($scenario);

$I->wantTo('see the homepage of SephoraTest');
$I->amOnPage('/');
$I->waitForElement('.product', 10);

// Test Queries (Single, Multiple)
$I->wantTo('see multiple products');
$I->see('Awesome Rubber Car');
$I->see('Durable Rubber Bag');

$I->wantTo('see single product');
$I->click('.viewBtn[value="1"]');
$I->wait(2);
$I->see('Awesome Rubber Car');
$I->see('Sale Price: $15.51');
$I->dontSee('Durable Rubber Bag');

$I->wantTo('view all products again from single product view');
$I->click('X');
$I->wait(2);
$I->see('Awesome Rubber Car');
$I->dontSee('Sale Price: $15.51');
$I->see('Durable Rubber Bag');

// Test Pagination (Next, Previous, Jump to Page)
$I->wantTo('view next page');
$I->click('▶');
$I->waitForText('Incredible Leather Hat', 10);
$I->see('Mediocre Wool Car');
$I->dontSee('Awesome Rubber Car');
$I->dontSee('Durable Rubber Bag');

$I->wantTo('view previous page');
$I->click('◀');
$I->waitForText('Awesome Rubber Car', 10);
$I->see('Durable Rubber Bag');
$I->dontSee('Incredible Leather Hat');
$I->dontSee('Mediocre Wool Car');

$I->wantTo('view page 5');
$I->click('.inactive[data-value="5"]');
$I->waitForText('Aerodynamic Cotton Table', 10);
$I->see('Aerodynamic Aluminum Bench');
$I->dontSee('Durable Rubber Bag');
$I->dontSee('Incredible Leather Hat');

$I->wantTo('view 60 products per page');
$I->selectOption('#view_selector', "60");
$I->waitForText('Incredible Leather Hat', 10);
$I->seeNumberOfElements('.product', 60);

$I->wantTo('view 240 products per page');
$I->selectOption('#view_selector', "240");
$I->waitForText('Small Plastic Plate', 10);
$I->seeNumberOfElements('.product', 200);

$I->wantTo('view 30 products per page');
$I->selectOption('#view_selector', "30");
$I->waitForText('Small Plastic Plate', 10);
$I->seeNumberOfElements('.product', 30);

// Test Price Based Sorting
$I->wantTo('sort price from high to low');
$I->selectOption('#sort_selector', "priceHighToLow");
$I->waitForText('$99.79', 10);
$I->see('$99.79', '#product_0');
$I->see('Incredible Wooden Bag', '#product_0');
$I->dontSee('Incredible Leather Bag');
$I->click('.inactive[data-value="7"]');
$I->waitForText('$0.35', 10);
$I->see('$0.35', '#product_19');
$I->see('Incredible Leather Bag', '#product_19');
$I->dontSee('Incredible Wooden Bag');

$I->wantTo('sort price from low to high');
$I->selectOption('#sort_selector', "priceLowToHigh");
$I->waitForText('$99.79', 10);
$I->see('$99.79', '#product_19');
$I->see('Incredible Wooden Bag', '#product_19');
$I->dontSee('Incredible Leather Bag');
$I->click('.inactive[data-value="1"]');
$I->waitForText('$0.35', 10);
$I->see('$0.35', '#product_0');
$I->see('Incredible Leather Bag', '#product_0');
$I->dontSee('Incredible Wooden Bag');

// Test Category Based Filtering
$I->wantTo('filter product based on brushes category');
$I->checkOption('#brushes_category');
$I->wait(2);
$I->see('brushes');
$I->dontSee('tools');
$I->dontSee('markup');

$I->wantTo('filter product based on tools category');
$I->checkOption('#tools_category');
$I->wait(2);
$I->see('tools');
$I->dontSee('brushes');
$I->dontSee('markup');

$I->wantTo('filter product based on markup category');
$I->checkOption('#markup_category');
$I->wait(2);
$I->see('markup');
$I->dontSee('tools');
$I->dontSee('brushes');