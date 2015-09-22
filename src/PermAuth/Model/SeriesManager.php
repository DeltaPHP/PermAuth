<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace PermAuth\Model;


use DeltaDb\EntityInterface;
use DeltaDb\Repository;
use HttpWarp\Cookie;
use User\Model\UserManager;
use User\Model\User;

class SeriesManager extends Repository
{
    const COOKIE_NAME = "paseries";

    protected $metaInfo = [
        "table" => "perm_auth_series",
        "class" => "\\PermAuth\\Model\\Series",
        "fields" => [
            "id",
            "series",
            "user",
            "created"
        ]
    ];

    /** @var  User */
    protected $user;
    protected $series;
    protected $time;

    /** @var  UserManager */
    protected $userManager;

    /** @var  TokenManager */
    protected $tokenManager;

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
     * @return TokenManager
     */
    public function getTokenManager()
    {
        if (is_callable($this->tokenManager)) {
            $this->tokenManager = call_user_func($this->tokenManager);
        }
        return $this->tokenManager;
    }

    /**
     * @param TokenManager $tokenManager
     */
    public function setTokenManager($tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function create(array $data = null, $entityClass = null)
    {
        /** @var Series $item */
        $item = parent::create($data, $entityClass);
        $item->setUserManager($this->getUserManager());
        $item->setTokenManager($this->getTokenManager());
        return $item;
    }

    /**
     * @param Series $series
     */
    public function writeCookie($series)
    {
        Cookie::setCookie(self::COOKIE_NAME, $series->getSeries(), time()+60*60*24*30*12);
    }

    /**
     * @return Series|null $series
     */
    public function readCookie()
    {
        $seriesCode = Cookie::getCookie(self::COOKIE_NAME);
        if (!$seriesCode) {
            return null;
        }
        $series = $this->findOne(["series" => $seriesCode]);
        if (!$series) {
            $this->deleteCookie();
            return null;
        }
        return $series;
    }

    public function deleteCookie()
    {
        Cookie::setCookie(self::COOKIE_NAME, "", time() - 3600);
    }

    public function delete(EntityInterface $entity)
    {
        $tm = $this->getTokenManager();
        $tm->clearTokens($entity);
        return parent::delete($entity);
    }


} 