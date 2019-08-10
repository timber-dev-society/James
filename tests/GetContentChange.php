<?php
declare(strict_types=1);
namespace James\Tests;

use PHPUnit\Framework\TestCase;
use Cz\Git\GitRepository;
use Symfony\Component\Filesystem\Filesystem;
use Gitonomy\Git\{ Repository, Diff\FileChange };

final class GetContentChangeTest extends TestCase
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
    file_put_contents(self::STORE, self::CONTENT . PHP_EOL);
    $this->repo->addAllChanges();
    $this->repo->commit('1');
  }

public function testAdd()
  {
    $this->init();
    $before = $this->repo->getLastCommitId();

    file_put_contents(self::STORE, self::CONTENT . PHP_EOL . 'TUT' . PHP_EOL);

    $this->repo->addAllChanges();
    $this->repo->commit('2');

    $after = $this->repo->getLastCommitId();


    $tut = $this->repo->execute(['diff', $before . '..' . $after]);

    $repository = new Repository(self::GIT_STORE);
    $diff = $repository->getDiff($before . '..' . $after);
    $files = $diff->getFiles();
    $file = reset($files);
    $this->assertEquals($file->getAdditions(), 1);
    $this->assertEquals($file->getDeletions(), 0);

    foreach($file->getChanges() as $change) {
      foreach ($change->getLines() as $data) {
        list($type, $line) = $data;
        if ($type === FileChange::LINE_ADD) {
          $this->assertEquals($line, 'TUT');
        }

      }
    }
  }

  public function testDelete()
  {
    $this->init();
    $before = $this->repo->getLastCommitId();
    $content = <<<TXT
Lorem ipsum dolor sit amet,
sed do eiusmod tempor incididunt.
TXT;

    file_put_contents(self::STORE, $content . PHP_EOL);

    $this->repo->addAllChanges();
    $this->repo->commit('2');

    $after = $this->repo->getLastCommitId();
    $repository = new Repository(self::GIT_STORE);
    $diff = $repository->getDiff($before . '..' . $after);
    $files = $diff->getFiles();
    $file = reset($files);
    $this->assertEquals($file->getAdditions(), 0);
    $this->assertEquals($file->getDeletions(), 1);

    foreach($file->getChanges() as $change) {
      $lines = $change->getLines();
      $ignoreNext = false;

      foreach ($lines as $key => $data) {

        list($type, $line) = $data;
        if ($ignoreNext) {
          $ignoreNext = false;
          continue;
        } elseif ($type === FileChange::LINE_REMOVE && isset($lines[$key + 1]) && $lines[$key + 1][0] === FileChange::LINE_ADD) {
          $this->assertTrue(false);
          $ignoreNext = true;
        } elseif ($type === FileChange::LINE_REMOVE) {
          $this->assertEquals($line, 'consectetur adipiscing elit,');
        } elseif ($type === FileChange::LINE_ADD) {
          $this->assertTrue(false);
        }
      }
    }
  }

  public function testUpdate()
  {
    $this->init();
    $before = $this->repo->getLastCommitId();

    file_put_contents(self::STORE, self::CONTENT . 'TUT' . PHP_EOL);

    $this->repo->addAllChanges();
    $this->repo->commit('2');

    $after = $this->repo->getLastCommitId();
    $repository = new Repository(self::GIT_STORE);
    $diff = $repository->getDiff($before . '..' . $after);
    $files = $diff->getFiles();
    $file = reset($files);
    $this->assertEquals($file->getAdditions(), 1);
    $this->assertEquals($file->getDeletions(), 1);

    foreach($file->getChanges() as $change) {
      $lines = $change->getLines();
      $ignoreNext = false;

      foreach ($lines as $key => $data) {

        list($type, $line) = $data;
        if ($ignoreNext) {
          $ignoreNext = false;
          continue;
        } elseif ($type === FileChange::LINE_REMOVE && isset($lines[$key + 1]) && $lines[$key + 1][0] === FileChange::LINE_ADD) {
          $this->assertEquals($line, 'sed do eiusmod tempor incididunt.');
          $this->assertEquals($lines[$key + 1][1], 'sed do eiusmod tempor incididunt.TUT');
          $this->assertEquals(str_replace($line, '', $lines[$key + 1][1]), 'TUT');
          $ignoreNext = true;
        } elseif ($type === FileChange::LINE_REMOVE) {
          $this->assertTrue(false);
        } elseif ($type === FileChange::LINE_ADD) {
          $this->assertTrue(false);
        }
      }
    }
  }
}