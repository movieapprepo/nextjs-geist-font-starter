<?php
// Entry point for the Stalker Portal Player application
// This will serve as the main landing page for users to select countries, channels, and play streams

// For now, a simple placeholder page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Stalker Portal Player</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white font-sans">
    <header class="p-4 border-b border-gray-700">
        <h1 class="text-3xl font-bold">Stalker Portal Player</h1>
    </header>
    <main class="p-4">
        <p class="text-lg">Welcome to the Stalker Portal Player. Please login to the admin panel to add M3U files or Stalker portal details.</p>
        <a href="admin.php" class="mt-4 inline-block bg-white text-black px-4 py-2 rounded hover:bg-gray-300">Go to Admin Panel</a>
    </main>
</body>
</html>
