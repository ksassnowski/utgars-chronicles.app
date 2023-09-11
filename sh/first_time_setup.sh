#!/bin/bash
# run this script against from inside the app container the first time you bring up the stack
php artisan migrate:fresh --seed
