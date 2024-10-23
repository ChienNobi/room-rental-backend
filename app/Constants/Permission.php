<?php


namespace App\Constants;


class Permission
{
    /** Example permissions */
//    const POST_LIST = 'post.list';
//    const POST_CREATE = 'post.create';
//    const POST_VIEW = 'post.view';
//    const POST_EDIT = 'post.edit';
//    const POST_DELETE = 'post.delete';

    public static function getAdminPermissions()
    {
        return [
//            static::POST_LIST,
//            static::POST_CREATE,
//            static::POST_VIEW,
//            static::POST_EDIT,
//            static::POST_DELETE,
        ];
    }

    public static function getStaffPermissions()
    {
        return [
//            static::POST_LIST,
//            static::POST_VIEW,
        ];
    }

    /**
     * For permissions seeding
     *
     * @return array
     */
    public static function getAllPermissions()
    {
        return [
//            static::POST_LIST,
//            static::POST_CREATE,
//            static::POST_VIEW,
//            static::POST_EDIT,
//            static::POST_DELETE,
        ];
    }
}
