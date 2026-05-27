<?php
class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(array $where = [], string $order = '', int $limit = 0, int $offset = 0): array {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        if ($where) {
            $clauses = array_map(fn($k) => "$k = ?", array_keys($where));
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
            $params = array_values($where);
        }
        if ($order) $sql .= " ORDER BY $order";
        if ($limit) $sql .= " LIMIT $limit";
        if ($offset) $sql .= " OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne(array $where): ?array {
        $result = $this->findAll($where, '', 1);
        return $result[0] ?? null;
    }

    public function findById(int $id): ?array {
        return $this->findOne(['id' => $id]);
    }

    public function insert(array $data): int {
        $cols = implode(', ', array_keys($data));
        $places = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($places)");
        $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sets = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $sets WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count(array $where = []): int {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];
        if ($where) {
            $clauses = array_map(fn($k) => "$k = ?", array_keys($where));
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
            $params = array_values($where);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function query(string $sql, array $params = []): array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute(string $sql, array $params = []): bool {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
