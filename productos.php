<?php 
 
session_start();

// Inicializar la sesión de productos si no existe
if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = cargarProductos('productos.txt');
}

// Definir el archivo donde se guardarán los productos
$archivoProductos = 'productos.txt';

// Función para cargar productos desde el archivo
function cargarProductos($archivo) {
  $productos = [];
if (file_exists($archivo)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES);
    foreach ($lineas as $linea) {
      $producto = explode('|', $linea);
      if ($producto) {
        $productos[] = [
            'codigo' => $producto[0],
            'nombre' => $producto[1],
            'descripcion' => $producto[2],
            'cantidad' => $producto[3],
            'precio' => $producto[4],
            'modelo' => $producto[5],
            'marca' => $producto[6],
            'proveedor' => $producto[7],
            'categoria' => $producto[8],
            'estado' => $producto[9],
        ];
    }
  }
  }
  return $productos;
}




// Función para guardar productos en el archivo
function guardarProducto($archivo, $producto) {
  $handle = fopen($archivo, 'a');
  if ($handle) {
    fwrite($handle, implode('|', $producto) . PHP_EOL);
    fclose($handle);
  }
}

// Función para actualizar el archivo de productos
function actualizarProductos($archivo, $productos) {
    $handle = fopen($archivo, 'w');
    if ($handle) {
        foreach ($productos as $producto) {
            fwrite($handle, implode('|', $producto) . PHP_EOL);
        }
        fclose($handle);
    }
}


// Lógica para agregar producto
if (isset($_POST['action']) && $_POST['action'] === 'agregar') {
    // Recibir los datos enviados por AJAX para agregar el producto
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $proveedor = $_POST['proveedor'];
    $categoria = $_POST['categoria'];
    $estado = $_POST['estado'];

   // Verificar si el código ya existe
    foreach ($_SESSION['productos'] as $p) {
      if ($p['codigo'] === $codigo) {
          echo json_encode(['success' => false, 'message' => 'El código ya existe.']);
          exit;
      }
  }

     // Crear el producto y agregarlo a la sesión
$producto = [
  'codigo' => $codigo,
  'nombre' => $nombre,
  'descripcion' => $descripcion,
  'cantidad' => $cantidad,
  'precio' => $precio,
  'modelo' => $modelo,
  'marca' => $marca,
  'proveedor' => $proveedor,
  'categoria' => $categoria,
  'estado' => $estado
];
$_SESSION['productos'][] = $producto;

  // Guardar el producto en el archivo
  guardarProducto($archivoProductos, $producto);

 

    // Preparar el producto para la respuesta
    $productoData = [
        'codigo' => $codigo,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'cantidad' => $cantidad,
        'precio' => $precio,
        'modelo' => $modelo,
        'marca' => $marca,
        'proveedor' => $proveedor,
        'categoria' => $categoria,
        'estado' => $estado,
        'index' => count($_SESSION['productos']) - 1 // Índice del nuevo producto
    ];

    echo json_encode(['success' => true, 'message' => 'Producto agregado correctamente', 'producto' => $productoData]);
    exit;
}

// Lógica para editar producto
if (isset($_POST['action']) && $_POST['action'] === 'editar') {
    // Recibir los datos enviados por AJAX para editar el producto
    $index = $_POST['index'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $proveedor = $_POST['proveedor'];
    $categoria = $_POST['categoria'];
    $estado = $_POST['estado'];

    // Actualizar el producto en la sesión
    $_SESSION['productos'][$index] = [
        'codigo' => $codigo,
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'cantidad' => $cantidad,
        'precio' => $precio,
        'modelo' => $modelo,
        'marca' => $marca,
        'proveedor' => $proveedor,
        'categoria' => $categoria,
        'estado' => $estado
    ];

    // Actualizar el archivo de productos
    actualizarProductos($archivoProductos, $_SESSION['productos']);

    echo json_encode(['success' => true, 'message' => 'Producto editado correctamente']);
    exit;
}

// Lógica para eliminar producto
if (isset($_POST['action']) && $_POST['action'] === 'eliminar') {
    // Recibir el índice del producto a eliminar
    $index = $_POST['index'];

    // Verificar que el índice es válido
    if (isset($_SESSION['productos'][$index])) {
        // Eliminar el producto de la sesión
        unset($_SESSION['productos'][$index]);

        // Reindexar el array para mantener los índices en orden
        $_SESSION['productos'] = array_values($_SESSION['productos']);

        // Actualizar el archivo de productos
        actualizarProductos($archivoProductos, $_SESSION['productos']);

        echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    }
    exit;
}


   
 // Variables para la plantilla
$titulo = "Gestión de Productos";

$pagina = "productos"; 

$contenido = '
 <h3>Listado de Productos</h3>

    <!-- Botón para abrir el modal -->
    <button class="btn btn-primary  mb-3 float-right" data-toggle="modal" data-target="#productoModal"> 
     <i class="fas fa-plus"></i>&nbspNuevo Registro 
    </button>



    <!-- Modal -->
    <div class="modal fade" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productoModalLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="agregarProductoForm">
                        <div class="form-group">
                            <label for="codigo">Código:</label>
                            <input type="text" name="codigo" required class="form-control" id="codigo">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" required class="form-control" id="nombre">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <input type="text" name="descripcion" required class="form-control" id="descripcion">
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" name="cantidad" required class="form-control" id="cantidad">
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" name="precio" step="0.01" required class="form-control" id="precio">
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo:</label>
                            <input type="text" name="modelo" required class="form-control" id="modelo">
                        </div>
                        <div class="form-group">
                            <label for="marca">Marca:</label>
                            <input type="text" name="marca" required class="form-control" id="marca">
                        </div>
                        <div class="form-group">
                            <label for="proveedor">Proveedor:</label>
                            <input type="text" name="proveedor" required class="form-control" id="proveedor">
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría:</label>
                            <input type="text" name="categoria" required class="form-control" id="categoria">
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <input type="text" name="estado" required class="form-control" id="estado">
                        </div>
                        <input type="hidden" name="action" value="agregar">
                        <button type="submit" class="btn btn-primary">Agregar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Proveedor</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($_SESSION['productos'] as $index => $producto) {
            $contenido .= '<tr>
                <td>' . $producto['codigo'] . '</td>
                <td>' . $producto['nombre'] . '</td>
                <td>' . $producto['descripcion'] . '</td>
                <td>' . $producto['cantidad'] . '</td>
                <td>' . $producto['precio'] . '</td>
                <td>' . $producto['modelo'] . '</td>
                <td>' . $producto['marca'] . '</td>
                <td>' . $producto['proveedor'] . '</td>
                <td>' . $producto['categoria'] . '</td>
                <td>' . $producto['estado'] . '</td>
                <td>
                    <button class="btn btn-warning btn-sm editar" data-index="' . $index . '" data-toggle="modal" data-target="#productoModal">Editar</button>
                    <button class="btn btn-danger btn-sm eliminar" data-index="' . $index . '">Eliminar</button>
                </td>
            </tr>';
        }
                $contenido .=
        '</tbody>
    </table>
</div>';

// Incluir la plantilla
include 'template.php';

 
 