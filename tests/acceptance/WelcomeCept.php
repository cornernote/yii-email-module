<?php
$I = new WebGuy($scenario);
$I->wantTo('ensure modules default action works');
$I->amOnPage('/index.php?r=email');
$I->see('You may use the following tools to help manage email within your application.');