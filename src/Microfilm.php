<?php

namespace James;

use PDO;

class Microfilm
{
  private $db;

  public function __construct($storageDir)
  {
    $installed = file_exists($storageDir . '/database.sqlite');
    $this->db = new PDO('sqlite:' . $storageDir . '/database.sqlite');
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (!$installed) {
      $this->install();
    }
  }

  public function get($id)
  {
    $stmt = $this->db->prepare('SELECT id, state, content FROM site_state WHERE id=?');
    $stmt->setFetchMode(PDO::FETCH_CLASS, Section::class);
    $stmt->execute([$id]);
    return $stmt->fetch();
  }

  public function create(Section $section)
  {
    $stmt = $this->db->prepare('INSERT INTO site_state (id, state, content) VALUES (:id, :state, :content)');
    $stmt->execute([
        'id' => $section->id,
        'state' => $section->state,
        'content' => $section->content,
    ]);
  }

  public function
  update(Section $section)
  {
    $stmt = $this->db->prepare('UPDATE site_state SET state=:state, content=:content WHERE id=:id');
    $stmt->execute([
        'id' => $section->id,
        'state' => $section->state,
        'content' => $section->content,
    ]);
  }

  private function install()
  {
    $createTable = <<<SQL
CREATE TABLE IF NOT EXISTS site_state (
    id TEXT PRIMARY KEY,
    state TEXT,
    content TEXT
)
SQL;
    $this->db->exec($createTable);

    $stmt = $this->db->prepare('INSERT INTO site_state (id) VALUES (?)');
    $stmt->execute(['global']);
  }
}