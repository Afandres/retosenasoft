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

                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="send_request_button">
                        Enviar Petición
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar los resultados de la predicción y traducción -->
    <div class="modal fade" id="predictionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resultado de Reconocimiento Facial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="image_results"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let imageUrls = []; // Lista para almacenar las URLs de las imágenes
            let imageFiles = []; // Lista para almacenar las imágenes locales

            // Función para mostrar los resultados en el modal
            function showModal(imagesAndResponses) {
                const modalBody = $("#image_results");
                modalBody.empty(); // Limpiar el modal

                imagesAndResponses.forEach((item, index) => {
                    const imageUrl = item.imageUrl;
                    const prediction = item.prediction;

                    // Crear elementos para mostrar la imagen y resultados de predicción
                    const imageDiv = $("<div class='image-result'></div>");
                    const image = $("<img class='result-image'>");
                    image.attr("src", imageUrl);
                    const predictionText = $("<p class='prediction-text'></p>").text("Resultado: " +
                        prediction);

                    // Agregar los elementos al modal
                    imageDiv.append(image);
                    imageDiv.append(predictionText);
                    modalBody.append(imageDiv);
                });

                $("#predictionModal").modal("show");
            }

            // Agregar evento para agregar URL
            $("#add_url_button").click(function() {
                const imageUrl = $("#image_url").val();

                if (imageUrl) {
                    // Crear una nueva imagen para verificar su tamaño
                    const tempImage = new Image();
                    tempImage.src = imageUrl;

                    // Verificar el tamaño de la imagen
                    tempImage.onload = function() {
                        if (tempImage.width < 200 || tempImage.height < 200) {
                            alert(
                                "La imagen es demasiado pequeña. Por favor, elija una imagen más grande.");
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

            // Agregar evento de clic para el botón de Enviar Petición
            $("#send_request_button").click(function() {
                // Verificar si hay al menos una imagen para enviar
                if (imageUrls.length === 0 && imageFiles.length === 0) {
                    alert("Por favor, agregue al menos una imagen antes de enviar.");
                    return;
                }

                const minImageSizeBytes = 1024; // 1KB

                // Función para verificar el tamaño de una imagen
                function checkImageSize(image) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = function() {
                            const imageBlob = new Blob([reader.result]);
                            if (imageBlob.size >= minImageSizeBytes) {
                                resolve();
                            } else {
                                reject();
                            }
                        };
                        reader.readAsArrayBuffer(image);
                    });
                }

                // Validar imágenes locales
                const localImagePromises = imageFiles.map((imageFile) => {
                    return checkImageSize(imageFile);
                });

                // Validar imágenes a través de URL
                const imageUrlPromises = imageUrls.map((imageUrl) => {
                    return new Promise((resolve, reject) => {
                        const tempImage = new Image();
                        tempImage.src = imageUrl;
                        tempImage.onload = function() {
                            if (tempImage.width >= 200 && tempImage.height >= 200) {
                                resolve();
                            } else {
                                reject();
                            }
                        };
                        tempImage.onerror = function() {
                            reject();
                        };
                    });
                });

                // Ejecutar todas las promesas para validar imágenes
                Promise.all([...localImagePromises, ...imageUrlPromises])
                    .then(() => {
                        // Todas las imágenes cumplen con los requisitos de tamaño
                        // Crear un objeto FormData y enviar la solicitud a Azure Face API
                        const formData = new FormData();
                        // Agregar las imágenes al FormData
                        // ...

                        const subscriptionKey = "920a27b0bc944f6e89d2fd2920721065";
                        const endpoint = "https://facesoft.cognitiveservices.azure.com/";

                        // Realizar la solicitud AJAX a tu Azure Function
                        $.ajax({
                            type: "POST",
                            url: `${endpoint}face/v1.0/detect?detectionModel=detection_01`,
                            data: formData,
                            contentType: "application/octet-stream", // Cambia el tipo de contenido aquí
                            processData: false,
                            headers: {
                                "Ocp-Apim-Subscription-Key": subscriptionKey
                            },
                            success: function(response) {
                                // Manejar la respuesta de tu Azure Function aquí (resultado del reconocimiento facial)
                                console.log(response);

                                // Mostrar los resultados en el modal
                                showModal(response);
                            },
                            error: function() {
                                alert("Hubo un error al realizar la solicitud al servidor.");
                            }
                        });
                    })
                    .catch(() => {
                        alert("Una de las imágenes es demasiado pequeña o no se pudo cargar.");
                    });
            });
        });
    </script>
</body>

</html>
