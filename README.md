# VIES

The VIES class allows you to validate a European VAT-number and retrieve address information from the VAT Information Exchange Service.

## REQUIREMENTS

-	PHP 5
-	SOAP extension

## USAGE ##

	$vies = new VIES();
	$valid = $vies->checkVAT('BE 0883.923.584'); // true or false
	$info = $vies->getInfo('BE 0883.923.584'); // array with information

## CONTACT
http://www.tse-webdesign.be/
info@tse-webdesign.be (bugs, questions or comments)