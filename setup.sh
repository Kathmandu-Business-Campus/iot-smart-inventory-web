# !/usr/bin/bash

php artisan migrate:fresh --seed
php artisan shield:super-admin --user=1 --panel=admin
php artisan shield:generate --all --ignore-existing-policies --panel=admin
