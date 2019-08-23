<?php
declare(strict_types=1);
namespace James\Equipments;

use Symfony\Component\Filesystem\Filesystem;
use James\Events\{ Collect\After, State};
use Cz\Git\GitRepository;

class Microfilm implements GearInterface
{
  const STORAGE_KEY = 'storageDir';
  private $fs;
  private $dir;
  private $mission;
  private $rawContent;
  private $content;

  public function __construct(string $storageDir)
  {
    $this->fs = new Filesystem();
    $this->dir = $storageDir;
    if (!$this->fs->exists($this->dir)) {
      $this->fs->mkdir($this->dir);
    }
  }

  public function on(): string
  {
    return After::event;
  }

  public function do(): callable
  {
    return function (After $event) {
      $OO7 = $event->getOO7();
      $OO7->save(self::STORAGE_KEY, $this->dir);

      $this->mission = $OO7->getMission();
      $this->rawContent = $event->getDom();
      $this->content = $event->getContent();

      $this->saveRawContent();
      $repo = $this->getRepoContent();

      $hasChange = $repo->hasChanges();

      $repo->addAllChanges();
      $repo->commit('Bond. James Bond');

      if ($hasChange) {
        $OO7->dispatch(State\HasChange::event);
        return;
      }
      $OO7->dispatch(State\HasNotChange::event);
    };
  }

  private function saveRawContent()
  {
    $path = "{$this->dir}/{$this->mission->getRawPathname()}";
    $file = "$path/content";

    $repo = $this->getRepo($path, $file);

    file_put_contents($file, $this->rawContent);
    $repo->addAllChanges();
    $repo->commit('Bond. James Bond');
  }

  private function getRepoContent()
  {
    $path = "{$this->dir}/{$this->mission->getPathname()}";
    $file = "$path/content";

    $repo = $this->getRepo($path, $file);
    file_put_contents($file, $this->content);

    return $repo;
  }

  private function getRepo($path, $file)
  {
    if (!$this->fs->exists($path)) {
      $this->fs->mkdir($path);
      $repo = GitRepository::init($path);
      $this->fs->touch($file);

      return $repo;
    }

    return new GitRepository($path);
  }
}
