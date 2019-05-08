# telefony-srv
Server for my telephone app.  
Will fill this later (the commands might be in bad order didn't check them),  

Quick setup:  
=====
 * fill the database information in .env  
 * composer install  
 * You got to use my fork of adldap2 to this project to work (missing some methods the pull request is on)  
 * php bin\console doctrine:schema:create  
 * php bin\console doctrine:schema:update --force  
 * php bin\console server:run  
 * visit localhost:8000/api for the list 
 * use (POST) api/domain to add at least one domain 
 * You can now use the (POST) /api/domain/ldap_fetch/{id}, you can also use the dummy data (serialized result from a domain) that is in public by commenting the line 30,31 and uncommenting the line 32 in  App\Service\ActiveDirectoryFetch ( then no matter what is in the domain it will work I still work on doing a good query with the adldap2) 
