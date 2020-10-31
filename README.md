## Intro

Here is my solution for the coding challenge. I could have used CSV parser for the import and API Platform for easily defining API resources but then where is the fun. On the other hand, this way you have a decent amount of codebase to assess my knowledge.

## Installation

1. Build/run containers

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```

2. Update your system host file and add

    ```bash
    ni.local to /etc/hosts
    ```
    
    if you use Docker for Mac

3. Install dependencies and import users and products

    Login in the container 
    
    ```bash
    docker exec -it ni_php_1 bash
    ```
    
    and run 

    ```bash
    composer install
    ```
    
4. To run tests

    Login in the container
    
    For behat run : 
    `vendor/bin/behat`
    
    For unit testing run: `vendor/bin/phpunit`
    
## Usage

The usable endpoints as requested:
    
* GET /products
* POST /auth
* GET /user
* GET /user/products
* POST /user/products
* DELETE /user/products/{SKU}

To test the API e2e:

Import the NI.postman_collection.json in Postman
    
## What more I could add?

1. Isolate test database.
2. Finish functional testing
2. JWT private/public keys should not be committed but generated on first deployment
3. Validation (eg.: payload / id could be validated)
4. Caching (Doctrine or memcache, maybe Redis)
5. Migration instead of import command
6. Improved logging
7. and many more
    
