<?php
$I = new WebGuy($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnPage('/'); 
$I->see('Index of /');
$I->click('test'); 
$I->see('test2.csv');
