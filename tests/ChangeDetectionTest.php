<?php
declare(strict_types=1);
namespace James\Tests;

use PHPUnit\Framework\TestCase;
use Cz\Git\GitRepository;
use Symfony\Component\Filesystem\Filesystem;

final class ChangeDetectionTest extends TestCase
{
  private const REPO = __DIR__ . '/repo';
  private const STORE = self::REPO . '/content';
  private const GIT_STORE = self::REPO . '/.git';
  private const CONTENT = <<<TXT
Lorem ipsum dolor sit amet,
consectetur adipiscing elit,
sed do eiusmod tempor incididunt.
TXT;

  private $repo;

  public function testInit()
  {
    $fs = new Filesystem();

    if ($fs->exists(self::REPO)) {
      $fs->remove(self::REPO);
    }
    $fs->mkdir(self::REPO);

    $this->repo = GitRepository::init(self::REPO);
    file_put_contents(self::STORE, self::CONTENT);

    $this->assertTrue($this->repo->hasChanges());
    $this->repo->addAllChanges();
    $this->repo->commit('1');

    $this->assertNotNull($this->repo->getLastCommitId());
  }

  public function testUpdate()
  {
    file_put_contents(self::STORE, PHP_EOL . 'TUT', FILE_APPEND);

    $this->assertTrue($this->repo->hasChanges());
    $this->repo->addAllChanges();
    $this->repo->commit('2');

    $this->assertNotNull($this->repo->getLastCommitId());
  }
}
