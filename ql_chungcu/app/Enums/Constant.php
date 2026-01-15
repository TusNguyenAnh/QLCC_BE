<?php

namespace App\Enums;

enum Constant: string
{
    case REVENUE = 'revenue';
    case EXPENSE = 'expense';
    case APPROVED = 'APPROVED';
    case PENDING = 'PENDING';
    case REJECT = 'REJECTED';
    case UNFINISHED = 'UNFINISHED';
    case MEMBER = 'member';

}
