## Installation

1. Build/run containers with (with and without detached mode)

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```

2. Update your system host file and add

    ```bash
    symfony.dev to /etc/hosts
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
    
4. What's more I could add?

    1. Validation (uuid could be validated)
    2. Caching (Doctrine or memcache, maybe Redis)
    3. Proper migrations
    4. Logging
    5. and many more
    
    
I hope the provided solution is sufficient, even though it doesn't cover everything.