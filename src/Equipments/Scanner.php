<?php
declare(strict_types=1);
namespace James\Equipments;

use James\Events\{ Content, State\HasChange };
use Gitonomy\Git\{ Repository, Diff\FileChange };

class Scanner implements GearInterface
{
  public function on(): string
  {
    return HasChange::event;
  }

  public function do(): callable
  {
    return function (HasChange $event) {
      $OO7 = $event->getOO7();
      $storageDir = $OO7->get(Microfilm::STORAGE_KEY);
      $mission =  $OO7->getMission();

      $repoPath = "{$storageDir}/{$mission->getPathname()}/.git";
      $repo = new Repository($repoPath);

      foreach ($this->getChanges($repo) as $change) {
        $lines = $change->getLines();
        $ignoreNext = false;
        $additions = $modifications = $deletions = [];

        foreach ($lines as $key => list($type, $line)) {
          if ($ignoreNext) { $ignoreNext = false; continue; }

          if ($type === FileChange::LINE_REMOVE) {
            if (isset($lines[$key + 1]) && $lines[$key + 1][0] === FileChange::LINE_ADD) {
              $OO7->dispatch(Content::UPDATED,  [
                  'deleted' => $line,
                  'added' => $lines[$key + 1][1],
              ]);
              $ignoreNext = true;
              continue;
            }
            $OO7->dispatch(Content::DELETED, [ 'deleted' => $line ]);
          } elseif ($type === FileChange::LINE_ADD) {
            $OO7->dispatch(Content::ADDED, [ 'added' => $line ]);
          }
        }
      }
    };
  }

  private function getChanges(Repository $repo)
  {
    $diff = $repo->getDiff('HEAD^..master');
    $files = $diff->getFiles();
    $file = reset($files);
    return $file->getChanges();
  }
}
