# Laravel API

Prérequis :

-   installer composer
-   avoir un serveur en local (WAMP par exemple)

---

Lancement :

-   vérifier le .env
-   `composer install`
-   si nécessaire lancer `php artisan jwt:secret` (génération de la clé JWT Auth dans le .env) 
(https://hackthestuff.com/article/laravel-8-jwt-authentication-tutorial)
-   `php artisan serve`

---

Utils :

-   lancer les migrations : `php artisan migrate:fresh`
-   lancer les données de test : `php artisan db:seed`
-   charset bdd recommandé : utf8mb4_general_ci
-   suppression du cache : `composer dump-autoload`
