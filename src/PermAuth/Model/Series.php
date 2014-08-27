<?php
/**
 * User: Vasiliy Shvakin (orbisnull) zen4dev@gmail.com
 */

namespace PermAuth\Model;


use DeltaCore\Prototype\AbstractEntity;
use User\Model\User;
use User\Model\UserManager;
use DeltaDb\EntityInterface;

class Series extends AbstractEntity implements EntityInterface
{
    /** @var  User */
    protected $user;
    protected $series;
    protected $created;

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
     * @return mixed
     */
    public function getSeries()
    {
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

    public function getTokens()
    {
        $tm = $this->getTokenManager();
        return $tm->find(["series" => $this->getId()]);
    }

}