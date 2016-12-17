<?php
/**
 * Created by PhpStorm.
 * User: ivanovkbg
 * Date: 11.12.2016 г.
 * Time: 20:35
 */

namespace AppBundle\Services;


class HelpService
{
    const MAX_PASSWORD_RAND = 999999;
    const MIN_PASSWORD_RAND = 333333;

    public function randomPassword()
    {
        $password_random = rand(self::MIN_PASSWORD_RAND, self::MAX_PASSWORD_RAND);
        return $password_random;
    }

}