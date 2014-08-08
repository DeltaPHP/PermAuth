<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace PermAuth\Model;


use DeltaDb\Repository;
use User\Model\UserManager;
use User\Model\User;
use HttpWarp\Cookie;

class TokenManager extends Repository
{
    const COOKIE_NAME = "atoken";
    const MAX_TIME = 7;
    protected $metaInfo = [
        "perm_auth_tokens" => [
            "class"  => "\\PermAuth\\Model\\Token",
            "id"     => "id",
            "fields" => [
                "id",
                "token",
                "series",
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
        if (is_callable($this->seriesManager)) {
            $this->seriesManager = call_user_func($this->seriesManager);
        }
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
        return $item;
    }

    /**
     * @param Token $token
     */
    public function writeCookie($token)
    {
        Cookie::setCookie(self::COOKIE_NAME, $token->getToken(), time()+60*60*24*7);
    }

    /**
     * @return Token|ErrorToken|Null
     */
    public function readCookie()
    {
        $tokenCode = Cookie::getCookie(self::COOKIE_NAME);
        if (!$tokenCode) {
            return null;
        }
        /** @var Token $token */
        $token = $this->findOne(["token" => $tokenCode]);
        if (!$token) {
            $this->deleteCookie();
            return null;
        }
        $tokenTimeDiff = $token->getCreated()->diff(new \DateTime());
        if ($tokenTimeDiff->days > self::MAX_TIME) {
            $this->delete($token);
            $this->deleteCookie();
            return false;
        }
        return $token;
    }

    public function deleteCookie()
    {
        Cookie::setCookie(self::COOKIE_NAME, "", time() - 3600);
    }

    /**
     * @param Series $series
     */
    public function generateToken($series)
    {
        $token = $this->create([
            "series" => $series,
            "user" => $series->getUser(),
            "created" => new \DateTime(),
            "token" => hash("md5", mt_rand()),
        ]);
        $this->save($token);
        return $token;
    }

    /**
     * @param Series $series
     * @return bool
     */
    public function clearTokens($series)
    {
        $tokens = $series->getTokens();
        foreach($tokens as $token) {
            $this->delete($token);
        }
        return true;
    }
}