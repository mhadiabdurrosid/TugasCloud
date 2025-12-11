<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    echo json_encode(['ok' => false, 'message' => 'Forbidden']);
    exit;
}

require_once __DIR__ . '/../model/Koneksi.php';
$db   = new koneksi();
$conn = $db->getConnection();

$action = $_GET['action'] ?? '';

function fail($msg) {
    echo json_encode(['ok' => false, 'message' => $msg]);
    exit;
}


/* =======================================================
   1. LIST FOLDERS  (dipakai index.php & cloud.php)
   ======================================================= */
if ($action === 'list') {

    $rows = [];
    $q = $conn->query("SELECT id, name, parent_id, owner_id FROM folders ORDER BY name ASC");

    while ($r = $q->fetch_assoc()) {
        $rows[] = $r;
    }

    echo json_encode(['ok' => true, 'rows' => $rows]);
    exit;
}



/* =======================================================
   2. CREATE FOLDER  (Quick Actions)
   ======================================================= */
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $name      = trim($_POST['name'] ?? '');
    $owner     = $_POST['owner_id'] ?? '';
    $parent    = $_POST['parent_id'] ?? '';

    if ($name === '') fail("Nama folder wajib diisi.");
    if ($owner === '') fail("Pemilik folder wajib dipilih.");

    $owner_id  = ($owner === '') ? null : (int)$owner;
    $parent_id = ($parent === '') ? null : (int)$parent;

    $stmt = $conn->prepare("
        INSERT INTO folders (name, parent_id, owner_id, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param("sii", $name, $parent_id, $owner_id);

    if ($stmt->execute()) {
        echo json_encode([
            'ok' => true,
            'message' => 'Folder berhasil dibuat.',
            'id' => $stmt->insert_id
        ]);
    } else {
        fail("Gagal membuat folder.");
    }

    exit;
}



/* =======================================================
   3. MOVE FILE (dipanggil index.php)
   ======================================================= */
if ($action === 'moveFile' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $fileId   = (int)($_POST['file_id'] ?? 0);
    $folderId = $_POST['folder_id'] === '' ? null : (int)$_POST['folder_id'];

    if ($fileId <= 0) fail("ID file tidak valid.");

    if ($folderId) {
        $q = $conn->prepare("SELECT id FROM folders WHERE id = ?");
        $q->bind_param("i", $folderId);
        $q->execute();
        if (!$q->get_result()->fetch_assoc()) fail("Folder tidak ditemukan.");
        $q->close();
    }

    if ($folderId) {
        $stmt = $conn->prepare("UPDATE files SET folder_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $folderId, $fileId);
    } else {
        $stmt = $conn->prepare("UPDATE files SET folder_id = NULL WHERE id = ?");
        $stmt->bind_param("i", $fileId);
    }

    if ($stmt->execute()) {
        echo json_encode([
            'ok' => true,
            'message' => "File berhasil dipindahkan."
        ]);
    } else {
        fail("Gagal memindahkan file.");
    }
    exit;
}



/* =======================================================
   4. RENAME FOLDER (optional)
   ======================================================= */
if ($action === 'rename' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id   = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');

    if ($id <= 0) fail("ID tidak valid.");
    if ($name === '') fail("Nama folder wajib diisi.");

    $stmt = $conn->prepare("UPDATE folders SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();

    echo json_encode(['ok' => true, 'message' => 'Folder berhasil diubah.']);
    exit;
}



/* =======================================================
   5. DELETE FOLDER (optional)
   ======================================================= */
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) fail("ID tidak valid.");

    $q = $conn->prepare("SELECT COUNT(*) c FROM folders WHERE parent_id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    if ($q->get_result()->fetch_assoc()['c'] > 0) fail("Folder masih memiliki subfolder.");
    $q->close();

    $q = $conn->prepare("SELECT COUNT(*) c FROM files WHERE folder_id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    if ($q->get_result()->fetch_assoc()['c'] > 0) fail("Masih ada file di folder.");
    $q->close();

    $stmt = $conn->prepare("DELETE FROM folders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(['ok' => true, 'message' => 'Folder berhasil dihapus.']);
    exit;
}



// fallback
fail('Aksi tidak dikenal.');
?>
