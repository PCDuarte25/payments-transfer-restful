<?php

namespace App\Enums;

/**
 * Enum UserType
 *
 * Defines the available user categories within the application.
 * These types are used to determine business logic permissions,
 * such as transaction eligibility.
 *
 * @package App\Enums
 */
enum UserType: string
{
    /**
     * Represents a standard person who can both send and receive transfers.
     */
    case COMMON = 'common';

    /**
     * Represents a business or seller account.
     * In this domain, merchants are typically restricted to only receiving transfers.
     */
    case MERCHANT = 'merchant';
}
