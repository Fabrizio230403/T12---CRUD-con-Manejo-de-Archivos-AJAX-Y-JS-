 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <!-- CSS de AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
   
 </head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">La Tienda de Don Fabrizio</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="productos.php" class="nav-link <?php echo ($pagina == 'productos') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Productos</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Contenido -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <?php 
                echo $contenido; ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center" >
        <strong>Copyright &copy; 2024 <a href="#">La Tienda de Don Fabrizio</a>.</strong> Todos los derechos reservados.
    </footer>
</div>

<!-- JS de AdminLTE -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
<script>
    $(document).ready(function() {
        // Manejar el envío del formulario de agregar producto
        $('#agregarProductoForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'productos.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Recargar la página para ver el nuevo producto
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        });

        // Manejar el clic en el botón Editar
        $('.editar').on('click', function() {
            var index = $(this).data('index');
            var producto = <?php echo json_encode($_SESSION['productos']); ?>[index];

            // Rellenar el formulario con los datos del producto
            $('#codigo').val(producto.codigo);
            $('#nombre').val(producto.nombre);
            $('#descripcion').val(producto.descripcion);
            $('#cantidad').val(producto.cantidad);
            $('#precio').val(producto.precio);
            $('#modelo').val(producto.modelo);
            $('#marca').val(producto.marca);
            $('#proveedor').val(producto.proveedor);
            $('#categoria').val(producto.categoria);
            $('#estado').val(producto.estado);
            $('#agregarProductoForm').find('input[name="action"]').val('editar');
            $('#agregarProductoForm').find('input[name="index"]').remove(); // Asegurarse de que no haya múltiples campos hidden
            $('#agregarProductoForm').append('<input type="hidden" name="index" value="' + index + '">');
        });

        // Manejar el clic en el botón Eliminar
        $('.eliminar').on('click', function() {
            var index = $(this).data('index');
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                // Eliminar el producto de la sesión
                $.ajax({
                    url: 'productos.php',
                    type: 'POST',
                    data: {
                        action: 'eliminar',
                        index: index
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Recargar la página para ver el producto eliminado
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        });
    });
</script>
 
</body>
</html>

<?php
 
?>