WGTOTW
======

This is a web application is built as part of the course phpmvc given at Blekinge tekniska högskola. More information can be found at [dbwebb.se](http://dbwebb.se/).

This web application uses Anax-MVC, found at [Anax-MVC on github](https://github.com/mosbth/Anax-MVC)

Read article about Anax-MVC here: ["Anax som MVC-ramverk"](http://dbwebb.se/kunskap/anax-som-mvc-ramverk) and here ["Bygg en me-sida med Anax-MVC"](http://dbwebb.se/kunskap/bygg-en-me-sida-med-anax-mvc).


##Install

* Clone from github

> clone https://github.com/jonasahlsson/WGTOTW

* Run composer install to download dependencies. Composer can be downloaded and installed from [composer.org](https://getcomposer.org/).

>cd WGTOTW  
>composer install

* Change permissions for databasefolder, database, style-folder and HTMLPurifiers cache.

> chmod 777 webroot/database  

> chmod 777 webroot/database/database.sqlite  

> chmod 777 webroot/css/anax-grid  

> chmod -R 777 vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer  


* Edit RewriteBase in .htaccess if necessary.



##License

This software is free software and carries a MIT license.


##Use of external libraries

External modules are included and subject to its own license.
* PHP Markdown
* Modernizr
* Font Awesome
* lessphp
* HTMLPurifier



Copyright Jonas Ahlsson 2015, jonasahlsson@hotmail.com