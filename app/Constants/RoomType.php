<?php

namespace App\Constants;

class RoomType
{
    public const SINGLE = 'single';
    public const MOTEL = 'motel';
    public const APARTMENT = 'apartment';
    public const HOUSE = 'house';

    public const TYPES = [
        self::SINGLE,
        self::MOTEL,
        self::APARTMENT,
        self::HOUSE,
    ];
}
