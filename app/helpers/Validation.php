<?php
class Validation {
    private array $errors = [];

    public function validate(array $input, array $rules): bool {
        $this->errors = [];
        foreach ($rules as $field => $ruleStr) {
            $value = $input[$field] ?? '';
            foreach (explode('|', $ruleStr) as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }
        return empty($this->errors);
    }

    private function applyRule(string $field, $value, string $rule): void {
        [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);
        $label = ucfirst(str_replace('_', ' ', $field));
        switch ($ruleName) {
            case 'required':
                if ($value === '' || $value === null) $this->errors[$field] = "$label wajib diisi.";
                break;
            case 'min':
                if (strlen((string)$value) < (int)$param) $this->errors[$field] = "$label minimal $param karakter.";
                break;
            case 'max':
                if (strlen((string)$value) > (int)$param) $this->errors[$field] = "$label maksimal $param karakter.";
                break;
            case 'exact':
                if (strlen((string)$value) !== (int)$param) $this->errors[$field] = "$label harus $param karakter.";
                break;
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) $this->errors[$field] = "$label tidak valid.";
                break;
            case 'numeric':
                if ($value && !is_numeric($value)) $this->errors[$field] = "$label harus angka.";
                break;
            case 'date':
                if ($value && !strtotime($value)) $this->errors[$field] = "$label bukan tanggal valid.";
                break;
            case 'in':
                if ($value && !in_array($value, explode(',', $param))) $this->errors[$field] = "$label tidak valid.";
                break;
            case 'unique':
                [$table, $col, $exceptId] = array_pad(explode(',', $param), 3, null);
                $db = Database::getInstance()->getConnection();
                $sql = "SELECT COUNT(*) FROM $table WHERE $col = ?";
                $params = [$value];
                if ($exceptId) { $sql .= " AND id != ?"; $params[] = $exceptId; }
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                if ($stmt->fetchColumn() > 0) $this->errors[$field] = "$label sudah digunakan.";
                break;
        }
    }

    public function errors(): array { return $this->errors; }
    public function fails(): bool { return !empty($this->errors); }
    public function firstError(): string { return reset($this->errors) ?: ''; }
}
