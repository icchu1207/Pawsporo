<?php
include __DIR__ . "/../config.php"; 
$type = $_GET['type'] ?? '';
$size = $_GET['size'] ?? '';
$gender = $_GET['gender'] ?? '';
$temperament = $_GET['temperament'] ?? '';
$neutered = $_GET['neutered'] ?? '';
$vaccines = $_GET['vaccines'] ?? '';

$sql = "SELECT * FROM pets WHERE 1=1";

if ($type !== '') {
    $sql .= " AND type = '" . $conn->real_escape_string($type) . "'";
}
if ($size !== '') {
    $sql .= " AND size = '" . $conn->real_escape_string($size) . "'";
}
if ($neutered !== '') {
    $sql .= " AND neutered = '" . $conn->real_escape_string($neutered) . "'";
}
if ($vaccines !== '') {
    $sql .= " AND vaccines LIKE '%" . $conn->real_escape_string($vaccines) . "%'";
}
if ($gender !== '') {
    $sql .= " AND gender = '" . $conn->real_escape_string($gender) . "'";
}
if ($temperament !== '') {
    $sql .= " AND temperament = '" . $conn->real_escape_string($temperament) . "'";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>PawsConnect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Animated background -->
<div class="bg-blobs">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>
<div class="paws-bg" id="pawsBg"></div>

<!-- Header -->
<div class="header">
    <a href="stray_report.php" class="btn-nav">🐾 Stray Report</a>
    <img src="assets/images/logo.png" alt="PawsConnect Logo" class="logo">
    <div style="display:flex; gap:12px; align-items:center;">
        <a href="http://localhost:5173" class="btn-nav" title="Home">🏠</a>
        <a href="admin_login.php" class="btn-nav">Log In</a>
    </div>
</div>

<!-- Hero -->
<div class="hero">
    <h1>Find Your Perfect Companion</h1>
    <p>Line about like pets you know</p>
</div>

<!-- Filters -->
<form class="filters" method="GET" data-aos="fade-up">
    <select name="type">
        <option value="">All Types</option>
        <option value="Dog" <?= $type=="Dog" ? "selected" : "" ?>>🐶 Dog</option>
        <option value="Cat" <?= $type=="Cat" ? "selected" : "" ?>>🐱 Cat</option>
        <option value="Bird" <?= $type=="Bird" ? "selected" : "" ?>>🐦 Bird</option>
    </select>
    <select name="size">
        <option value="">All Sizes</option>
        <option value="Small" <?= $size=="Small" ? "selected" : "" ?>>Small</option>
        <option value="Medium" <?= $size=="Medium" ? "selected" : "" ?>>Medium</option>
        <option value="Large" <?= $size=="Large" ? "selected" : "" ?>>Large</option>
    </select>
    <select name="gender">
        <option value="">All Genders</option>
        <option value="Male" <?= $gender=="Male" ? "selected" : "" ?>>Male</option>
        <option value="Female" <?= $gender=="Female" ? "selected" : "" ?>>Female</option>
    </select>
    <select name="temperament">
        <option value="">All Temperaments</option>
        <option value="Friendly" <?= $temperament=="Friendly" ? "selected" : "" ?>>Friendly</option>
        <option value="Calm" <?= $temperament=="Calm" ? "selected" : "" ?>>Calm</option>
        <option value="Playful" <?= $temperament=="Playful" ? "selected" : "" ?>>Playful</option>
        <option value="Shy" <?= $temperament=="Shy" ? "selected" : "" ?>>Shy</option>
    </select>
    <select name="neutered">
        <option value="">Neutered/Spayed?</option>
        <option value="1" <?= $neutered=="1" ? "selected" : "" ?>>Yes</option>
        <option value="0" <?= $neutered==="0" ? "selected" : "" ?>>No</option>
    </select>
    <select name="vaccines">
        <option value="">All Vaccines</option>
        <option value="Anti-rabies" <?= $vaccines=="Anti-rabies" ? "selected" : "" ?>>Anti-rabies</option>
        <option value="5-in-1" <?= $vaccines=="5-in-1" ? "selected" : "" ?>>5-in-1</option>
        <option value="None" <?= $vaccines=="None" ? "selected" : "" ?>>None</option>
    </select>
    <button type="submit">Search 🔍</button>
    <a href="index.php">Reset</a>
</form>

<!-- Pet Cards -->
<div class="pet-cards">
<?php
if ($result && $result->num_rows > 0) {
    $delay = 0;
    while ($pet = $result->fetch_assoc()) {
?>
    <div class="pet-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
        <div class="pet-card-img-wrap">
            <img src="../upload/<?= htmlspecialchars($pet['image']) ?>" alt="<?= htmlspecialchars($pet['name']) ?>" onerror="this.src='https://placehold.co/400x200/ffd6e7/ff5c9d?text=🐾+No+Photo'">
            <div class="pet-card-badge"><?= htmlspecialchars($pet['type']) ?></div>
        </div>
        <div class="pet-card-body">
            <h2><?= htmlspecialchars($pet['name']) ?></h2>
            <div class="primary-info">
                <span class="pill"><?= htmlspecialchars($pet['size']) ?></span>
                <span class="pill"><?= htmlspecialchars($pet['gender']) ?></span>
                <span class="pill"><?= htmlspecialchars($pet['temperament']) ?></span>
            </div>
            <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($pet['location']) ?></p>
            <?php if (!empty($pet['vaccines'])): ?>
            <p><strong>Vaccines:</strong> <?= htmlspecialchars($pet['vaccines']) ?></p>
            <?php endif; ?>
            <?php if (isset($pet['neutered'])): ?>
            <p><strong>Neutered/Spayed:</strong> <?= $pet['neutered'] ? 'Yes ✅' : 'No ❌' ?></p>
            <?php endif; ?>
            <a class="adopt-btn" href="adopt.php?pet_id=<?= $pet['id'] ?>">Adopt Me 🐾</a>
        </div>
    </div>
<?php
        $delay = min($delay + 100, 400);
    }
} else {
    echo '<div class="no-pets"><span>🐾</span>No pets match your search.</div>';
}
?>
</div>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 600, once: true, easing: 'ease-out-cubic' });

    // Floating paw prints
    const container = document.getElementById('pawsBg');
    for (let i = 0; i < 12; i++) {
        const el = document.createElement('span');
        el.className = 'paw';
        el.textContent = '🐾';
        el.style.left = Math.random() * 100 + 'vw';
        el.style.animationDuration = (12 + Math.random() * 15) + 's';
        el.style.animationDelay = (Math.random() * 15) + 's';
        el.style.fontSize = (18 + Math.random() * 20) + 'px';
        container.appendChild(el);
    }
</script>
</body>
</html>
