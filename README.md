RESTful SOA client-server
===============================

### Objective

Service Oriented Architecture created from scratch using the MVC design pattern.

### The data is read from the Google Books API as database.

This is just an example.

I've created my API based on the following three parameters to show that you can create your own with any kind of parameter you prefer.

http://restful_client-server/{category}/{keyword}/{pub_year}


e.g.


{category} only 'book' is available

{keyword} any string as keyword

{year} published year 0000 (give me at least 4 digits.. :) )

http://restful_client-server/book/RESTful/2014

### Note

I have purposely left out API Name and API Version.

It's just matter of organising the APIs in a real context and adapting the controller to manage these parameters.

This is my first practical approach to PHPUnit for unit testing.
