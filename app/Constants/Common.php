<?php


namespace App\Constants;


class Common
{
    const USER_REQUEST = [
        'ACTIVE_ACCOUNT' => 'ACTIVE_ACCOUNT',
        'FORGET_PASSWORD' => 'FORGET_PASSWORD'
    ];

    const CLIENT_REDIRECT_URI = [
        'ACTIVE_ACCOUNT' => '/active-account'
    ];

    const REQUEST_ACCOUNT_TOKEN_LENGTH = 60;

    const ACCOUNT_REQUEST_STATUS = [
        'NOT_MATCH_TYPE' => 'NOT_MATCH_TYPE',
        'NOT_MATCH_USER' => 'NOT_MATCH_USER',
        'TOKEN_EXPIRED' => 'TOKEN_EXPIRED',
        'INVALID_TOKEN' => 'INVALID_TOKEN',
        'VALID' => 'VALID'
    ];

    const ROOM_TYPES = [
        'MOTEL' => 'MOTEL',
        'APARTMENT' => 'APARTMENT',
        'HOUSE' => 'HOUSE',
        'OFFICE' => 'OFFICE'
    ];

    const FURNITURE_TYPES = [
        'FULL' => 'FULL',
        'PART' => 'PART',
        'NONE' => 'NONE'
    ];
    const POST_STATUSES = [
        'PENDING' => 'PENDING',
        'PUBLISH' => 'PUBLISH',
        'DRAFT' => 'DRAFT',
        'REJECTED' => 'REJECTED'
    ];
}
