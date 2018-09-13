<?php

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

$affectedRow = 0;

// xml feed named feed.xml
// this feed would be keep in a separate place, with the data sanitized to ensure no
// no injections or bad behavior


// Exercises 1 through 3 - Retrieving the data from the API (you can assume this will be triggered by a cron job), Parsing the data and then writing it to the database.
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

// TODO : clean up query
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

// this assumes that daily rates are already written to a database 
// and the conversion requests already exist in an array

// Consideration: check timestamp on xml feed and compare to previous timestamp to check if rates have been updated.
// Considreration: check syntax of xml feed to ensure it intergrity (might be part of check within loop)

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