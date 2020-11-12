# Symfony 5 docker containers
Forked from gitlab.com:martinpham/symfony-5-docker.git

## Included containers

- Database (MariaDB)
- PHP (PHP-FPM)
- Webserver (Nginx)

## How to start
- Go into the docker folder
- run `docker-compose up` which will start the following container
    - two databases
    - nginx
    - php-fpm
 
## How to use
- Go to http://localhost/link/new to create a new shortened url
- Copy the shortened link
- Go to http://localhost/link/revert to get back the real url

### Optionally check the database
- Login to 127.0.0.1:3306 with appuser:apppassword to see the "odd" links 
- Login to 127.0.0.1:3307 with appuser:apppassword to see the "even" links 

## Explanation
- The whole setup is based on symfony 5 and the integrated ORM.
- The split between two databases is made with an arbitrary rule
    - the link will be stored on `shard one` if the length of it is odd
    - the link will be stored on `shard two` if the length of it is even
    
# Caveats
- The focus of this implementation is on the implementation of the "sharding" mechanism only. The following topics are out of scope
    - UI/UX
    - Tests
    - Validation 
- The split by length of the string is not very intuitive and was chosen for demo purposes only
    - with this approach it is quite easy to test if links are really stored on different databases
- The shortened link is simplified by just adding the shard-name + a md5 hash of the link. In a real world scenario the implementation should be much more sophisticated by using [this](https://www.geeksforgeeks.org/how-to-design-a-tiny-url-or-url-shortener/) implementation for example 
- in a real world scenario I wouldn't have used the ORM for that use-case. It has the downside that it needs two entities for the same model just to map it to different databases. On the other hand it was a good choice for demo purposes as it simplified the development process and is easier to review.
