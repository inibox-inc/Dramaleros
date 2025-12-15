<?php
session_start();
require_once 'config/database.php';
$db = new Database();
$pdo = $db->getConnection();
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login");
    exit();
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_avatar = $_SESSION['user_avatar'];
$database = new Database();
$db = $database->getConnection();
$query = "SELECT * FROM configuracion ORDER BY id DESC LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();
$configuracion = $stmt->fetch(PDO::FETCH_ASSOC);
$mostrar_cast = isset($configuracion['mostrar_cast']) ? (bool)$configuracion['mostrar_cast'] : true;
$mostrar_telegram = isset($configuracion['mostrar_telegram']) ? (bool)$configuracion['mostrar_telegram'] : true;
$mostrar_search = isset($configuracion['mostrar_search']) ? (bool)$configuracion['mostrar_search'] : true;
$mostrar_logout = isset($configuracion['mostrar_logout']) ? (bool)$configuracion['mostrar_logout'] : true;
$query = "SELECT * FROM series WHERE es_hero = 1 LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute();
$heroSerie = $stmt->fetch(PDO::FETCH_ASSOC);
$query = "SELECT * FROM series ORDER BY titulo";
$stmt = $db->prepare($query);
$stmt->execute();
$series = $stmt->fetchAll(PDO::FETCH_ASSOC);
$secciones = [];
foreach ($series as $serie) {
    $categorias = explode(', ', $serie['categorias']);
    foreach ($categorias as $categoria) {
        $categoria = trim($categoria);
        if (!isset($secciones[$categoria])) {
            $secciones[$categoria] = [];
        }
        $secciones[$categoria][] = $serie;
    }
}
$query = "SELECT * FROM peliculas ORDER BY titulo";
$stmt = $db->prepare($query);
$stmt->execute();
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($configuracion['titulo_app'] ?? 'Dramaleros'); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
<link href="assets/css/pages/main.css" rel="stylesheet">
<?php if (!empty($configuracion['css_externo'])): ?>
<link rel="stylesheet" href="<?php echo htmlspecialchars($configuracion['css_externo']); ?>">
<?php endif; ?>
<?php if (!empty($configuracion['css_personalizado'])): ?>
<style>
<?php echo $configuracion['css_personalizado']; ?>
</style>
<?php endif; ?>
</head>
<body>
<header class="header">
  <div class="header-title" id="header-title"><?php echo htmlspecialchars($configuracion['titulo_app'] ?? 'Dramaleros'); ?></div>
  <div class="header-profile">
    <a href="pages/profile" class="profile-link">
      <div class="profile-info">
        <img src="<?php echo htmlspecialchars($user_avatar); ?>" alt="Perfil" class="profile-avatar">
        <span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span>
      </div>
    </a>
    <div class="header-buttons">
      <?php if ($mostrar_cast && !empty($configuracion['enlace_cast'])): ?>
      <a id="link-cast" href="<?php echo htmlspecialchars($configuracion['enlace_cast']); ?>" class="header-btn" title="Cast">
        <i class="material-icons-round"><?php echo htmlspecialchars($configuracion['icono_cast'] ?? 'cast'); ?></i>
      </a>
      <?php endif; ?>
      <?php if ($mostrar_telegram && !empty($configuracion['enlace_telegram'])): ?>
      <a id="link-telegram" href="<?php echo htmlspecialchars($configuracion['enlace_telegram']); ?>" class="header-btn" title="Telegram">
        <i class="material-icons-round"><?php echo htmlspecialchars($configuracion['icono_telegram'] ?? 'send'); ?></i>
      </a>
      <?php endif; ?>
      <?php if ($mostrar_search && !empty($configuracion['enlace_search'])): ?>
      <a id="link-search" href="<?php echo htmlspecialchars($configuracion['enlace_search']); ?>" class="header-btn" title="Buscar">
        <i class="material-icons-round"><?php echo htmlspecialchars($configuracion['icono_search'] ?? 'search'); ?></i>
      </a>
      <?php endif; ?>
      <?php if ($mostrar_logout && !empty($configuracion['enlace_logout'])): ?>
      <a id="link-logout" href="#" class="header-btn logout" title="Cerrar Sesión" onclick="confirmLogout(event)">
        <i class="material-icons-round"><?php echo htmlspecialchars($configuracion['icono_logout'] ?? 'logout'); ?></i>
      </a>
      <?php endif; ?>
    </div>
  </div>
</header>
<div id="logoutModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <i class="material-icons-round modal-icon">logout</i>
      <span class="modal-title">Cerrar Sesión</span>
    </div>
    <div class="modal-message">
      ¿Estás seguro de que quieres cerrar sesión? Tendrás que volver a iniciar sesión para acceder a tu cuenta.
    </div>
    <div class="modal-actions">
      <button class="modal-btn cancel" onclick="closeLogoutModal()">Cancelar</button>
      <button class="modal-btn confirm" onclick="performLogout()">Sí, Cerrar Sesión</button>
    </div>
  </div>
</div>
<div class="welcome-message">
  ¡Bienvenido/a, <?php echo htmlspecialchars($user_name); ?>!
</div>
<!-- Hero -->
<div id="hero" class="hero">
  <?php if ($heroSerie): ?>
  <a href="sections/serie?id=<?php echo htmlspecialchars($heroSerie['slug']); ?>">
    <img src="<?php echo htmlspecialchars($heroSerie['imagen_backdrop']); ?>" alt="<?php echo htmlspecialchars($heroSerie['titulo']); ?>">
    <div class="overlay"><h1><?php echo htmlspecialchars($heroSerie['titulo']); ?></h1></div>
  </a>
  <?php endif; ?>
</div>
<div id="sections">
  <?php foreach ($secciones as $categoria => $seriesCategoria): ?>
  <div class="section">
    <h2><?php echo htmlspecialchars($categoria); ?></h2>
    <div class="cards">
      <?php foreach ($seriesCategoria as $serie): ?>
      <div class="card">
        <a href="sections/serie?id=<?php echo htmlspecialchars($serie['slug']); ?>">
          <img src="<?php echo htmlspecialchars($serie['imagen_poster']); ?>" alt="<?php echo htmlspecialchars($serie['titulo']); ?>">
        </a>
        <p><?php echo htmlspecialchars($serie['titulo']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (!empty($peliculas)): ?>
  <div class="section">
    <h2>Películas</h2>
    <div class="cards">
      <?php foreach ($peliculas as $pelicula): ?>
      <div class="card">
        <a href="sections/movie?id=<?php echo htmlspecialchars($pelicula['slug']); ?>">
          <img src="<?php echo htmlspecialchars($pelicula['imagen_poster']); ?>" alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
        </a>
        <p><?php echo htmlspecialchars($pelicula['titulo']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<footer id="footer"><?php echo htmlspecialchars($configuracion['footer'] ?? 'Dramaleros © 2025'); ?></footer>
<script>
function confirmLogout(e){e.preventDefault(),document.getElementById('logoutModal').classList.add('active'),document.body.style.overflow='hidden'}function closeLogoutModal(){document.getElementById('logoutModal').classList.remove('active'),document.body.style.overflow='auto'}function performLogout(){const o='<?php echo htmlspecialchars($configuracion['enlace_logout'] ?? 'includes/logout.php'); ?>';window.location.href=o}document.getElementById('logoutModal').addEventListener('click',function(o){o.target===this&&closeLogoutModal()}),document.addEventListener('keydown',function(o){'Escape'===o.key&&closeLogoutModal()});
</script>
</body>
</html>
