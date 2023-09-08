<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{
    public function test(): void
    {
        require_once __DIR__ . "/../src/config/mysql.php";

        $GLOBALS['mysqli'] = $mysqli;

        require_once __DIR__ . "/../src/includes/auth.php";
        require_once __DIR__ . "/../src/config/systemSettings.php";
        require_once __DIR__ . "/../src/includes/classes/User.php";

        $user = new User("User1");

        $testExists = $user->exists;

        $this->assertSame(false, $testExists);
    }
}
