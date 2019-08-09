<?php
declare(strict_types=1);
namespace James\Tests;

use PHPUnit\Framework\TestCase;
use Cz\Git\GitRepository;
use Symfony\Component\Filesystem\Filesystem;

final class GitDetectionTest extends TestCase
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
  private $fs;

  public function init()
  {
    $fs = new Filesystem();

    if ($fs->exists(self::REPO)) { $fs->remove(self::REPO); }
    $fs->mkdir(self::REPO);

    $this->repo = GitRepository::init(self::REPO);
    file_put_contents(self::STORE, self::CONTENT);
    $this->repo->addAllChanges();
    $this->repo->commit('1');
  }

  public function testInit()
  {
    $fs = new Filesystem();

    if ($fs->exists(self::REPO)) { $fs->remove(self::REPO); }
    $fs->mkdir(self::REPO);

    $repo = GitRepository::init(self::REPO);

    file_put_contents(self::STORE, self::CONTENT);

    $this->assertTrue($repo->hasChanges());
    $repo->addAllChanges();
    $repo->commit('1');

    $this->assertNotNull($repo->getLastCommitId());
  }

  public function testUpdate()
  {
    $this->init();
    $before = $this->repo->getLastCommitId();

    file_put_contents(self::STORE, self::CONTENT . PHP_EOL . 'TUT');

    $this->assertTrue($this->repo->hasChanges());
    $this->repo->addAllChanges();
    $this->repo->commit('2');

    $after = $this->repo->getLastCommitId();
    $this->assertNotEquals($before, $after);
  }

  public function testNoChanges()
  {
    $this->init();

    file_put_contents(self::STORE, self::CONTENT);

    $this->assertFalse($this->repo->hasChanges());
  }

  public function testRemove()
  {
    $this->init();
    $before = $this->repo->getLastCommitId();
    $remove = <<<TXT
Lorem ipsum dolor sit amet,
sed do eiusmod tempor incididunt.
TXT;

    file_put_contents(self::STORE, $remove);

    $this->assertTrue($this->repo->hasChanges());
    $this->repo->addAllChanges();
    $this->repo->commit('2');

    $after = $this->repo->getLastCommitId();
    $this->assertNotEquals($before, $after);
  }
}
