<?php
require "config/Conexion.php";

  //print_r($_SERVER['REQUEST_METHOD']);
  switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
      // Consulta SQL para seleccionar datos de la tabla
  $sql = "SELECT nombre, curso, matricula, correo, edad FROM alumno";

  $query = $conexion->query($sql);

  if ($query->num_rows > 0) {
    $data = array();
    while ($row = $query->fetch_assoc()) {
        $data[] = $row;
    }
    // Devolver los resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
  } else {
    echo "No se encontraron registros en la tabla.";
  }

  $conexion->close();
      break;

    case 'POST':
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recibir los datos del formulario HTML
        $nombre = $_POST['nombre'];
        $curso = $_POST['curso'];
        $matricula = $_POST['matricula'];
        $correo = $_POST['correo'];
        $edad = $_POST['edad'];
     
    
        // Insertar los datos en la tabla
        $sql = "INSERT INTO alumno (nombre, curso, matricula, correo, edad ) VALUES ('$nombre', '$curso', '$matricula','$correo', '$edad')"; // Reemplaza con el curso de tu tabla
    
        if ($conexion->query($sql) === TRUE) {
            echo "Datos insertados con éxito.";
        } else {
            echo "Error al insertar datos: " . $conexion->error;
        }
    } else {
        echo "Esta API solo admite solicitudes POST.";
    }
    
    $conexion->close();
      break;

      case 'PATCH':
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
          parse_str(file_get_contents("php://input"), $datos);
          $nombre = $datos['nombre'];
          $matricula = $datos['matricula'];
          $edad = $datos['edad'];
          $correo = $datos['correo'];
          $curso = $datos['curso'];
      
          if ($_SERVER['REQUEST_METHOD'] === 'PATCH') { // Método PATCH
              $actualizaciones = array();
              if (!empty($nombre)) {
                $actualizaciones[] = "nombre = '$nombre'";
              }
              if (!empty($matricula)) {
                  $actualizaciones[] = "matricula = '$matricula'";
              }
              if (!empty($edad)) {
                  $actualizaciones[] = "edad = '$edad'";
              }
              if (!empty($correo)) {
                  $actualizaciones[] = "correo = '$correo'";
              }
              if (!empty($curso)) {
                $actualizaciones[] = "curso = '$curso'";
            }
      
              $actualizaciones_str = implode(', ', $actualizaciones);
              $sql = "UPDATE alumno SET $actualizaciones_str WHERE Id = $id_alumno";
          }
      
          if ($conexion->query($sql) === TRUE) {
              echo "Registro actualizado con éxito.";
          } else {
              echo "Error al actualizar registro: " . $conexion->error;
          }
      } else {
          echo "Método de solicitud no válido.";
      }
      
      $conexion->close();
       break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        // Asegúrate de que los datos necesarios estén presentes
        if (isset($input['nombre']) && isset($input['curso']) && isset($input['matricula']) && isset($input['correo']) && isset($input['edad'])) {
            $nombre = $datos['nombre'];
            $curso = $input['curso'];
            $matricula = $input['matricula'];
            $correo = $input['correo'];
            $edad = $input['edad'];

            $sql = "INSERT INTO alumno (nombre, curso, matricula, correo, edad) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);

            // Enlaza los parámetros y sus tipos
            $stmt->bind_param("sssi",$nombre, $curso, $matricula, $correo, $edad);

            if ($stmt->execute()) {
                $response = array("message" => "Registro insertado con éxito.");
                echo json_encode($response);
            } else {
                $response = array("error" => "Error al insertar registro: " . $stmt->error);
                echo json_encode($response);
            }

            $stmt->close();
        } else {
            $response = array("error" => "Faltan datos obligatorios en la solicitud.");
            echo json_encode($response);
        }
      break;
        
    case 'DELETE':
        // Obtener el contenido del cuerpo de la solicitud
        $json = file_get_contents('php://input');
        
        // Decodificar el JSON en un array asociativo
        $data = json_decode($json, true);
        
        // Verificar si la solicitud es DELETE
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Verificar si se proporciona el parámetro Id en el JSON
            if (isset($data['Id'])) {
                // Procesar solicitud DELETE
                $Id = $data['Id'];
                $sql = "DELETE FROM alumno WHERE Id = $Id";
        
                // Realizar la consulta DELETE
                if ($conexion->query($sql) === TRUE) {
                    echo "Registro eliminado con éxito.";
                } else {
                    echo "Error al eliminar registro: " . $conexion->error;
                }
            } else {
                echo "El parámetro Id no se proporcionó en el JSON.";
            }
        } else {
            echo "Método de solicitud no válido.";
        }
        
        // Cerrar la conexión a la base de datos
        $conexion->close();
      break;


     default:
       echo 'undefined request type!';
  }
?>