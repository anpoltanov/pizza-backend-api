# pizza-backend-api
This is a test task for Innoscripta GmbH. 
The goal was to make simple and scalable application with functions of menu page, product cart, calculating total and placing orders, orders history for authenticated users, currency switching.
The application consists of 2 separate parts which are backend API and frontend application.

This is a backend REST API. It is built on top of Symfony framework 5.1 and Doctrine ORM. 

Build steps:
1. clone git repository
2. composer install --no-dev --optimize-autoloader
3. create .env.local (place here db credentials or use Symfony secrets instead)
4. php bin/console cache:clear
5. php bin/console doctrine:schema:create
