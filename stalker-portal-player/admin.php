<?php
// Admin panel to add M3U file or Stalker portal details (DNS URL, MAC address)

session_start();

// Simple authentication for admin panel (for demonstration)
$adminUser = 'admin';
$adminPass = 'password';

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if (!isset($_SESSION['logged_in'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === $adminUser && $_POST['password'] === $adminPass) {
            $_SESSION['logged_in'] = true;
            header('Location: admin.php');
            exit;
        } else {
            $error = "Invalid username or password";
        }
    }
} else {
    // Handle form submission for M3U upload or Stalker portal details
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'upload_m3u' && isset($_FILES['m3u_file'])) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadFile = $uploadDir . basename($_FILES['m3u_file']['name']);
            if (move_uploaded_file($_FILES['m3u_file']['tmp_name'], $uploadFile)) {
                $message = "M3U file uploaded successfully.";
            } else {
                $error = "Failed to upload M3U file.";
            }
        } elseif ($_POST['action'] === 'add_stalker') {
            $dns = filter_input(INPUT_POST, 'dns', FILTER_SANITIZE_URL);
            $mac = filter_input(INPUT_POST, 'mac', FILTER_SANITIZE_STRING);
            if ($dns && $mac) {
                $dataFile = __DIR__ . '/stalker_portals.json';
                $portals = [];
                if (file_exists($dataFile)) {
                    $portals = json_decode(file_get_contents($dataFile), true);
                }
                $portals[] = ['dns' => $dns, 'mac' => $mac];
                file_put_contents($dataFile, json_encode($portals, JSON_PRETTY_PRINT));
                $message = "Stalker portal details saved successfully.";
            } else {
                $error = "Please provide valid DNS and MAC address.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - Stalker Portal Player</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white font-sans">
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Admin Panel</h1>
        <?php if (!isset($_SESSION['logged_in'])): ?>
            <form method="POST" class="space-y-4 max-w-sm">
                <?php if (isset($error)): ?>
                    <div class="bg-red-600 p-2 rounded"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <div>
                    <label for="username" class="block mb-1">Username</label>
                    <input type="text" name="username" id="username" required class="w-full p-2 rounded text-black" />
                </div>
                <div>
                    <label for="password" class="block mb-1">Password</label>
                    <input type="password" name="password" id="password" required class="w-full p-2 rounded text-black" />
                </div>
                <button type="submit" class="bg-white text-black px-4 py-2 rounded hover:bg-gray-300">Login</button>
            </form>
        <?php else: ?>
            <?php if (isset($message)): ?>
                <div class="bg-green-600 p-2 rounded mb-4"><?php echo htmlspecialchars($message); ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-600 p-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="mb-6 space-y-4 max-w-lg">
                <input type="hidden" name="action" value="upload_m3u" />
                <label for="m3u_file" class="block mb-1">Upload M3U File</label>
                <input type="file" name="m3u_file" id="m3u_file" accept=".m3u,.m3u8" required class="w-full p-2 rounded text-black" />
                <button type="submit" class="bg-white text-black px-4 py-2 rounded hover:bg-gray-300">Upload</button>
            </form>
            <form method="POST" class="space-y-4 max-w-lg">
                <input type="hidden" name="action" value="add_stalker" />
                <div>
                    <label for="dns" class="block mb-1">Stalker Portal DNS URL</label>
                    <input type="url" name="dns" id="dns" required class="w-full p-2 rounded text-black" />
                </div>
                <div>
                    <label for="mac" class="block mb-1">MAC Address</label>
                    <input type="text" name="mac" id="mac" required class="w-full p-2 rounded text-black" />
                </div>
                <button type="submit" class="bg-white text-black px-4 py-2 rounded hover:bg-gray-300">Save Stalker Portal</button>
            </form>
            <form method="POST" class="mt-6">
                <button type="submit" name="logout" class="bg-red-600 px-4 py-2 rounded hover:bg-red-700">Logout</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
