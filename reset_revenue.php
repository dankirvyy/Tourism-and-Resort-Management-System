<?php
// Reset Guest ID 1's revenue to 0

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=visit_mindoro_db;charset=utf8mb4",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Reset Guest ID 1's revenue to 0
    $stmt = $pdo->prepare("UPDATE guests SET total_revenue = 0 WHERE id = 1");
    $stmt->execute();

    echo "✓ Guest ID 1's revenue has been reset to 0\n";
    
    // Verify the change
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, total_revenue FROM guests WHERE id = 1");
    $stmt->execute();
    $guest = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nVerification:\n";
    echo "ID: {$guest['id']}\n";
    echo "Name: {$guest['first_name']} {$guest['last_name']}\n";
    echo "Revenue: ₱" . number_format($guest['total_revenue'], 2) . "\n";
    
    // Check new total
    $stmt = $pdo->query("SELECT SUM(total_revenue) as total FROM guests");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nNew Total Revenue (CRM): ₱" . number_format($result['total'], 2) . "\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
