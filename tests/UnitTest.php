<?php

declare(strict_types=1);

require_once __DIR__ . "/../src/includes/auth.php";
require_once __DIR__ . "/../src/config/mysql.php";
require_once __DIR__ . "/../src/config/systemSettings.php";
require_once __DIR__ . "/../src/includes/classes/User.php";

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
