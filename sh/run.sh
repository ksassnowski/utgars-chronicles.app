#!/bin/bash
# This application requires two long running services to work. To avoid having to login to the script we follow this
# pattern from the Docker docs https://docs.docker.com/config/containers/multi-service_container/#use-a-wrapper-script

# run the vue server in the background
npm run production

# run the laravel server in the background
php artisan serve --host 0.0.0.0 &

# Wait for artisan process to exit
wait -n

# Exit with artisan status code
exit $?
