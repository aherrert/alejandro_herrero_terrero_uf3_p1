<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>El Título de Tu Sitio Web</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agrega tus estilos personalizados, etiquetas meta, etc. aquí -->
</head>

<body>
    <header>
        <div class="container-fluid p-0" align="center">
            <img src="https://img.freepik.com/vector-premium/cartel-pelicula-banner-cine-palomitas-maiz-refrescos-claqueta-banner-cine-brillante_661273-99.jpg" class="img-fluid" alt="Imagen de Cabecera">
        </div>
    </header>

    <main class="container mt-4">
        @yield('content') <!-- Esto será reemplazado por el contenido de las vistas individuales -->
    </main>

    <footer class="bg-primary text-white mt-4 text-center">
    <div class="container-mt-4">
        <p class="mx-0">&copy; 2023 Cines Splau</p>
        <p class="mx-0">¡Disfruta de la mejor experiencia cinematográfica!</p>
    </div>
</footer>

    <!-- Bootstrap JS y jQuery (Opcionales) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
</body>
</html>
