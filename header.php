<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador Alimentos</title>
    
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .jumbotron {
            background: white;
            color: black;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .jumbotron h1 {
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 16px rgba(0,0,0,.15);
        }

        .table {
            background-color: white;
        }
        
        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        
        .btn {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
            cursor: pointer;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-utensils"></i> Buscador de Alimentos
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-search"></i> Búsqueda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://es.openfoodfacts.org/" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Open Food Facts
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<?php
// Función helper para seleccionar opciones
function check_selected_option($field_name, $value) {
    if(isset($_POST[$field_name]) && $_POST[$field_name] == $value) {
        echo 'selected';
    }
}

?>
