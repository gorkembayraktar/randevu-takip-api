<?php
namespace App\Enum;

use App\Trait\EnumToArray;

enum AppointmentEnum:string
{
    use EnumToArray;
    
    case WAIT = 'wait';
    case COMPLETE = 'complete';
    case REJECT = 'reject';
    case CANCEL = "cancel";
}