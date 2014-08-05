<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace PermAuth\Model;


use DeltaDb\Repository;
use User\Model\UserManager;
use User\Model\User;

class TokenManager extends Repository
{
    protected $metaInfo = [
        "perm_auth_tokens" => [
            "class"  => "\\PermAuth\\Model\\Token",
            "id"     => "id",
            "fields" => [
                "id",
                "token",
                "serial",
                "user",
                "created",
            ]
        ],
    ];

    /** @var  User */
    protected $user;
    protected $series;
    protected $time;

    /** @var  UserManager */
    protected $userManager;
    /** @var  SeriesManager */
    protected $seriesManager;

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

    /**
     * @return SeriesManager
     */
    public function getSeriesManager()
    {
        return $this->seriesManager;
    }

    /**
     * @param SeriesManager $seriesManager
     */
    public function setSeriesManager($seriesManager)
    {
        $this->seriesManager = $seriesManager;
    }

    public function create(array $data = null, $entityClass = null)
    {
        /** @var Token $item */
        $item = parent::create($data, $entityClass);
        $item->setSeriesManager($this->getSeriesManager());
        $item->setUserManager($this->getUserManager());
    }

    /**
     * @param Token $token
     */
    public function writeCookie($token)
    {

    }

    /**
     * @return Token|Null
     */
    public function readCookie()
    {

    }

    public function check($token)
    {

    }


} 