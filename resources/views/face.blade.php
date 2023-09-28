<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reconocimiento Facial</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="container">
        <div class="card-header">
            <h2>Reconocimiento Facial</h2>
        </div>
        <div class="card">
            <div class="card-body">
                <!-- Formulario para subir imágenes -->
                <div class="form-group">
                    <label for="image_url">URL de la imagen</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="image_url">
                        <button type="button" class="btn btn-primary" id="add_url_button">
                            Agregar URL
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image_files">Subir imágenes locales</label>
                    <div class="input-group">
                        <input type="file" class="form-control" accept="image/*" multiple id="image_files">
                        <button type="button" class="btn btn-primary" id="add_files_button">
                            Agregar imágenes locales
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_preview">Vista previa de la imagen</label>
                    <img src="" id="image_preview" style="max-width: 50%; max-height: 100px;">
                </div>

                <div class="row" id="image_list">
                    <!-- Aquí se mostrarán las imágenes -->
                </div>
                <div class="row" id="imageContainerResult">
                    <!-- Aquí se mostrará la imagen y las líneas de detección -->
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary"
                    id="detectObjectsButton">Detectar Objetos</button><br>              
                </div>                
            </div>
        </div>
    </div>

    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let imageUrls = []; // Lista para almacenar las URLs de las imágenes
            let imageFiles = []; // Lista para almacenar las imágenes locales

           
            // Declarar la variable global para imageUrl
            let imageUrl = "";

            // Agregar evento para agregar URL
            $("#add_url_button").click(function() {
                imageUrl = $("#image_url").val();

                if (imageUrl) {
                    // Crear una nueva imagen para verificar su tamaño
                    const tempImage = new Image();
                    tempImage.src = imageUrl;

                    // Verificar el tamaño de la imagen
                    tempImage.onload = function() {
                        if (tempImage.width < 200 || tempImage.height < 200) {
                            alert(
                                "La imagen es demasiado pequeña. Por favor, elija una imagen más grande."
                            );
                        } else {
                            // La imagen cumple con los requisitos de tamaño
                            // Agregar la URL a la lista
                            imageUrls.push(imageUrl);

                            // Crear un elemento div para el contenedor de la imagen
                            const imageContainerDiv = document.createElement("div");
                            imageContainerDiv.className = "card";
                            imageContainerDiv.style.maxWidth = "30%";

                            // Crear un elemento de imagen
                            const img = document.createElement("img");
                            img.src = imageUrl;
                            img.style.maxWidth =
                                "100%"; // Ajustar el ancho máximo según tus necesidades

                            // Agregar la imagen al contenedor
                            imageContainerDiv.appendChild(img);

                            // Agregar el contenedor de la imagen a la lista de imágenes
                            $("#image_list").append(imageContainerDiv);

                            // Limpiar el campo de entrada de URL
                            $("#image_url").val("");
                        }
                    };
                } else {
                    alert("Por favor, ingrese una URL válida.");
                }
            });

            // Agregar evento al botón "Detectar Objetos"
            $("#detectObjectsButton").click(function() {
                // Verificar si hay al menos una imagen para enviar
                if (imageUrls.length === 0 && imageFiles.length === 0) {
                    alert("Por favor, agregue al menos una imagen antes de enviar.");
                    return;
                }

                // Enviar la solicitud AJAX
                predictFromUrl2(imageUrl);


            });

            // Agregar evento de clic para el botón de Agregar imágenes locales
            $("#add_files_button").click(function() {
                const files = $("#image_files")[0].files;

                if (files.length) {
                    // Agregar las imágenes locales a la lista
                    for (let i = 0; i < files.length; i++) {
                        imageFiles.push(files[i]);

                        // Crear un elemento div para el contenedor de la imagen
                        const imageContainerDiv = document.createElement("div");
                        imageContainerDiv.className = "card";
                        imageContainerDiv.style.maxWidth = "30%";

                        // Crear un elemento de imagen y agregarlo al contenedor
                        const img = document.createElement("img");
                        img.src = URL.createObjectURL(files[i]);
                        img.style.maxWidth = "100%"; // Ajustar el ancho máximo según tus necesidades

                        // Agregar la imagen al contenedor
                        imageContainerDiv.appendChild(img);

                        // Agregar el contenedor de la imagen a la lista de imágenes
                        $("#image_list").append(imageContainerDiv);
                    }
                    // Limpiar el campo de selección de archivos
                    $("#image_files").val("");
                } else {
                    alert("Por favor, seleccione al menos un archivo de imagen.");
                }
            });

            // Función para realizar la solicitud AJAX a la API de Azure
            function predictFromUrl2(imageUrl) {
                // URL y clave de predicción de Custom Vision
                const predictionUrl =
                    "https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/621e83dd-7b53-4257-8203-e571dd38168c/detect/iterations/Face_detector/url";
                const predictionKey = "f396d854b020421c86efedb94f63c183";
                console.log(imageUrl);
                // Encabezados de la solicitud
                const headers = {
                    "Prediction-Key": predictionKey,
                    "Content-Type": "application/json",
                    "Cache-Control": "no-cache",
                };

                // Cuerpo de la solicitud con la URL de la imagen
                const requestBody = {
                    "Url": imageUrl
                };

                // Realizar la solicitud AJAX directamente al servicio Custom Vision
                $.ajax({
                    type: "POST",
                    url: predictionUrl,
                    data: JSON.stringify(requestBody),
                    contentType: "application/json",
                    headers: headers,
                    success: function(response) {
                        console.log(response);

                        // Llamar a la función displayDetectionResults para mostrar los resultados
                        displayDetectionResults(imageUrl, response);
                    },
                    error: function() {
                        alert("Hubo un error al realizar la predicción.");
                    }
                });
            }

            function displayDetectionResults(imageUrl, response) {
                const imageContainer = document.getElementById("imageContainerResult");
                imageContainer.innerHTML = ""; // Limpiar el contenido existente en el div

                // Obtener la lista de predicciones
                const predictions = response.predictions;

                // Filtrar las predicciones con una probabilidad igual o mayor a 0.90
                const filteredPredictions = predictions.filter((prediction) => prediction.probability >= 0.90);

                // Crear un elemento de imagen para mostrar la imagen original
                const imageElement = document.createElement("img");
                imageElement.src = imageUrl; // Usar la URL de la imagen pasada como parámetro

                // Agregar la imagen al contenedor
                imageContainer.appendChild(imageElement);

                // Crear un contenedor relativo para la imagen y las líneas
                const container = document.createElement("div");
                imageContainer.className = "card";
                imageContainer.style.maxWidth = "30%";

                // Agregar el contenedor al contenedor de la imagen
                imageContainer.appendChild(container);

                // Dibujar líneas de detección en el contenedor relativo
                for (const prediction of filteredPredictions) {
                    const tagName = prediction.tagName;
                    const boundingBox = prediction.boundingBox;

                    // Obtener las coordenadas de la caja delimitadora
                    const left = boundingBox.left * imageElement.width;
                    const top = boundingBox.top * imageElement.height;
                    const width = boundingBox.width * imageElement.width;
                    const height = boundingBox.height * imageElement.height;

                    // Crear un elemento DIV para representar la línea de detección
                    const detectionLine = document.createElement("div");
                    detectionLine.style.position = "absolute";
                    detectionLine.style.left = left + "px";
                    detectionLine.style.top = top + "px";
                    detectionLine.style.width = width + "px";
                    detectionLine.style.height = height + "px";
                    detectionLine.style.border = "2px solid #FF0000"; // Color rojo

                    // Agregar el nombre de la etiqueta como etiqueta de texto
                    const label = document.createElement("div");
                    label.style.position = "absolute";
                    label.style.left = left + "px";
                    label.style.top = top - 20 + "px"; // Ajustar la posición vertical de la etiqueta
                    label.style.color = "#FF0000";
                    label.innerText = tagName;

                    // Agregar la línea de detección y la etiqueta al contenedor relativo
                    container.appendChild(detectionLine);
                    container.appendChild(label);
                }
            }




        });
    </script>
</body>

</html>
