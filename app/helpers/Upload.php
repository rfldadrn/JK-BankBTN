<?php
class Upload {
    public static function file(string $inputName, string $folder = 'lampiran'): ?string {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $file = $_FILES[$inputName];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_TYPES)) {
            throw new Exception("Tipe file tidak diizinkan. Hanya: " . implode(', ', ALLOWED_TYPES));
        }
        if ($file['size'] > MAX_FILE_SIZE) {
            throw new Exception("Ukuran file melebihi batas 5MB.");
        }
        $dir = UPLOAD_PATH . $folder . '/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
            throw new Exception("Gagal menyimpan file.");
        }
        return $folder . '/' . $filename;
    }

    public static function delete(?string $path): void {
        if ($path && file_exists(UPLOAD_PATH . $path)) {
            unlink(UPLOAD_PATH . $path);
        }
    }
}
