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

  public function do(): callback
  {
    return function (HasChange $event) {
      $OO7 = $event->getOO7();
      $mission = $james->getMission();

      $repoPath = "{$this->dir}/{$mission->getPathname()}/.git";
      $repo = new Repository($repoPath);
      $diff = $repository->getDiff('HEAD^..master');

      foreach($file->getChanges() as $change) {
        $lines = $change->getLines();
        $ignoreNext = false;

        foreach ($lines as $key => $data) {

          list($type, $line) = $data;
          if ($ignoreNext) {
            $ignoreNext = false;
            continue;
          } elseif ($type === FileChange::LINE_REMOVE && isset($lines[$key + 1]) && $lines[$key + 1][0] === FileChange::LINE_ADD) {
            $OO7->dispatch(Content\Updated::event, [
              'removed' => $line,
              'added' => $lines[$key + 1][1],
            ]);
            $ignoreNext = true;
          } elseif ($type === FileChange::LINE_REMOVE) {
            $OO7->dispatch(Content\Deleted::event, [
              'removed' => $line,
            ]);
          } elseif ($type === FileChange::LINE_ADD) {
            $OO7->dispatch(Content\Added::event, [
              'added' => $line,
            ]);
          }
        }
      }
    };
  }
}
