# First Time DB setup 

The first time start your utgar's chronicle container, 
you need to log into the container and run the migration script. 

## Steps 
1. Make sure the container is 
2. `docker exec -it bash backend`

```
php artisan migrate:fresh --seed
```

## run the server 
the laravel server needs to be started after the vue server has been started. The vue 
server starts when we start docker-compose. from inside your container 
run `php artisan serve --host=0.0.0.0`

we set the host to this because of the way docker handles IP addresses

