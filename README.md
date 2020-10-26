# Login&Register app

- The content of this assignment can be found under the docs directory

## Installation & requirements

- Start with ``` ./run``` command to setup docker environment
- Create new entry in /etc/hosts
```127.0.0.1 app.loc```
- Enter the php container with ```docker exec -it container_name_php bash```
- Install backend dependencies with composer: ```composer install``` 
- Install front dependencies with npm ```npm install```
- Build assets: ```./node_modules/.bin/encore dev```
- Run migrations ```php bin/console doctrine:migrations:diff``` ```php bin/console doctrine:migrations:migrate```
- Upload fixtures ```php bin/console doctrine:fixtures:load```

## Usage

- Enter http://app.loc in your browser and enjoy!
