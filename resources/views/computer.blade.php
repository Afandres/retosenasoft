<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Imágenes</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="card-header">
            <h2>Predicción de Imágenes</h2>
        </div>
        <div class="card">
            <div class="card-body">
                {!! Form::open([ 'route' => 'predict' ,'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group">
                    {!! Form::label('image_url', 'URL de la imagen') !!}
                    <div class="input-group">
                        {!! Form::text('image_url', old('image_url'), ['class' => 'form-control', 'id' => 'image_url']) !!}
                        <button type="button" class="btn btn-primary" id="upload_button">
                            <i class='bx bx-right-arrow-alt'></i> Cargar desde URL
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('image_file', 'Subir imagen local') !!}
                    {!! Form::file('image_file', ['class' => 'form-control', 'accept' => 'image/*']) !!}
                </div>
                <div class="form-group">
                    <label for="image_preview"></label>
                    <img src="" id="image_preview" style="max-width: 100%; max-height: 200px;">
                </div>
                <!-- Agregar el botón de enviar -->
                <button type="button" class="btn btn-primary" id="submit_button">
                    <i class='bx bx-right-arrow-alt'></i> Enviar
                </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="predictionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resultado de Predicción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="predictionResult"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Función para mostrar el modal con los resultados
            function showModal(prediction) {
                $("#predictionResult").text("Predicción: " + prediction);
                $("#predictionModal").modal("show");
            }
    
            $("#submit_button").click(function () {
                const imageUrl = $("#image_url").val();
                const file = $("#image_file")[0].files[0];
    
                if (!imageUrl && !file) {
                    alert("Por favor, seleccione una imagen o ingrese una URL.");
                    return;
                }
    
                // URL y clave de predicción de Custom Vision
                const predictionUrl = "https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/6937ad05-be4d-4d72-97c2-34f194ae1118/classify/iterations/challengeQualification/url";
                const predictionKey = "f396d854b020421c86efedb94f63c183";
    
                // Encabezados de la solicitud
                const headers = {
                    "Prediction-Key": predictionKey,
                    "Content-Type": "application/json",
                };
    
                // Cuerpo de la solicitud en formato JSON
                const requestBody = { "Url": imageUrl };
    
                // Realizar la solicitud AJAX directamente al servicio Custom Vision
                $.ajax({
                    type: "POST",
                    url: predictionUrl,
                    data: JSON.stringify(requestBody),
                    contentType: "application/json",
                    headers: headers,
                    success: function (response) {
                        const prediction = response.predictions[0].tagName;
                        showModal(prediction);
                    },
                    error: function () {
                        alert("Hubo un error al realizar la predicción.");
                    }
                });
            });
    
            // Agregar evento de clic para el botón de la flecha junto al campo de la URL
            $("#upload_button").click(function () {
                const imageUrl = $("#image_url").val();
    
                if (!imageUrl) {
                    alert("Por favor, ingrese una URL válida.");
                    return;
                }
    
                // URL y clave de predicción de Custom Vision
                const predictionUrl = "https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/6937ad05-be4d-4d72-97c2-34f194ae1118/classify/iterations/challengeQualification/url";
                const predictionKey = "f396d854b020421c86efedb94f63c183";
    
                // Encabezados de la solicitud
                const headers = {
                    "Prediction-Key": predictionKey,
                    "Content-Type": "application/json",
                };
    
                // Cuerpo de la solicitud en formato JSON
                const requestBody = { "Url": imageUrl };
    
                // Realizar la solicitud AJAX directamente al servicio Custom Vision
                $.ajax({
                    type: "POST",
                    url: predictionUrl,
                    data: JSON.stringify(requestBody),
                    contentType: "application/json",
                    headers: headers,
                    success: function (response) {
                        const prediction = response.predictions[0].tagName;
                        showModal(prediction);
                    },
                    error: function () {
                        alert("Hubo un error al realizar la predicción.");
                    }
                });
            });
        });
    </script>
</body>
</html>
