# wordgame
Word Game - Symfony project implemented Domain Driven Design

Write a simple “word game” web application
Tier 1
The user must be able to enter a word and the application should allow only the words that are in the
English dictionary. Application should score the word by the following rules:
- Give 1 point for each unique letter.
- Give 3 extra points if the word is a palindrome.
- Give 2 extra points if the word is “almost palindrome”. Definition of “almost palindrome”: if by removing
  at most one letter from the word, the word will be a true palindrome.

# Download project
```sh
$ git init
$ git remote add origin https://github.com/GoranSubic/wordgame
$ git pull
```

# Install vendor files
```sh
$ composer install
```
- Do you trust "slince/composer-registry-manager" to execute code and wish to enable it now?
  (writes "allow-plugins" to composer.json) [y,n,d,?]
  y

# First, make sure you install Yarn package manager.
- Optionally you can also install the  Node.js.
```sh
$ yarn install
```
- or if you use the npm package manager
```sh
$ npm install
```

- Create public files
```sh
$ yarn encore dev
```

# .env contains database setup info
- DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"

# Commands to crate and update db
```sh
$ bin/console doctrine:database:create
$ bin/console doctrine:schema:update --force
```

- Command to delete db
```sh
$ bin/console doctrine:database:drop --force
```
