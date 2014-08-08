<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace PermAuth\Model;


use User\Model\UserManager;
use User\Model\User;

class Authenticator 
{
    /** @var  TokenManager */
    protected $tokenManager;
    /** @var  SeriesManager */
    protected $seriesManager;
    /** @var  UserManager */
    protected $userManager;

    /**
     * @return TokenManager
     */
    public function getTokenManager()
    {
        return $this->tokenManager;
    }

    /**
     * @param TokenManager $tokenManager
     */
    public function setTokenManager($tokenManager)
    {
        $this->tokenManager = $tokenManager;
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

    public function authenticate()
    {
        $tm = $this->getTokenManager();
        $sm = $this->getSeriesManager();
        $token = $tm->readCookie();
        $series = $sm->readCookie();
        if (!$this->check($token, $series)) {
            return false;
        }
        return $token->getUser();
    }

    /**
     * @param Token $token
     * @param Series $series
     * @return bool
     */
    public function check($token, $series)
    {
        $tm = $this->getTokenManager();
        $sm = $this->getSeriesManager();
        if (!$token) {
            return false;
        }
        $tokenSeries = $token->getSeries();
        if (!$series || !$tokenSeries) {
            $this->deletePair($token);
            return false;
        }
        if ($tokenSeries->getId() !== $series->getId()) {
            $this->deletePair($token, $tokenSeries);
            return false;
        }
        return true;
    }

    public function deletePair($token = null, $series = null)
    {
        if ($series) {
            $sm = $this->getSeriesManager();
            $sm->delete($token);
            $sm->deleteCookie();
        }
        if ($token) {
            $tm = $this->getTokenManager();
            $tm->delete($token);
            $tm->deleteCookie();
        }
    }

    /**
     * @param User $user
     */
    public function setToken($user)
    {
        $sm = $this->getSeriesManager();
        $series = $sm->readCookie() ?: $sm->create([
            "user" => $user,
            "created" => new \DateTime(),
            "series" => md5(mt_rand()),
        ]);
        $sm->save($series);
        $sm->writeCookie($series);
        $tm = $this->getTokenManager();
        $tm->clearTokens($series);
        $token = $tm->generateToken($series);
        $tm->writeCookie($token);
    }

    public function logout()
    {
        $sm = $this->getSeriesManager();
        $series = $sm->readCookie();
        if ($series) {
            $tm = $this->getTokenManager();
            $tm->deleteCookie();
            $sm->deleteCookie();
            $sm->delete($series);
        }
    }
} 