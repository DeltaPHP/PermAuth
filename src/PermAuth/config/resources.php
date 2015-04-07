<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */
return [
    'permAuthSeriesManager' => function ($c) {
        $manager = new \PermAuth\Model\SeriesManager();
        $manager->setUserManager($c["userManager"]);
        $manager->setTokenManager($c->lazyGet("permAuthTokenManager"));
        return $manager;
    },
    "permAuthTokenManager"  => function ($c) {
        $manager = new \PermAuth\Model\TokenManager();
        $manager->setSeriesManager($c->lazyGet("permAuthSeriesManager"));
        $manager->setUserManager($c["userManager"]);
        return $manager;
    },
    "permAuthenticator" => function($c) {
        $manager = new \PermAuth\Model\Authenticator();
        $manager->setTokenManager($c["permAuthTokenManager"]);
        $manager->setSeriesManager($c["permAuthSeriesManager"]);
        $manager->setUserManager($c["userManager"]);
        return $manager;
    }
];