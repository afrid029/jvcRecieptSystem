<?php
require_once '../db.php';
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$action = $_GET['action'] ?? '';

try {
    if ($action === 'school_info') {
        // hardcoded for now or fetch from setting table if created
        echo json_encode([
            'name' => 'J/Victoria College',
            'description' => 'J/Victoria College is a prestigious educational institution located in Chulipuram, Jaffna. Established with a vision to provide quality education and foster holistic development, the college has a rich history of academic excellence and community service.',
            'logo' => 'assets/images/logo.jpg'
        ]);

    } elseif ($action === 'oba_list' || $action === 'countries') {
        $stmt = $pdo->query("SELECT c.id, c.name, c.flag_image, c.slug FROM countries c ORDER BY c.name ASC");
        echo json_encode($stmt->fetchAll());

    } elseif ($action === 'upcoming_events') {
        // Global events (country_id IS NULL) + All country events, sorted by date ASC
        // User requirements: "all the future upcoming events of all OBAs... ascending order by date"
        $stmt = $pdo->prepare("
            SELECT e.*, c.name as country_name, c.flag_image 
            FROM events e 
            LEFT JOIN countries c ON e.country_id = c.id 
            WHERE e.event_date >= CURDATE() 
            ORDER BY e.event_date ASC
        ");
        $stmt->execute();
        echo json_encode($stmt->fetchAll());

    } elseif ($action === 'donations') {
        // Donations with show_on_homepage purpose
        // User requirements: "donation list shud be listed in pagination. 20 itemz per list."
        // "show Homepage selected purposed should come in this list"

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Count total
        $countSql = "
            SELECT COUNT(*) 
            FROM receipts r 
            JOIN purposes p ON r.purpose_id = p.id 
            WHERE p.show_on_homepage = 1 AND p.is_active = 1
        ";
        $total = $pdo->query($countSql)->fetchColumn();

        // Fetch items
        $sql = "
            SELECT r.received_from, r.amount, r.date, c.name as country_name, c.flag_image, r.city
            FROM receipts r 
            JOIN purposes p ON r.purpose_id = p.id 
            LEFT JOIN countries c ON r.country_id = c.id
            WHERE p.show_on_homepage = 1 AND p.is_active = 1
            ORDER BY r.date DESC, r.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode([
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit)
        ]);

    } elseif ($action === 'country_donations') {
        $id = $_GET['id'] ?? 0;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $countSql = "SELECT COUNT(*) FROM receipts WHERE country_id = ?";
        $cStmt = $pdo->prepare($countSql);
        $cStmt->execute([$id]);
        $total = $cStmt->fetchColumn();

        $dStmt = $pdo->prepare("
            SELECT r.*, p.name as purpose_name 
            FROM receipts r 
            LEFT JOIN purposes p ON r.purpose_id = p.id
            WHERE r.country_id = :country_id 
            ORDER BY r.date DESC, r.id DESC 
            LIMIT :limit OFFSET :offset
        ");
        $dStmt->bindValue(':country_id', $id, PDO::PARAM_INT);
        $dStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $dStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $dStmt->execute();

        echo json_encode([
            'data' => $dStmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit)
        ]);

    } elseif ($action === 'country_details') {
        $id = $_GET['id'] ?? 0;
        // OBA Info
        $stmt = $pdo->prepare("
            SELECT o.*, c.name as country_name 
            FROM oba_infos o 
            JOIN countries c ON o.country_id = c.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $info = $stmt->fetch();

        // Events for this country
        $stmtEvents = $pdo->prepare("
            SELECT * FROM events 
            WHERE country_id = ? AND event_date >= CURDATE() 
            ORDER BY event_date ASC
        ");
        $stmtEvents->execute([$id]);
        $events = $stmtEvents->fetchAll();

        // Donations from this OBA admins (meaning receipts where country_id matches)
        // User requirement: "All the donations given by this OBA members."
        // "This list shud come from current table. Note that tables needs to be updated with country"
        // Wait, does this mean ALL donations for that country, or just those created by admins of that country?
        // User said: "Update their OBA logo. show their log as well... Add/Update/Delete Donations of their OBA."
        // So yes, receipts where country_id = this country.

        // Pagination for country donations
        $page = isset($_GET['dpage']) ? (int) $_GET['dpage'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $countSql = "SELECT COUNT(*) FROM receipts WHERE country_id = ?";
        $cStmt = $pdo->prepare($countSql);
        $cStmt->execute([$id]);
        $total = $cStmt->fetchColumn();

        $dStmt = $pdo->prepare("
            SELECT r.*, p.name as purpose_name 
            FROM receipts r 
            LEFT JOIN purposes p ON r.purpose_id = p.id
            WHERE r.country_id = :country_id 
            ORDER BY r.date DESC, r.id DESC 
            LIMIT :limit OFFSET :offset
        ");
        $dStmt->bindValue(':country_id', $id, PDO::PARAM_INT);
        $dStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $dStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $dStmt->execute();

        echo json_encode([
            'info' => $info,
            'events' => $events,
            'donations' => [
                'data' => $dStmt->fetchAll(),
                'total' => $total,
                'page' => $page,
                'pages' => ceil($total / $limit)
            ]
        ]);

    } elseif ($action === 'news') {
        // Pagination
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        // Get all news (global + all countries) or filter by country
        $countryId = $_GET['country_id'] ?? null;

        if ($countryId) {
            // Count
            $cStmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE (country_id = ? OR is_global = 1)");
            $cStmt->execute([$countryId]);
            $total = $cStmt->fetchColumn();

            // Country-specific news + global news
            $stmt = $pdo->prepare("
                SELECT n.*, c.name as country_name 
                FROM news n 
                LEFT JOIN countries c ON n.country_id = c.id 
                WHERE (n.country_id = :cid OR n.is_global = 1)
                ORDER BY n.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':cid', $countryId);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Count
            $total = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();

            // All news for homepage
            $stmt = $pdo->prepare("
                SELECT n.*, c.name as country_name 
                FROM news n 
                LEFT JOIN countries c ON n.country_id = c.id 
                ORDER BY n.created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        echo json_encode([
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit)
        ]);

    } elseif ($action === 'advertisements') {
        $stmt = $pdo->query("SELECT * FROM advertisements ORDER BY display_order ASC, created_at DESC");
        echo json_encode($stmt->fetchAll());

    } elseif ($action === 'posters') {
        $stmt = $pdo->query("SELECT * FROM posters ORDER BY display_order ASC, created_at DESC");
        echo json_encode($stmt->fetchAll());

    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>