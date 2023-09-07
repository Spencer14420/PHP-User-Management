<?php

declare(strict_types=1);

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/mysql.php";
require_once __DIR__ . "/../config/systemSettings.php";
require_once __DIR__ . "/../includes/classes/User.php";

use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{
    public function test(): void
    {
        $user = new User("User1");

        $testExists = $user->exists;

        $this->assertSame(false, $testExists);
    }
}
