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

    1. JWT keys should not be commited but generated on first deployment
    2. Validation (uuid could be validated)
    3. Caching (Doctrine or memcache, maybe Redis)
    4. Proper migrations
    5. Logging
    6. and many more
    
    
I hope the provided solution is sufficient, even though it doesn't cover everything.