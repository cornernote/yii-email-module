<?php
$I = new WebGuy($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnPage('/index.php?r=email');
$I->see('You are not allowed to access this page');