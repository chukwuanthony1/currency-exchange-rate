<?php
// tests/Command/CreateUserCommandTest.php
namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:currency:rates');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
            'base_currency' => 'USD',
            'target_currencies' => ["NGN", "EUR", "ZEN", "WOR"]
            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
            // use brackets for testing array value,
            // e.g: '--some-option' => ['option_value'],
        ]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Fetching Exchange Rates for Base Currency: USD', $output);
        $this->assertStringContainsString('target_currency: NGN', $output);
        $this->assertStringContainsString('target_currency: EUR', $output);
        $this->assertStringContainsString('target_currency: ZEN', $output);
        $this->assertStringContainsString('target_currency: WOR', $output);

        // ...
    }
}
