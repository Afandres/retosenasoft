<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Imágenes</title>
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
    <!-- Contenido principal del documento -->
    <div class="container">
        <!-- Contenedor principal -->
        <div class="card-header">
            <h2>Predicción de Imágenes</h2>
        </div>
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'predict', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <!-- Formulario para subir imágenes -->
                <div class="form-group">
                    {!! Form::label('image_url', 'URL de la imagen') !!}
                    <div class="input-group">
                        {!! Form::text('image_url', old('image_url'), ['class' => 'form-control', 'id' => 'image_url']) !!}
                        <button type="button" class="btn btn-primary" id="add_url_button">
                            <box-icon name='plus-circle' color='#ffffff'></box-icon>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('image_files', 'Subir imágenes locales') !!}
                    <div class="input-group">

                        {!! Form::file('image_files[]', [
                            'class' => 'form-control',
                            'accept' => 'image/*',
                            'multiple' => true,
                            'id' => 'image_files',
                        ]) !!}
                        <button type="button" class="btn btn-primary" id="add_files_button">
                            <box-icon name='plus-circle' color='#ffffff'></box-icon>
                        </button>
                    </div>
                </div>


                <div class="form-group">
                    <label for="image_preview"></label>
                    <img src="" id="image_preview" style="max-width: 50%; max-height: 100px;">
                </div>

                <div class="row" id="image_list">
                    <!-- Aquí se mostrarán las imágenes -->
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="send_request_button">
                        <box-icon name='send' color='#fff9f9'></box-icon>
                    </button>
                </div>

                {!! Form::close() !!}
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-pill shadow" id="detectObjectsButton">Detectar
                Objetos</button>
            <div id="imageContainerResult">
                <!-- Aquí se mostrará la imagen y las líneas de detección -->
            </div>
        </div>
    </div>

    <!-- Modal para mostrar los resultados de la predicción y traducción -->
    <div class="modal fade" id="predictionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resultado de Predicción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contenido de resultados -->
                    <div id="image_results"></div>
                    <p id="text_to_speech_result"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript para el manejo del backend -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/microsoft-cognitiveservices-speech-sdk/3.11.0/speech.sdk.bundle.js">
    </script>

    <script>
        $(document).ready(function() {
            let imageUrls = []; // Lista para almacenar las URLs de las imágenes
            let imageFiles = []; // Lista para almacenar las imágenes locales
            let
                imagesAndResponses = []; // Variable para almacenar la información de todas las imágenes y resultados

            // Función para mostrar los resultados en el modal
            function showModal(imagesAndResponses) {
                const modalBody = $("#image_results");
                modalBody.empty(); // Limpiar e modal

                imagesAndResponses.forEach((item, index) => {
                    const imageUrl = item.imageUrl;
                    const prediction = item.prediction;
                    const translations = item.translations;

                    // Crear elementos para mostrar la imagen y respuestas de predicciones
                    const imageDiv = $("<div class='image-result'></div>");
                    const image = $("<img class='result-image'>");
                    image.attr("src", imageUrl);
                    const predictionText = $("<p class='prediction-text'></p>").text("Predicción: " +
                        prediction);
                    const translationsText = $("<p class='translation-text'></p>").text("Traducciones: " +
                        translations);

                    // Agregar los elementos al modal
                    imageDiv.append(image);
                    imageDiv.append(predictionText);
                    imageDiv.append(translationsText);
                    modalBody.append(imageDiv);
                });

                $("#predictionModal").modal("show");
            }

            // Agregar evento cuando el modal se cierra
            $("#predictionModal").on("hidden.bs.modal", function() {
                // Ocultar el modal
                $(this).hide();

                // Limpiar las imagenes cargadas
                imageUrls = [];
                imageFiles = [];
            });

            // Función para limpiar el contenido
            function clearContent() {
                // Limpiar la lista de imágenes
                $("#image_list").empty();
                // Limpiar el campo de vista previa de la imagen
                $("#image_preview").attr("src", "");
                // Limpiar la lista de imágenes y resultados almacenados

                imagesAndResponses = [];
            }

            // Agregar evento para agregar URL
            $("#add_url_button").click(function() {
                const imageUrl = $("#image_url").val();

                if (imageUrl) {
                    // Agregar la URL a la lista
                    imageUrls.push(imageUrl);

                    // Crear un elemento div para el contenedor de la imagen
                    const imageContainerDiv = document.createElement("div");
                    imageContainerDiv.className = "card"; // Ajusta las clases de Bootstrap según tu diseño
                    imageContainerDiv.style.maxWidth = "30%";
                    // Crear un elemento de imagen
                    const img = document.createElement("img");
                    img.src = imageUrl;

                    // Establecer el tamaño máximo
                    img.style.maxWidth = "100%"; // Ajusta el ancho máximo según tus necesidades

                    // Agregar la imagen al contenedor de la imagen
                    imageContainerDiv.appendChild(img);

                    // Agregar el contenedor de la imagen a la lista de imágenes
                    $("#image_list").append(imageContainerDiv);

                    // Limpiar el campo de entrada de URL
                    $("#image_url").val("");
                } else {
                    alert("Por favor, ingrese una URL válida.");
                }
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

                        // Establecer el tamaño máximo
                        img.style.maxWidth = "100%"; // Ajusta el ancho máximo según tus necesidades

                        // Agregar la imagen al contenedor de la imagen
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



            // Agregar evento de clic para el botón de Enviar Petición
            $("#send_request_button").click(function() {
                // Verificar si hay al menos una imagen para enviar
                if (imageUrls.length === 0 && imageFiles.length === 0) {
                    alert("Por favor, agregue al menos una imagen antes de enviar.");
                    return;
                }
                console.log(imagesAndResponses);
                // Limpiar el contenido antes de enviar la petición
                clearContent();

                // En la función processResponse, crea un objeto para cada imagen y sus resultados y agrégalo a una matriz
                function processResponse(response, imageUrl, prediction) {
                    const confidence = response.predictions[0].probability;

                    if (confidence >= 0.90) {
                        // Realizar la traducción después de obtener la predicción
                        translateText(prediction, imageUrl, response.predictions[0].tagName);

                    } else {
                        alert("No tiene coincidencia");
                    }
                }

                // Función para realizar la solicitud de predicción con una URL
                function predictFromUrl(imageUrl) {
                    // URL y clave de predicción de Custom Vision
                    const predictionUrl =
                        "https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/6937ad05-be4d-4d72-97c2-34f194ae1118/classify/iterations/quialification_Senasoft/url";
                    const predictionKey = "f396d854b020421c86efedb94f63c183";

                    // Encabezados de la solicitud
                    const headers = {
                        "Prediction-Key": predictionKey,
                        "Content-Type": "application/json",
                        "Cache-Control": "no-cache",
                    };

                    // Cuerpo de la solicitud URL de la imagen
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
                            processResponse(response, imageUrl, response.predictions[0]
                                .tagName); // Pasar prediction como argumento
                        },
                        error: function() {
                            alert("Hubo un error al realizar la predicción.");
                        }
                    });
                }


                // Función para realizar la solicitud de predicción con una imagen local
                function predictFromLocalImage(imageFile) {
                    // URL y clave de predicción de Custom Vision
                    const predictionUrl =
                        "https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/6937ad05-be4d-4d72-97c2-34f194ae1118/classify/iterations/quialification_Senasoft/image";
                    const predictionKey = "f396d854b020421c86efedb94f63c183";

                    // Encabezados de la solicitud
                    const headers = {
                        "Prediction-Key": predictionKey,
                        "Cache-Control": "no-cache",
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
                        success: function(response) {
                            processResponse(response, URL.createObjectURL(imageFile), response
                                .predictions[0].tagName); // Pasar prediction como argumento
                        },
                        error: function() {
                            alert("Hubo un error al realizar la predicción.");
                        }
                    });
                }

                // Iterar sobre las URLs de las imágenes y realizar la predicción
                for (let i = 0; i < imageUrls.length; i++) {
                    const imageUrl = imageUrls[i];
                    predictFromUrl(imageUrl);
                }

                // Iterar sobre las imágenes locales y realizar la predicción
                for (let i = 0; i < imageFiles.length; i++) {
                    const imageFile = imageFiles[i];
                    predictFromLocalImage(imageFile);
                }
            });

            // Al llamar a la función showModal, pasa la matriz de objetos con la información de todas las imágenes
            function translateText(text, imageUrl, prediction) {
                // Reemplaza "YOUR_TRANSLATOR_KEY" y "YOUR_TRANSLATOR_LOCATION" con tus propias claves y ubicación
                const translatorKey = "ba422d3db6a6479599f8a479135414e7";
                const translatorLocation = "eastus";
                const translatorEndpoint = "https://api.cognitive.microsofttranslator.com/";

                // Encabezados de la solicitud de traducción
                const translatorHeaders = {
                    "Ocp-Apim-Subscription-Key": translatorKey,
                    "Ocp-Apim-Subscription-Region": translatorLocation,
                    "Content-Type": "application/json",
                    "Cache-Control": "no-cache",
                };

                // Cuerpo de la solicitud de traducción
                const translatorRequestBody = [{
                    "text": text
                }];

                // Realizar la solicitud AJAX al servicio de Traducción de Texto de Azure
                $.ajax({
                    type: "POST",
                    url: `${translatorEndpoint}/translate?api-version=3.0&from=en&to=es&to=fr&to=zh-Hans`,
                    data: JSON.stringify(translatorRequestBody),
                    headers: translatorHeaders,
                    success: function(translationResponse) {
                        // Aquí puedes procesar y mostrar las traducciones en el modal
                        const translations = translationResponse[0].translations;
                        const translatedText = translations.map(translation => translation.text).join(
                            ", ");

                        // Crear un objeto con la información de la imagen y resultados
                        const imageInfo = {
                            imageUrl: imageUrl,
                            prediction: text,
                            translations: translatedText
                        };

                        // Remover resultados anteriores con la misma URL de imagen
                        imagesAndResponses = imagesAndResponses.filter(item => item.imageUrl !==
                            imageUrl);

                        // Agregar el objeto a la matriz de imágenes y resultados
                        imagesAndResponses.push(imageInfo);

                        // Verificar si todas las imágenes han sido procesadas
                        if (imagesAndResponses.length === imageUrls.length + imageFiles.length) {
                            // Luego, muestra todas las imágenes y resultados en el modal
                            showModal(imagesAndResponses);
                        }
                    },
                    error: function() {
                        alert("Hubo un error al realizar la traducción.");
                    },
                });
            }

            // Agregar evento al botón "Detectar Objetos"
            $("#detectObjectsButton").click(function() {
                // Obtener la URL de la imagen
                const imageUrl = $("#image_url").val();

                // Enviar la solicitud AJAX
                predictFromUrl2(imageUrl);

                // Mostrar la imagen
                displayImage(imageUrl);
            });

            // Función para mostrar la imagen
            function displayImage(imageUrl) {
                // Crear un elemento div para el contenedor de la imagen
                const imageContainerDiv = document.createElement("div");
                imageContainerDiv.className = "card"; // Ajusta las clases de Bootstrap según tu diseño
                imageContainerDiv.style.maxWidth = "30%";

                // Crear un elemento de imagen
                const img = document.createElement("img");
                img.src = imageUrl;

                // Establecer el tamaño máximo
                img.style.maxWidth = "100%"; // Ajusta el ancho máximo según tus necesidades

                // Agregar la imagen al contenedor de la imagen
                imageContainerDiv.appendChild(img);

                // Agregar el contenedor de la imagen a la lista de imágenes
                $("#image_list").append(imageContainerDiv);
            }

            // Función para realizar la solicitud AJAX a la API de Azure
            function predictFromUrl2(imageUrl) {
                // URL y clave de predicción de Custom Vision
                const predictionUrl =
                    "https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/4a21c679-b6f1-4337-81c7-b9de39203f07/detect/iterations/Detection_Challenge/url";
                const predictionKey = "f396d854b020421c86efedb94f63c183";

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
                imageContainer.style.position = "relative";
                imageContainer.style.display = "inline-block";
                imageContainer.style.width = imageElement.width + "px";
                imageContainer.style.height = imageElement.height + "px";

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


            import * as sdk from "microsoft-cognitiveservices-speech-sdk";

            // Configuración de las credenciales de Azure Cognitive Services Speech Service
            const subscriptionKey = process.env.AZURE_SPEECH_SERVICE_KEY;
            const serviceRegion = process.env.AZURE_SPEECH_SERVICE_ENDPOINT;
            const speechConfig = sdk.SpeechConfig.fromSubscription(subscriptionKey, serviceRegion);

            // Crear un reconocedor de texto a voz
            const recognizer = new sdk.SpeechRecognizer(speechConfig);

            // Función para convertir texto a voz
            function textoAVoz(prediction, translations) {
                // Texto que deseas convertir en voz (puedes concatenar "prediction" y "translations" si es necesario)
                const textoAReproducir = `${prediction} ${translations}`;

                // Evento que se dispara cuando se recibe la respuesta del servicio
                recognizer.recognizeOnceAsync(
                    result => {
                        if (result.reason === sdk.ResultReason.RecognizedSpeech) {
                            // El texto reconocido se encuentra en result.text
                            console.log("Texto reconocido: " + result.text);

                            // Reproducir el audio del texto convertido a voz
                            const audio = new Audio(result.audioDataUrl);
                            audio.play();
                        } else {
                            console.error("No se pudo reconocer el texto.");
                        }
                    },
                    error => {
                        console.error("Error al reconocer el texto: " + error);
                    }
                );
            }

            // Función para convertir texto a voz y actualizar elementos HTML
            function convertirTextoAVoz() {
                const prediction = document.getElementById("prediction").value;
                const translations = document.getElementById("translations").value;

                // Llamar a la función textoAVoz con los valores de prediction y translations
                textoAVoz(prediction, translations);
            }


        });
    </script>
</body>

</html>
