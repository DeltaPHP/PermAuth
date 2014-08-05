<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace PermAuth\Model;


use DeltaDb\Repository;
use User\Model\UserManager;
use User\Model\User;

class SeriesManager extends Repository
{
    protected $metaInfo = [
        "perm_auth_series" => [
            "class"  => "\\PermAuth\\Model\\Series",
            "id"     => "id",
            "fields" => [
                "id",
                "series",
                "user",
                "created"
            ]
        ],
    ];

    /** @var  User */
    protected $user;
    protected $series;
    protected $time;

    /** @var  UserManager */
    protected $userManager;

    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @param UserManager $userManager
     */
    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
    }

    public function create(array $data = null, $entityClass = null)
    {
        /** @var Series $item */
        $item = parent::create($data, $entityClass);
        $item->setUserManager($this->getUserManager());
    }

    /**
     * @param Series $series
     */
    public function writeCookie($series)
    {

    }

    /**
     * @return Series|null $series
     */
    public function readCookie()
    {

    }

    public function check($token)
    {

    }



} 