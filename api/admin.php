<?php
require_once '../session_init.php';
require_once '../db.php';

header('Content-Type: application/json');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$role = $_SESSION['role'] ?? 'admin';
$userCountryId = $_SESSION['country_id'] ?? null;

try {
    // --- Countries (Super Admin Only) ---
    if ($action === 'list_countries') {
        $stmt = $pdo->query("SELECT * FROM countries ORDER BY name ASC");
        echo json_encode($stmt->fetchAll());

    } elseif ($action === 'save_country') {
        if ($role !== 'super_admin')
            throw new Exception("Unauthorized");

        $id = $_POST['id'] ?? null;
        $name = $_POST['name'];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

        // Image Upload
        $flagPath = null;
        if (isset($_FILES['flag_image']) && $_FILES['flag_image']['error'] === 0) {
            $ext = pathinfo($_FILES['flag_image']['name'], PATHINFO_EXTENSION);
            $filename = "flag_" . time() . ".$ext";
            // Ideally move to ../assets/images/flags/
            // Ensure dir exists
            if (!is_dir('../assets/images/flags'))
                mkdir('../assets/images/flags', 0777, true);
            move_uploaded_file($_FILES['flag_image']['tmp_name'], "../assets/images/flags/$filename");
            $flagPath = "assets/images/flags/$filename";
        }

        if ($id) {
            // Update
            $sql = "UPDATE countries SET name = ?, slug = ?";
            $params = [$name, $slug];
            if ($flagPath) {
                $sql .= ", flag_image = ?";
                $params[] = $flagPath;
            }
            $sql .= " WHERE id = ?";
            $params[] = $id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        } else {
            // Create
            $stmt = $pdo->prepare("INSERT INTO countries (name, slug, flag_image) VALUES (?, ?, ?)");
            $stmt->execute([$name, $slug, $flagPath]);
            // Also create empty oba_info for it?
            $newId = $pdo->lastInsertId();
            $pdo->query("INSERT INTO oba_infos (country_id) VALUES ($newId)");
        }
        echo json_encode(['success' => true]);

    } elseif ($action === 'delete_country') {
        if ($role !== 'super_admin')
            throw new Exception("Unauthorized");
        $id = $_POST['id'];
        $pdo->prepare("DELETE FROM countries WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);

        // --- Events ---
    } elseif ($action === 'list_events') {
        // Admin: Show all events (or filter by country if not super admin)
        if ($role === 'super_admin' || $role === 'manager') {
            $stmt = $pdo->query("SELECT e.*, c.name as country_name FROM events e LEFT JOIN countries c ON e.country_id = c.id ORDER BY e.event_date DESC");
        } else {
            $stmt = $pdo->prepare("SELECT e.*, c.name as country_name FROM events e LEFT JOIN countries c ON e.country_id = c.id WHERE e.country_id = ? ORDER BY e.event_date DESC");
            $stmt->execute([$userCountryId]);
        }
        echo json_encode($stmt->fetchAll());

    } elseif ($action === 'save_event') {
        // Super Admin can add Global (country_id=NULL) or any Country event
        // Admin can only add their Country event

        $id = $_POST['id'] ?? null;
        $title = $_POST['title'];
        $date = $_POST['event_date'];
        $desc = $_POST['description'];

        // Determine Country ID
        $cid = null;
        if ($role === 'super_admin' || $role === 'manager') {
            $cid = !empty($_POST['country_id']) ? $_POST['country_id'] : null;
        } else {
            $cid = $userCountryId;
        }

        // Image
        $imgPath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            if (!is_dir('../assets/images/events'))
                mkdir('../assets/images/events', 0777, true);
            $filename = "event_" . time() . "_" . mt_rand() . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/events/$filename");
            $imgPath = "assets/images/events/$filename";
        }

        if ($id) {
            // Update logic... Check ownership if Admin
            if ($role !== 'super_admin' && $role !== 'manager') {
                $check = $pdo->prepare("SELECT country_id FROM events WHERE id = ?");
                $check->execute([$id]);
                $curr = $check->fetchColumn();
                if ($curr != $userCountryId)
                    throw new Exception("Access Denied");
            }

            $sql = "UPDATE events SET title=?, event_date=?, description=?";
            $params = [$title, $date, $desc];
            if ($imgPath) {
                $sql .= ", image=?";
                $params[] = $imgPath;
            }
            if ($role === 'super_admin' || $role === 'manager') {
                $sql .= ", country_id=?";
                $params[] = $cid;
            }
            $sql .= " WHERE id=?";
            $params[] = $id;
            $pdo->prepare($sql)->execute($params);
        } else {
            $stmt = $pdo->prepare("INSERT INTO events (title, event_date, description, country_id, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $date, $desc, $cid, $imgPath]);
        }
        echo json_encode(['success' => true]);

    } elseif ($action === 'delete_event') {
        $id = $_POST['id'];
        if ($role !== 'super_admin' && $role !== 'manager') {
            $check = $pdo->prepare("SELECT country_id FROM events WHERE id = ?");
            $check->execute([$id]);
            if ($check->fetchColumn() != $userCountryId)
                throw new Exception("Access Denied");
        }
        $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);

        // --- Purposes (Super Admin) ---
    } elseif ($action === 'list_purposes') {
        // Admin likely wants to see all, maybe filter in frontend
        echo json_encode($pdo->query("SELECT * FROM purposes")->fetchAll());

    } elseif ($action === 'save_purpose') {
        if ($role !== 'super_admin')
            throw new Exception("Unauthorized");
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'];
        $show = isset($_POST['show_on_homepage']) ? 1 : 0;

        // Restore if it was deleted? User didn't ask, but safe to default is_active=1
        if ($id) {
            $pdo->prepare("UPDATE purposes SET name=?, show_on_homepage=? WHERE id=?")->execute([$name, $show, $id]);
        } else {
            $pdo->prepare("INSERT INTO purposes (name, show_on_homepage, is_active) VALUES (?, ?, 1)")->execute([$name, $show]);
        }
        echo json_encode(['success' => true]);

    } elseif ($action === 'delete_purpose') {
        if ($role !== 'super_admin')
            throw new Exception("Unauthorized");
        $id = $_POST['id'];
        // Soft Delete
        $pdo->prepare("UPDATE purposes SET is_active = 0 WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);

    } elseif ($action === 'get_oba_info') {
        $cid = $userCountryId;
        if ($role === 'super_admin' && !empty($_GET['country_id'])) {
            $cid = $_GET['country_id'];
        }
        if (!$cid)
            echo json_encode([]);
        else {
            $stmt = $pdo->prepare("SELECT * FROM oba_infos WHERE country_id = ?");
            $stmt->execute([$cid]);
            echo json_encode($stmt->fetch() ?: []);
        }

    } elseif ($action === 'save_oba_info') {
        $cid = $userCountryId;
        if ($role === 'super_admin' && !empty($_POST['country_id'])) {
            $cid = $_POST['country_id'];
        }

        if (!$cid)
            throw new Exception("No Country Assigned");

        $presName = $_POST['president_name'] ?? '';
        $presPhone = $_POST['president_phone'] ?? '';

        $vpName = $_POST['vp_name'] ?? '';
        $vpPhone = $_POST['vp_phone'] ?? '';

        $secName = $_POST['secretary_name'] ?? '';
        $secPhone = $_POST['secretary_phone'] ?? '';

        $vsName = $_POST['vs_name'] ?? '';
        $vsPhone = $_POST['vs_phone'] ?? '';

        $treasName = $_POST['treasurer_name'] ?? '';
        $treasPhone = $_POST['treasurer_phone'] ?? '';

        $vtName = $_POST['vt_name'] ?? '';
        $vtPhone = $_POST['vt_phone'] ?? '';

        // Dynamic Members: Expecting JSON string from frontend
        // Structure: [ { "position": "Name", "members": ["N1", "N2"] }, ... ]
        $othersJson = $_POST['other_members'] ?? '[]';

        $socials = json_encode([
            'web' => $_POST['social_web'] ?? '',
            'facebook' => $_POST['social_facebook'] ?? '',
            'instagram' => $_POST['social_instagram'] ?? '',
            'twitter' => $_POST['social_twitter'] ?? '',
            'youtube' => $_POST['social_youtube'] ?? ''
        ]);

        $logoPath = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            if (!is_dir('../assets/images/logos'))
                mkdir('../assets/images/logos', 0777, true);
            $filename = "logo_" . $cid . "_" . time() . "." . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['logo']['tmp_name'], "../assets/images/logos/$filename");
            $logoPath = "assets/images/logos/$filename";
        }

        $committeePhotoPath = null;
        if (isset($_FILES['committee_photo']) && $_FILES['committee_photo']['error'] === 0) {
            if (!is_dir('../assets/images/committee'))
                mkdir('../assets/images/committee', 0777, true);
            $filename = "committee_" . $cid . "_" . time() . "." . pathinfo($_FILES['committee_photo']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['committee_photo']['tmp_name'], "../assets/images/committee/$filename");
            $committeePhotoPath = "assets/images/committee/$filename";
        }

        $check = $pdo->prepare("SELECT id FROM oba_infos WHERE country_id = ?");
        $check->execute([$cid]);
        $exists = $check->fetchColumn();

        if ($exists) {
            $sql = "UPDATE oba_infos SET president_name=?, president_phone=?, vp_name=?, vp_phone=?, secretary_name=?, secretary_phone=?, vs_name=?, vs_phone=?, treasurer_name=?, treasurer_phone=?, vt_name=?, vt_phone=?, other_members=?, social_links=?";
            $params = [$presName, $presPhone, $vpName, $vpPhone, $secName, $secPhone, $vsName, $vsPhone, $treasName, $treasPhone, $vtName, $vtPhone, $othersJson, $socials];
            if ($logoPath) {
                $sql .= ", logo=?";
                $params[] = $logoPath;
            }
            if ($committeePhotoPath) {
                $sql .= ", committee_photo=?";
                $params[] = $committeePhotoPath;
            }
            $sql .= " WHERE country_id=?";
            $params[] = $cid;
            $pdo->prepare($sql)->execute($params);
        } else {
            $stmt = $pdo->prepare("INSERT INTO oba_infos (country_id, president_name, president_phone, vp_name, vp_phone, secretary_name, secretary_phone, vs_name, vs_phone, treasurer_name, treasurer_phone, vt_name, vt_phone, other_members, social_links, logo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$cid, $presName, $presPhone, $vpName, $vpPhone, $secName, $secPhone, $vsName, $vsPhone, $treasName, $treasPhone, $vtName, $vtPhone, $othersJson, $socials, $logoPath]);
        }
        echo json_encode(['success' => true]);

        // --- News Management ---
    } elseif ($action === 'create_news') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $isGlobal = 0;
        $countryId = null;

        if ($role === 'super_admin' || $role === 'manager') {
            $target = $_POST['target'] ?? 'global';
            if ($target === 'global') {
                $isGlobal = 1;
            } else {
                $countryId = $target;
                $isGlobal = 0;
            }
        } else {
            $countryId = $userCountryId;
        }

        // Handle multiple image uploads
        $imagePaths = [];
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            if (!is_dir('../assets/images/news'))
                mkdir('../assets/images/news', 0777, true);

            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === 0) {
                    $filename = "news_" . time() . "_" . $i . "." . pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                    move_uploaded_file($_FILES['images']['tmp_name'][$i], "../assets/images/news/$filename");
                    $imagePaths[] = "assets/images/news/$filename";
                }
            }
        }

        $imagesJson = json_encode($imagePaths);

        $stmt = $pdo->prepare("INSERT INTO news (title, content, images, country_id, is_global) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $imagesJson, $countryId, $isGlobal]);
        echo json_encode(['success' => true]);

    } elseif ($action === 'update_news') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        // Fetch existing news
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
        $stmt->execute([$id]);
        $news = $stmt->fetch();

        if (!$news) {
            echo json_encode(['error' => 'News not found']);
            exit;
        }

        // Update Scope/Country
        $isGlobal = $news['is_global'];
        $countryId = $news['country_id'];

        if ($role === 'super_admin' || $role === 'manager') {
            $target = $_POST['target'] ?? 'global';
            if ($target === 'global') {
                $isGlobal = 1;
                $countryId = null;
            } else {
                $countryId = $target;
                $isGlobal = 0;
            }
        }

        // Handle Images
        // 1. Get kept images from hidden inputs (if any)
        $keptImages = $_POST['kept_images'] ?? [];
        if (!is_array($keptImages))
            $keptImages = [];

        // 2. Upload new images
        $newImages = [];
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            if (!is_dir('../assets/images/news'))
                mkdir('../assets/images/news', 0777, true);

            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === 0) {
                    $filename = "news_upd_" . time() . "_" . $i . "." . pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                    move_uploaded_file($_FILES['images']['tmp_name'][$i], "../assets/images/news/$filename");
                    $newImages[] = "assets/images/news/$filename";
                }
            }
        }

        // Merge kept images with new images
        $finalImages = array_merge($keptImages, $newImages);
        $imagesJson = json_encode($finalImages);

        $stmt = $pdo->prepare("UPDATE news SET title=?, content=?, images=?, country_id=?, is_global=? WHERE id=?");
        $stmt->execute([$title, $content, $imagesJson, $countryId, $isGlobal, $id]);
        echo json_encode(['success' => true]);

    } elseif ($action === 'delete_news') {
        $id = $_POST['id'];
        // Check ownership
        if ($role !== 'super_admin' && $role !== 'manager') {
            $check = $pdo->prepare("SELECT country_id FROM news WHERE id = ?");
            $check->execute([$id]);
            $newsCountry = $check->fetchColumn();
            if ($newsCountry != $userCountryId) {
                throw new Exception("Unauthorized");
            }
        }
        $pdo->prepare("DELETE FROM news WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);

        // --- Advertisements (Super Admin Only) ---
    } elseif ($action === 'create_ad') {
        if ($role !== 'super_admin' && $role !== 'manager')
            throw new Exception("Unauthorized");

        $link = $_POST['link'] ?? null;
        $order = $_POST['display_order'] ?? 0;

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
            throw new Exception("Image required");
        }

        if (!is_dir('../assets/images/ads'))
            mkdir('../assets/images/ads', 0777, true);
        $filename = "ad_" . time() . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/ads/$filename");
        $imagePath = "assets/images/ads/$filename";

        $stmt = $pdo->prepare("INSERT INTO advertisements (image, link, display_order) VALUES (?, ?, ?)");
        $stmt->execute([$imagePath, $link, $order]);
        echo json_encode(['success' => true]);

    } elseif ($action === 'delete_ad') {
        if ($role !== 'super_admin' && $role !== 'manager')
            throw new Exception("Unauthorized");
        $id = $_POST['id'];
        $pdo->prepare("DELETE FROM advertisements WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);

        // --- Posters (Super Admin Only) ---
    } elseif ($action === 'create_poster') {
        if ($role !== 'super_admin' && $role !== 'manager')
            throw new Exception("Unauthorized");

        $link = $_POST['link'] ?? null;
        $order = $_POST['display_order'] ?? 0;

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
            throw new Exception("Image required");
        }

        if (!is_dir('../assets/images/posters'))
            mkdir('../assets/images/posters', 0777, true);
        $filename = "poster_" . time() . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/posters/$filename");
        $imagePath = "assets/images/posters/$filename";

        $stmt = $pdo->prepare("INSERT INTO posters (image, link, display_order) VALUES (?, ?, ?)");
        $stmt->execute([$imagePath, $link, $order]);
        echo json_encode(['success' => true]);

    } elseif ($action === 'delete_poster') {
        if ($role !== 'super_admin' && $role !== 'manager')
            throw new Exception("Unauthorized");
        $id = $_POST['id'];
        $pdo->prepare("DELETE FROM posters WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);

    } else {
        throw new Exception("Invalid Action");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>