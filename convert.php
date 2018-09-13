<?php

// As requested I kept this down to 2 hours, I churned this out just referencing php.net once or twice 
// (mainly for implexml_load_file reference) 
// I've broken up as much as I can the various questions (though 1-3 are all in one) for clarity's sake.

// This is a very rough initial markup of the code, given more time I would cleaned it up and have it be a bit more granular
// using a function rife with error traps and flags to determine if it was pulling just one exchange manually (ie. user input)
// or pulling from an xml file, or a database if it was either of the last 2 it would be placed into a multi dimensional array 
// and then from there run through the function.

// I am really excited about this opportunity, please do not hesitate to reach out to me if you have any questions about 
// my process, my reasoning or just want to chat in general.
// Also, I hardly use github, hence it's scarcity of content/
// Thank you, Eric Maag 
// eric.maag@gmail.com

// SQL command to build table
/* 
	Create database table
	CREATE TABLE IF NOT EXISTS 'currency_convert' 
	(
	'item_id' int(11) NOT NULL,
	'currency' varchar(4) NOT NULL,
  	'rate' int(5) NOT NULL,
	);
*/

//database connector
// this would be located in a separate file for security purposes
$conn = mysqli_connect("localhost", "root", "test", "currency_exchange");

// xml feed named feed.xml
// this feed would be keep in a separate place, with the data sanitized to ensure no
// no injections or bad behavior


// Exercises 1 through 3 - Retrieving the data from the API (you can assume this will be triggered by a cron job), 
// parsing the data and then writing it to the database.

$affectedRow = 0;
$xml = simplexml_load_file("feed.xml") or die("Error: Cannot create object");

foreach ($xml->children() as $row) {
    $currency = $row->currency;
    $rate = $row->rate;
    
    $sql = "INSERT INTO currency_convert(currency,rate) VALUES ('" . $currency . "','" . $rate . "')";
    
    $result = mysqli_query($conn, $sql);
    
    if (! empty($result)) {
        $affectedRow ++;
    } else {
        $error_message = mysqli_error($conn) . "\n";
    }
}

// report on xml -> db import 
if ($affectedRow > 0) {
    $message = $affectedRow . " records inserted";
} else {
    $message = "No records inserted";
}
?>


<?php
// Exercise 4
// Given an amount of a foreign currency, convert it into the equivalent in US dollars. For example:
//input: 'JPY 5000'
//output: 'USD 65.63'

// Single currency lookup, using human input
echo "Currency: ";
$handle = fopen ("php://stdin","r");
$currency = fgets($handle);

echo "Amount: ";
$handle = fopen ("php://stdin","r");
$amount = fgets($handle);

// TODO : sanitize query
// TODO : check to see that currency exists in database, if not return error/ignore
$result = mysql_query("SELECT rate FROM currency_convert WHERE currency = '$currency'");
$rate = mysql_fetch_array($result);
$usd = $amount * $rate;

// human readable output
echo $currency;
echo "\n";
echo $amount;
echo "\n Equals: $"
echo $usd;
echo " USD at rate: ";
echo $rate;

?>

<?php
// exercise 5
// Given an array of amounts in foreign currencies, return an array of US equivalent amounts in the same order. For example:
// input: array( 'JPY 5000', 'CZK 62.5' )
// output: array( 'USD 65.63', 'USD 3.27' )
// (This can be a separate function from #4.)

// this assumes that daily rates are already written to a database (as by above) 
// and the conversion requests already exist in an array

// Consideration: check timestamp on xml feed and compare to previous timestamp to check if rates have been updated by cron.
// Consideration: check syntax of xml feed to ensure it intergrity (might be part of check within loop)

// loop through existing array $rows extracting currency and exchange rate
// TODO: create a function rather than procedural
foreach ($rows as $row) {
    $currency = $row['currency'];
    $amount =  $row['amount'];

    // TODO : sanitize query
	// TODO : check to see that currency exists in database, if not return error/ignore
	$result = mysql_query("SELECT rate FROM currency_convert WHERE currency = '$currency'");
	$rate = mysql_fetch_array($result);
	$usd = $amount * $rate;

	// output, can be formatted or written as requirements specify just using human readable output from previous exercise.
	echo $currency;
	echo "\n";
	echo $amount;
	echo "\n Equals: $"
	echo $usd;
	echo "USD at rate: ";
	echo $rate;

}
?>
