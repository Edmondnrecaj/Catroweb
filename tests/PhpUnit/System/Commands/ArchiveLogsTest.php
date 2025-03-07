<?php

declare(strict_types=1);

namespace Tests\PhpUnit\System\Commands;

use App\System\Commands\Maintenance\ArchiveLogsCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
#[CoversClass(ArchiveLogsCommand::class)]
class ArchiveLogsTest extends KernelTestCase
{
  public function testArchiveLogs(): void
  {
    // setup app
    $kernel = static::createKernel();
    $container = static::getContainer();
    $application = new Application($kernel);
    $command = $application->find('catrobat:logs:archive');

    $log_dir = (string) $container->getParameter('catrobat.logs.dir');

    // create test log folder under TestData -> we don't want to remove our real logs
    if (!file_exists($log_dir)) {
      mkdir($log_dir);
    }

    // run command
    $commandTester = new CommandTester($command);
    $return = $commandTester->execute([]);
    $this->assertEquals(0, $return);

    // check if directory is not empty
    $this->assertNotEmpty(array_diff(scandir($log_dir), ['.', '..', '.gitignore']),
      'No file in log directory got archived.');
  }
}
