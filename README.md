## Installation

1. Build/run containers with (with and without detached mode)

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```

2. Update your system host file and add

    ```bash
    ni.local to /etc/hosts
    ```
    
    if you use Docker for Mac

3. Install dependecies

    Login in the container 
    
    ```bash
    docker exec -it ni_php_1 bash
    ```
    
    and run 

    ```bash
    composer install
    ```
    
4. Tests
    
    Behat: vendor/bin/behat
    Unit: vendor/bin/phpunit
    
5. What's more I could add?

    1. Isolate test database.
    2. JWT private/public keys should not be committed but generated on first deployment
    3. Validation (id could be validated)
    4. Caching (Doctrine or memcache, maybe Redis)
    5. Migration instead of import command
    6. Improved logging
    7. and many more
    
    
I hope the provided solution is sufficient, even though it doesn't cover everything.