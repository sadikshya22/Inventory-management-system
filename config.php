<?php 
require('stripe-php-master/init.php');

$publishableKey="pk_test_51NVSz0FvHe9IBz9GYXcNepoR3XxEEeOepcJXhPkQjCWNueVhv2yY3oiP0mMeHtIVAU4ymsIvbNOlbzDscY2CG69z00G8fAJu6Q";

$secretKey="sk_test_51NVSz0FvHe9IBz9G9zP7VvfHyWLWk7JHiTiBy7RxPQldFhh4li3OZwqxwVV40akDbhsb6nQLAx7SKpJU6mgjSjBr00nWWoItO7";

\Stripe\Stripe::setApiKey($secretKey);

?>