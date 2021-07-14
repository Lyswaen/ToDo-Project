# How to contribute to the project

## Create a local copy
Use [README.md](https://github.com/Shiroeuinn/ToDo-Project/blob/master/README.md) to install a clone of the project on local.

## Issue
Please create a new issue, to describe what will be developed.

## New branch
Before beginning to develop, please create a new branch :

```
git checkout -b [newBranch]
```

The branch name has to be in relation to the subject of the new development.

## Development
Please respect the MVC pattern and do not miss to develop the tests linked to your new code.

## Tests
Please launch the tests coverage with :

```
php bin\phpunit --coverage-html public\coverage
```

and check if the total coverage is up to 70%.

## Commit and push in the repository
Add and commit your code with a clear message in relation with the content. For exemple :

```
git add .
git commit -m "Feature(Authentication)Added new functionnality"
```

and push :
```
git push origin [branch name]
```

## Pull Request
Open a new pull request and link it to the issue.

Standards to apply
Please respect the PSR rule, especially those ones :
*   [PSR-1 : Basic Coding Standard](https://gist.github.com/npotier/d5a13245ad9cd2e92fa9dec19baf0e9a)
*   [PSR-2 : Coding Style Guide](https://gist.github.com/npotier/593b645025173ef8bbb5c59d3fd455fa)
*   [PSR-4 : Autoloader](https://www.php-fig.org/psr/psr-4/)
*   [Symfony best pratices](https://symfony.com/doc/5.2/best_practices/index.html) for 5.2 version 
