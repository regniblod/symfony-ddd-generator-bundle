Invoice SDK
=============

What is this project?
---------------------
This project removes the need of other developers to read the Invocing API documentation and handle all the possible  business cases.

It provides simple services that contains all the possible actions needed to do an API call to Invocing application.

Also if an action needs multiple API calls the SDK takes care of them automagically.

Installation
------------
See [installation](doc/Installation.md).

Usage
-----
See [usage](doc/Usage.md).

Examples
--------
See [examples](examples).

Releases
--------
See [changelog](CHANGELOG.md).

Important considerations
------------------------
- When creating/editing a `BankAccount` of a `PaymentAccount` you must always set the `IBAN`.
- All SDK methods can throw different exceptions for each possible error, although it's possible to catch the parent one `ApiException`.
