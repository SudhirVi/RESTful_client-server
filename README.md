RESTful SOA client-server
===============================

### Objective

Service Oriented Architecture created from scratch using the MVC paradigm.

The data is read from the Google Books API as database.



I've created my API based on the following three parameters to show that you can create your own with any kind of parameter you prefer.

http://restful_client-server/{category}/{keyword}/{pub_year}


where


{category} only 'book' is available

{keyword} any string as keyword

{pub_year} published year 0000 (give me at least 4 digits.. :) )

e.g.

http://restful_client-server/book/RESTful/2014

These data is taken by a client (model) that interfaces with Google Books APIs and returns the data like a database to make "the game" a bit more exciting.


### Note

I have purposely left out API Name and API Version.

It's just matter of organising the APIs in a real context and adapting the controller to manage these parameters.

This is my first practical approach to PHPUnit for unit testing.
