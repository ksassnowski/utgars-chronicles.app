# Getting Started With Docker
An overview of the communication between the different components of this application can be found in `docs/utgars_docker.drawio.xml`.

## Utgar's Chronicles Container
Utgar's Chronicles is a Laravel backend with a Javascript frontend. The Dockerfile & Docker Compose setup here assumes 
both services are running on the same container. 

### First Time Setup 
The first time you start containers and can verify connectivity with the mysql container, log into the container and run:
`sh/first_time_setup.sh`

### Environment Variables

Copy the `.env.example` to `.env` and set your variables accordingly.

If you are running this in production, you want the debug bar to disappear, add the following to your .env file: `DEBUGBAR_ENABLED=false` 
and set `APP_DEBUG` to false. 

After setting those environment variables run `php artisan config:cache` in the container. 

[Read more about the debug bar.](https://github.com/barryvdh/laravel-debugbar)

### Deployment Notes
It was found to be helpful that the `npm run docker` commands that occur in the `run.sh` script occur before the `php artisan serve`
command. 

#### Unused Environment Variables
It may be necessary to pass more of the variables into the containers, but I haven't run into that yet. 

## Mysql Container

### Troubleshooting
To connect to the database to IDE you may need to set the following in your client: `allowPublicKeyRetrieval=true` 


