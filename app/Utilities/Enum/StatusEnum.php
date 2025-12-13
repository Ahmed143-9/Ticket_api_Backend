<?php

namespace App\Utilities\Enum;

/* To get the Keys,
 * Use: StatusEnum::getKeys()
 * To get the Values,
 * Use: StatusEnum::getValues()
 */

abstract class StatusEnum extends BasicEnum
{
    // To call it anywhere, just call: StatusEnum::Active

    const Inactive = 0;
    const Active = 1;


}

