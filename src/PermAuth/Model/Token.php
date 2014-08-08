<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace PermAuth\Model;


use DeltaDb\AbstractEntity;
use DeltaDb\EntityInterface;
use User\Model\User;
use User\Model\UserManager;

class Token extends AbstractEntity
{
    /** @var  User */
    protected $user;
    protected $series;
    protected $token;
    protected $created;

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

    /**
     * @return Series
     */
    public function getSeries()
    {
        if (!is_null($this->series) && !$this->series instanceof EntityInterface) {
            $this->series = $this->getSeriesManager()->findById($this->series);
        }
        return $this->series;
    }

    /**
     * @param mixed $series
     */
    public function setSeries($series)
    {
        $this->series = $series;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        if (!empty($this->created) && !$this->created instanceof \DateTime) {
            $this->created = new \DateTime($this->created);
        }
        return $this->created;
    }

    /**
     * @param mixed $time
     */
    public function setCreated($time)
    {
        $this->created = $time;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        if (!is_null($this->user) && !$this->user instanceof EntityInterface) {
            $this->user  = $this->getUserManager()->findById($this->user);
        }
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}