<?php
namespace CSY2028;
class DatabaseTable {
	private $pdo;
	private $table;
	private $primaryKey;

	public function __construct($pdo, $table, $primaryKey = 'id') {
		$this->pdo = $pdo;
		$this->table = $table;
		$this->primaryKey = $primaryKey;
	}


	public function insert($record) {
        $keys = array_keys($record);

        $values = implode(', ', $keys);
        $valuesWithColon = implode(', :', $keys);

        $query = 'INSERT INTO ' . $this->table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ')';

        $stmt = $this->pdo->prepare($query);

        return $stmt->execute($record);
	}

	public function update($record) {
         $query = 'UPDATE ' . $table . ' SET ';

         $parameters = [];
         foreach ($record as $key => $value) {
                $parameters[] = $key . ' = :' .$key;
         }

         $query .= implode(', ', $parameters);
         $query .= ' WHERE ' . $this->primaryKey . ' = :primaryKey';

         $record['primaryKey'] = $record[$this->primaryKey];

         $stmt = $this->pdo->prepare($query);

         $stmt->execute($record);
	}

	public function save($record) {
		$success = $this->insert($record);

        if (!$success) {
            $this->update($record);
        }
	}

	public function delete($id) {
		$stmt = $this->pdo->prepare('DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :value');
		$criteria = [
			'value' => $id
		];

		$stmt->execute($criteria);
	}

	public function findById($id) {
		$stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :value');
		$criteria = [
			'value' => $id
		];

		$stmt->execute($criteria);

		return $stmt->fetch();
	}

	public function find($field, $value) {
		$stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = :value');
		$criteria = [
			'value' => $value
		];

		$stmt->execute($criteria);

		return $stmt->fetchAll();
	}
}
