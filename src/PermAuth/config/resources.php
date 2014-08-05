<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */
return [
    'permAuthSeriesManager' => function ($c) {
        $manager = new \PermAuth\Model\SeriesManager();
        $manager->setUserManager($c["userManager"]);
        return $manager;
    },
    "permAuthTokenManager"  => function ($c) {
        $manager = new \PermAuth\Model\TokenManager();
        $manager->setSeriesManager($c["permAuthSeriesManager"]);
        $manager->setUserManager($c["userManager"]);
        return $manager;
    },
];