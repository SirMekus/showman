After pulling please do the following. Run:
1. php artisan migrate
2. php artisan storage:link (to create a symbolic link from the public folder to storage folder)
3. php artisan db:seed
4. php artisan db:seed --class=CinemaSeeder

After running the above you can then login with:
email:admin@showman.com
password:password

NB: Incase you encounter any issue edit the seeders and factories to reflect your App\Models\User instance as appropriate