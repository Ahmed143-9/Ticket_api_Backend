<?php

namespace App\Utilities\Enum;

/* To get the Keys,
 * Use: StatusEnum::getKeys()
 * To get the Values,
 * Use: StatusEnum::getValues()
 */

abstract class OperatorEnum extends BasicEnum
{
    // To call it anywhere, just call: StatusEnum::Active

    const Grameenphone = 'grameenphone';
    const Banglalink = 'banglalink';
    const Robi = 'robi';
    const Airtel = 'airtel';
    const Taletalk = 'teletalk';

}

