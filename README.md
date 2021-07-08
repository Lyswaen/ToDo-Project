****
__ToDo & CO__
_DA PHP/Symfony 8th project_
****

*Installation*

* Go to https://github.com/Shiroeuinn/ToDo-Project.git and copy the URL of the project
* Return to your IDE and use the command
  `git clone https://github.com/Shiroeuinn/ToDo-Project.git`
* Use the command `composer install`
* Then use the command `yarn install`
* And finally, use the command `yarn run encore`

Your project is now install

****

*Usage*

* In the .env edit this command line `DATABASE_URL="mysql://root@127.0.0.1:3306/todo_project?serverVersion=10.14.1-MariaDB"`to use your database config
* On your terminal, use the command `symfony console doctrine:database:create` if you have the symfony CLI or `php bin/console doctrine:database:create`
* On your terminal, use the command `symfony serve` if you have the symfony CLI or `php -S localhost:8000 -t public`
  to launch the internal server of PHP
* Go to your browser, on the URL `https://localhost:8000/phpdoc/index.html` to see the technical documentation
