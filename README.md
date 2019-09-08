# blog-restful-api

Example of a blog restful API using ADR (Action-Domain-Responder) and DDD-Lite (Domain-Drive-Design) patterns

## Built With

* [Slim Framework](http://www.slimframework.com/) - Micro framework for PHP
* [PHP-DI](http://php-di.org/) - Dependency injection container
* [Monolog](https://github.com/Seldaek/monolog) - Logging for PHP
* [Respect/Validation](https://github.com/Respect/Validation) - Validation engine
* [PHPUnit](https://phpunit.de/) - Unit Testing for PHP
* [Phinx](https://phinx.org/) - Database Migrations
* [Faker](https://github.com/fzaninotto/Faker) - Generate Fake data

## Installing

Run Composer Dependencies

``` $ Composer update ```

Configure database variables in ``` app/settings.php ``` file

Run Database migration

``` $ php vendor/bin/phinx migrate  ```

Run Database seed

``` $ php vendor/bin/phinx seed:run  ```

For Testing run

``` $ composer test  ```

For Start the Server run

``` $ composer start ```

## Routes

* User

``` GET /users ``` - Get all users

``` GET /users?name=Ygor&sort=email.asc&offset=0&limit=10 ``` - Get users with filter, sort and pagination

``` GET /users/5 ``` - Get user by ID

``` POST /users ``` - Save user

``` PUT /users/5 ``` - Edit user
 
``` DELETE /users/5 ``` - Delete user by ID

* Post

``` GET /posts ``` - Get all posts

``` GET /posts/4 ``` - Get post by ID

``` POST /posts ``` - Persist post

``` PUT /posts/5 ``` - Edit title and content of a post
 
``` DELETE /posts/5 ``` - Delete posts by ID

``` POST /posts/5/like/ygoralcmd ``` - User (`username = ygoralcmd`) like or dislike a post (`id = 5`)

``` POST /posts/5/tag ``` - Add tag to post

``` DELETE /posts/5/tag/science ``` - Remove tag to post

* Tag

``` GET /tags ``` - Get all tags

``` GET /tags/science ``` - Get tag by name

``` POST /tags ``` - Save tag
 
``` DELETE /tag/2 ``` - Delete tag by ID

## Authors

* [Ygor Alcântara](https://github.com/ygoralcantara)


