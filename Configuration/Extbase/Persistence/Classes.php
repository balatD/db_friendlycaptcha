<?php

return [
    \In2code\Femanager\Domain\Model\User::class => [
        'subclasses' => [
            '\BalatD\FriendlyCaptcha\Domain\Model\User' => \BalatD\FriendlyCaptcha\Domain\Model\User::class,
        ],
    ],
    \BalatD\FriendlyCaptcha\Domain\Model\User::class => [
        'tableName' => 'fe_users',
        'recordType' => 0,
    ],
];