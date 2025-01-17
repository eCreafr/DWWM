<?php
require 'vendor/autoload.php';

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

$validator = new EmailValidator();
$multipleValidations = new MultipleValidationWithAnd([
    new RFCValidation(),
    new DNSCheckValidation()
]);
// Premier email
$isValid1 = $validator->isValid("pastropunmail@mail.afpa", new RFCValidation());
var_dump($isValid1);

// Premier email avec validation DNS
$isValid1dns = $validator->isValid("pastropunmail@mail.afpa", $multipleValidations);
var_dump($isValid1dns);


// Second email
$isValid2 = $validator->isValid("raphael@ecrea.fr", $multipleValidations); //true
var_dump($isValid2);
