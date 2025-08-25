<?php
require_once __DIR__ . "/../../config.php";

header("Content-Type: application/json");

try {
    if (isset($_GET["id"])) {
        $stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
        $stmt->execute([$_GET["id"]]);
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($lesson ?: ["error" => "Không tìm thấy bài học"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM lessons ORDER BY lesson_order ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
