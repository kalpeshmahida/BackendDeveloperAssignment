Assignment Submission for Backend Developer
===========================================

This repository is part of assignment for Backend Developer.

This document contains information on how to install, and setup this assignment script.

Installation
------------

To install this script, run the below commands

```bash

$ git clone https://github.com/kalpeshmahida/BackendDeveloperAssignment.git
$ cd BackendDeveloperAssignment
$ composer install

```

If you want to run the script using built-in server, execute this command

```bash

$ php bin/console server:run

```

Feed Command
------------

Run this command specifying API-KEY to retrive the data from the last 3 days from nasa api.

**NOTE:** It is assumed parameters.yml is configured with mysql details and specified database in details is created already.

It is important to run below command to populate data in database before proceeding further

```bash

$ bin/console  doctrine:schema:create
$ php bin/console neo:feed API-KEY

```

The command is implemented inside `AppBundle\Command\NeoFeedCommand`

Default Controller
------------------

`AppBundle\Controller\DefaultController` - the index method returns `{"hello":"world!"}`

REST Services
-------------

`Neo SDK` - Inside `src\Neo` basic rest client implementation to access nasa api

`/api/doc` - is the route to access API documentation

`/neo/hazardous` - implemented into `hazardousAction` method of `ApiBundle\Controller\AsteroidController`

`/neo/fastest?hazardous=(true|false)` - implemented into `fastestAction` method of `ApiBundle\Controller\AsteroidController`

`/neo/best-year?hazardous=(true|false)` - implemented into `bestYearAction` method of `ApiBundle\Controller\AsteroidController`

`/neo/best-month?hazardous=(true|false)` - implemented into `bestMonthAction` method of `ApiBundle\Controller\AsteroidController`

Tests
-----

To run all tests

```bash

$ phpunit

```

To run home page test

```bash

$ phpunit tests/AppBundle/Controller/DefaultControllerTest.php

```

To run Webservices tests

```bash

$ phpunit tests/ApiBundle/Controller/AsteroidControllerTest.php

```

What's inside?
---------------

The assignment is configured with the following Symfony bundles and third party PHP libs apart from Symfony's default distribution:
* [**FOSRestBundle**][1] - To add rest functionality
* [**NelmioApiDocBundle**][2] - To generate API documentation
* [**GuzzleHttp**][3] - As a base HTTP client for NEO api SDK
* [**DoctrineExtensions**][4] - A set of extensions to Doctrine 2 that add support for additional query functions like YEAR, EXTRACT etc.

[1]: https://github.com/FriendsOfSymfony/FOSRestBundle
[2]: https://github.com/nelmio/NelmioApiDocBundle
[3]: https://github.com/guzzle/guzzle
[4]: https://github.com/beberlei/DoctrineExtensions