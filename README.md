1) configure you bdd in .env (DATABASE_URL)
2) configure you smtp serveur in config/packages/swiftmailler.yaml
Exemple 
swiftmailer:

    transport: smtp
    
    auth_mode: login
    
    port: 587
    
    host: ssl0.ovh.net
    
    username: YOUUSERNAME
    
    password: YOUPASSWORD
    
    spool: { type: 'memory' }

3)open you console.

4)type : composer install

5) type : php bin/console doctrine:schema:update --force

6) type: php bin/console doctrine:fixtures:load

7) launch you app : php bin/console s:run
