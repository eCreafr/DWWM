<?php

/* on appelle l'autoload de composer, puis les fonctions d'email validator d'egulias */


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


// on teste le Premier email
echo "pastropunmail@mail.afpa\n";
$isValid1 = $validator->isValid("pastropunmail@mail.afpa", new RFCValidation());
var_dump($isValid1);

// Premier email avec validation DNS en plus !
echo "pastropunmail@mail.afpa\n";
$isValid1AvecDns = $validator->isValid("pastropunmail@mail.afpa", $multipleValidations);
var_dump($isValid1AvecDns);


// Second email
echo "raphael@ecrea.fr\n";
$isValid2 = $validator->isValid("raphael@ecrea.fr", $multipleValidations); //true
var_dump($isValid2);
