<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Registro</title>
</head>
<body>
    <h1>Actualizar Registro</h1>
    
    <form id="updateForm">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
     
        <label for="matricula">Matricula:</label>
        <input type="text" id="matricula" name="matricula" required><br><br>
     
        <label for="correo">Correo:</label>
        <input type="text" id="correo" name="correo" required><br><br>
     
        <label for="edad">Edad:</label>
        <input type="text" id="edad" name="edad" required><br><br>

        <label for="curso">Curso:</label>
        <input type="text" id="curso" name="curso" required><br><br>

        <button type="button" id="putButton">Actualizar con PUT</button>
        <button type="button" id="patchButton">Actualizar con PATCH</button>
    </form>

    <div id="response"></div>

    <script>
        document.getElementById('putButton').addEventListener('click', function () {
            actualizarRegistro('PUT');
        });

        document.getElementById('patchButton').addEventListener('click', function () {
            actualizarRegistro('PATCH');
        });

        function actualizarRegistro(metodo) {
            var nombre = document.getElementById('nombre').value;
            var matricula = document.getElementById('matricula').value;
            var correo = document.getElementById('correo').value;
            var edad = document.getElementById('edad').value;
            var curso = document.getElementById('curso').value;

            var data = new URLSearchParams();
            data.append('nombre', nombre);
            data.append('matricula', matricula);
            data.append('correo', correo);
            data.append('edad', edad);
            data.append('curso', curso);

            fetch('method.php', {
                method: metodo,
                body: data
            })
            .then(function(response) {
                return response.text();
            })
            .then(function(data) {
                document.getElementById('response').textContent = data;
            })
            .catch(function(error) {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
