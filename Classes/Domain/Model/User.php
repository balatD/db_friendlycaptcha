<?php

namespace BalatD\FriendlyCaptcha\Domain\Model;

class User extends \In2code\Femanager\Domain\Model\User
{

    /**
     * @var int
     */
    protected $friendlyCaptcha = 0;

    /**
     * @return int
     */
    public function getFriendlyCaptcha(): int
    {
        return $this->friendlyCaptcha;
    }

    /**
     * @param int $friendlyCaptcha
     */
    public function setFriendlyCaptcha(int $friendlyCaptcha): void
    {
        $this->friendlyCaptcha = $friendlyCaptcha;
    }


}