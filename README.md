# wikimedia_project
Project for wikimedia

Fundraising Nightly Currency Conversion Tool

Try not to spend more than a couple hours on this task!

Let's say that for our donation pipeline, we want to use currency conversion rates that are updated automatically on a daily basis, rather than having to update them by hand. In order to do this, we sign up for a 3rd party service that provides us with that day’s current conversion rates, for all the currencies that we support. The service is a simple API that outputs XML when called with the URL:

https://wikitech.wikimedia.org/wiki/Fundraising/tech/Currency_conversion_sample?ctype=text/xml&action=raw

This XML sample is a small static subset of all currencies we support. For the purposes of this task, you should assume that we support many more currencies, and that the exchange rates for those currencies are expected to change on a once-daily basis.

First, define a MySQL table that can store the daily currency conversion data. You don't actually have to set up the table anywhere public, just create a SQL file that contains the CREATE statement for the table.

Next, write a command-line PHP script that can handle all of the following tasks:

    Retrieving the data from the API (you can assume this will be triggered by a cron job)
    Parsing the data
    Storing the data in your MySQL table
    Given an amount of a foreign currency, convert it into the equivalent in US dollars. For example:
    input: 'JPY 5000'
    output: 'USD 65.63'
    Given an array of amounts in foreign currencies, return an array of US equivalent amounts in the same order. For example:
    input: array( 'JPY 5000', 'CZK 62.5' )
    output: array( 'USD 65.63', 'USD 3.27' )
    (This can be a separate function from #4.)
    Code checked into github is ideal.

What we’re looking for

A reusable, testable, extensible solution to the problem.

A solution we can reasonably believe was completed within the 2-3 hour timeframe.

Comments that address possible edge cases and point out potential areas for future improvement.
