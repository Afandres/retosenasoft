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
                <button type="button" class="btn btn-primary" id="submit_button">
                    <i class='bx bx-right-arrow-alt'></i> Enviar
                </button>   
                <div class="form-group">
                    <label for="image_preview"></label>
                    <img src="" id="image_preview" style="max-width: 100%; max-height: 200px;">
                    
                </div>
                
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
                    <p id="predictionResultTranslater"></p>
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
            
            
            // Agregar evento de clic para el botón de Enviar
            $("#submit_button").click(function () {
                const imageFile = $("#image_file")[0].files[0];

                if (!imageFile) {
                    alert("Por favor, seleccione un archivo de imagen.");
                    return;
                }
    
                // URL y clave de predicción de Custom Vision
                const predictionUrl = "https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/6937ad05-be4d-4d72-97c2-34f194ae1118/classify/iterations/challengeQualification/image";
                const predictionKey = "f396d854b020421c86efedb94f63c183";
    
                // Encabezados de la solicitud
                const headers = {
                    "Prediction-Key": predictionKey
                };
    
                // Crear un objeto FormData para enviar el archivo
                const formData = new FormData();
                formData.append("image", imageFile);

                // Realizar la solicitud AJAX directamente al servicio Custom Vision
                $.ajax({
                    type: "POST",
                    url: predictionUrl,
                    data: formData,
                    contentType: false, 
                    processData: false, 
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

            // Funcion para realizar el envio de la imagen de maner local
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
    
                // Cuerpo de la solicitud URL de la imagen
                const requestBody = { "Url": imageUrl };
    
                // Realizar la solicitud AJAX directamente al servicio Custom Vision
                $.ajax({
                    type: "POST",
                    url: predictionUrl,
                    data: JSON.stringify(requestBody),
                    contentType: "application/json",
                    headers: headers,
                    success: function (response) {
                        console.log(response);
                        const prediction = response.predictions[0].tagName;
                        const confidence = response.predictions[0].probability;

                        if (confidence >= 0.90) {
                            showModal(prediction);
                        } else {
                            alert("No tiene coincidencia");
                        }
                        
                    },
                    error: function () {
                        alert("Hubo un error al realizar la predicción.");
                    }
                });
            });
        });
    </script>
    <script>
    $(document).ready(function () {
        // Función para mostrar el modal con los resultados
        function showModal(translation) {
            $("#predictionResultTranslater").text("Translation: " + translation);
        }

        // Evento para detectar cambios en el campo de texto
        $("#yourTextField").on("input", function () {
            const inputText = $(this).val();

            // Verifica si el campo de texto tiene un valor
            if (inputText) {
                translateText(inputText);
            } else {
                // Si el campo está vacío, borra las traducciones
                $("#predictionResultTranslater").empty();
            }
        });

        // Función para traducir el texto
        function translateText(text) {
            // Reemplaza "YOUR_KEY" y "YOUR_LOCATION" con tus propias claves y ubicación
            const key = "YOUR_KEY";
            const location = "YOUR_LOCATION";
            const endpoint = "https://api.cognitive.microsofttranslator.com/";

            const headers = {
                "Ocp-Apim-Subscription-Key": key,
                "Ocp-Apim-Subscription-Region": location,
                "Content-Type": "application/json",
            };

            const body = [
                {
                    "text": text
                }
            ];

            $.ajax({
                type: "POST",
                url: `${endpoint}/translate?api-version=3.0&from=en&to=fr&to=it&to=zh-Hans`,
                data: JSON.stringify(body),
                headers: headers,
                success: function (response) {
                    const translations = response[0].translations;
                    const translatedText = translations.map(translation => translation.text).join(", ");
                    showModal(translatedText);
                },
                error: function () {
                    alert("Hubo un error al realizar la traducción.");
                },
            });
        }
    });
</script>
</html>
