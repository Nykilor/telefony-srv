# telefony-srv
Server for my telephone app.
Will fill this later (the commands might be in bad order didn't check them),
quick setup: 
fill the database information in .env 
composer install 
php bin\console doctrine:schema:create 
php bin\console doctrine:schema:update --force 
php bin\console server:run 
visit localhost:8000/api for the list
use it to add at least one domain, you can use the dummy data that is in public by commenting the line 30,31 and uncommenting the line 32 in  App\Service\ActiveDirectoryFetch ( then no matter what is in the domain it will work I still work on doing a good query with the adldap2) 
