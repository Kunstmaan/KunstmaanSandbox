sudo chmod -R 777 app/cache/
sudo chmod -R 777 app/logs/
rm -R app/cache/*
rm -R app/logs/*
php app/console --force doctrine:schema:drop
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load
php app/console assets:install web
php app/console assetic:dump
sudo chmod -R 777 app/cache/
sudo chmod -R 777 app/logs/

