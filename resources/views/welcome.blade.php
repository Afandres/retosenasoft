<html lang="es">

<head>
    <title>Acerca de IASOFT</title>
    <style>
        .hero {
            background-image: linear-gradient(180deg, #0000008c 0%, #0000008c 100%), url('{{ asset('images/ia.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            /* Evita la repetición de la imagen */
            clip-path: polygon(100% 0, 100% 82%, 51% 100%, 0 80%, 0 0);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/usuario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
</head>
<main>

    <body>
        <!-- Navbar -->
        <header class="hero">
            <nav class="nav container">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <br>
                <div class="nav_logo">
                    <h2 class="nav_title" id="inicio">SENASOFT AI</h2>
                </div>
                <ul class="nav_link nav_link--menu">
                    <li class="nav_items">
                        <a href="#que_es" class="nav_links">Que es?</a>
                    </li>
                    <li class="nav_items">
                        <a href="#proposito" class="nav_links">Proposito</a>
                    </li>
                    <li class="nav_items">
                        <a href="#mision" class="nav_links">Desarrolladores</a>
                    </li>
                    <li class="nav_items">
                        <a href="#vision" class="nav_links">Recursos</a>
                    </li>
                    </li>
                    <img src="{{ asset('images//close.svg') }}" class="nav_close">
                </ul>
                <img src="{{ asset('images//menu.svg') }}" class="nav_close">
            </nav>
            </nav>
            <section class="hero_container container">
                <h1 class="hero_title">SENASOFT AI</h1>
                <div class="buttonsect">
                    <a href="/computertranslator">Clasificacion</a>
                    <a href="{{ route('face.index') }}">Face</a>
                </div>                
            </section>
        </header>
        <section>
            <div class="knowledge_container container" id="que_es">
                <div class="knowledge_texts">
                    <h2 class="subtitle">Que es SENASOFT AI</h2>
                    <p class="residuo">

                    </p>
                </div>
                <div class="knowledge_picture">
                    <img src="{{ asset('images/azure-services.webp') }}" class="knowledge_img">
                </div>
            </div>
        </section>
        <section class="container about" id="r">
            <h2 class="subtitle"><img src="{{ asset('images/logo.png') }}" alt="Cerco Horizontal"
                    class="cerco-horizontal">¿Qué descubrirás en esta sección?<img src="{{ asset('images/logo.png') }}"
                    alt="Cerco Horizontal" class="cerco-horizontal">
            </h2>
            <p class="about_paragraph">SENASOFT AI software Web</p>
            <div class="about_main">
                <article class="about_icons">
                    <img src="{{ asset('images/proposito.jpg') }}" class="about_icon">
                    <h3 class="about_title">Proposito</h3>
                    <button id="verMasBoton">Ver más</button>
                </article>
                <article class="about_icons">
                    <img src="{{ asset('images/desarrolladores.jpg') }}" class="about_icon">
                    <h3 class="about_title">Desarrolladores</h3>
                    <button id="verMasBotonn"><a>Ver más</a></button>
                </article>
                <article class="about_icons">
                    <img src="{{ asset('images/recursos.png') }}" class="about_icon">
                    <h3 class="about_title">Recursos</h3>
                    <button id="verMasBotonnn"><a>Ver más</a></button>
                </article>
            </div>
        </section>
        <section>
            <div class="knowledge_container container" id="proposito">
                <div class="knowledge_picture">
                    <img src="{{ asset('images/proposit.jpg') }}" class="knowledge_img">
                </div>
                <div class="knowledge_texts">
                    <h2 class="subtitle"><img src="{{ asset('images/logo.png') }}" alt="Cerco Horizontal"
                            class="cerco-horizontal">Proposito</h2>
                    <p class="residuo">
                        Nuestra aplicación es tu asistente todo en uno para imágenes y voz. Puedes cargar imágenes y obtener respuestas claras, incluso en diferentes idiomas. Además, si alguna vez te preguntaste quién está en una foto, también podemos ayudarte a descubrirlo. Es una forma sencilla y práctica de trabajar con imágenes y obtener información útil de manera rápida y eficiente.
                    </p>
                </div>
            </div>
        </section>
        <section class="testimony" id="clasificacion">
            <div class="testimony_container container">
                <img src="{{ asset('images/izquierda.svg') }}" class="testimony_arrow" id="before">
                <!-- Facilitar el Control -->
                <section class="testimony_body  testimony_body--show" data-id="1">
                    <div class="testimony_texts">
                        <h2 class="subtitle">Control de facilidad</h2>
                        <p class="testimony_review">
                            Ofrece la facilidad y conveniencia en la gestión de imágenes y datos visuales. Con un solo clic, puedes obtener respuestas claras sobre el contenido de tus imágenes, traducirlas a diferentes idiomas y, si lo prefieres, escucharlas en voz alta. Además, el reconocimiento facial simplifica la identificación de personas en fotos. Esta versatilidad hace que trabajar con imágenes sea más accesible y eficiente, ahorrandote tiempo y esfuerzo.
                        </p>
                    </div>
                    <figure class="testimony_picture">
                        <img src="{{ asset('images/tractor.jpg') }}" class="testimony_img">
                    </figure>
                </section>
                <!-- Optimizar la Gestión -->
                <section class="testimony_body" data-id="2">
                    <div class="testimony_texts">
                        <h2 class="subtitle">Optimizar la gestion</h2>
                        <p class="testimony_review">
                            Nuestro software proporciona una plataforma integral que abarca desde la carga de imágenes hasta la gestión de datos esenciales relacionados con el procesamiento y análisis de contenido visual. Esto incluye la clasificación de imágenes, la traducción de resultados y la síntesis de voz, lo que facilita una comprensión y toma de decisiones más efectiva en una amplia variedad de aplicaciones.
                        </p>
                    </div>
                    <figure class="testimony_picture">
                        <img src="{{ asset('images/espiga.jpg') }}" class="testimony_img">
                    </figure>
                </section>
                <!-- Centralizar la Información -->
                <section class="testimony_body" data-id="3">
                    <div class="testimony_texts">
                        <h2 class="subtitle">Informacion Central</h2>
                        <p class="testimony_review">
                            El software centraliza la información de manera eficiente, permitiendo a los usuarios acceder a datos críticos relacionados con la clasificación de imágenes, traducción de resultados y reconocimiento facial en una única plataforma accesible. Esto simplifica la toma de decisiones al proporcionar acceso rápido y actualizado a la información relevante, mejorando la eficacia de la comunicación y la colaboración entre los usuarios.
                        </p>
                    </div>
                    <figure class="testimony_picture">
                        <img src="{{ asset('images/arroz.jpg') }}" class="testimony_img">
                    </figure>
                </section>
                
                <img src="{{ asset('images/derecha.svg') }}" class="testimony_arrow" id="next">
            </div>
        </section>
        {{-- Desarrolladores --}}
        <section>
            <div class="knowledge_container container" id="mision">
                <div class="knowledge_texts">
                    <h2 class="subtitle"><img src="{{ asset('images/logo.png') }}" alt="Cerco Horizontal"
                            class="cerco-horizontal">Desarrolladores</h2>
                    <p class="residuo">
                        Este software fue desarrollado por los siguientes desarrolladores:
                    </p>
                    <ul class="desarrolladores">
                        <li>Andres Felipe Almario</li>
                        <li>Brayan David Lizcano</li>
                    </ul>
                </div>
                <div class="knowledge_pictures">
                    <div class="image-container">
                        <img src="{{ asset('images/desarrollo.png') }}" class="knowledge_img1" id="imagen1">
                        <img src="{{ asset('images/desarrollo.png') }}" class="knowledge_img2" id="imagen2">
                    </div>
                </div>
            </div>
        </section>        
        <!-- Recursos -->
        <section class="knowledge" id="vision">
            <div class="knowledge_container container">
                <figure class="knowledge_picture">
                    <div class="knowledge_pictures">
                        <div class="image-container">
                            <img src="{{ asset('images/microsoftazure.jpg') }}" class="knowledge_img1" id="imagen1">
                            <img src="{{ asset('images/microsoftazure.jpg') }}" class="knowledge_img2" id="imagen2">
                        </div>
                </figure>
                <div class="knowledge_texts">
                    <h2 class="subtitle">
                        <img src="{{ asset('images/logo.png') }}" alt="Cerco Horizontal" class="cerco-horizontal">
                        Recursos
                    </h2>
                    <p class="knowledge_paragraph">
                        Nuestra aplicación utiliza una arquitectura de microservicios basada en contenedores Docker para garantizar la escalabilidad y la facilidad de despliegue. Los servicios de IA de Azure, como Custom Vision, Translator, Speech y Face, se integran mediante API RESTful para procesar datos visuales de manera eficiente. Además, implementamos una capa de autenticación basada en tokens JWT para garantizar la seguridad de las solicitudes de los usuarios. La infraestructura se administra en Microsoft Azure, aprovechando Azure Kubernetes Service (AKS) para la orquestación de contenedores y Azure Functions para ejecutar tareas programadas. El front-end se desarrolla en Angular, mientras que el back-end está construido en Laravel.
                    </p>
                </div>
            </div>
        </section>
        
    </body>
</main>

{{-- script para los objetivos de agrocefa --}}
<script>
    (function() {

        const sliders = [...document.querySelectorAll('.testimony_body')];
        const buttonNext = document.querySelector('#next');
        const buttonBefore = document.querySelector('#before');
        let value;


        buttonNext.addEventListener('click', () => {
            changePosition(1);
        });

        buttonBefore.addEventListener('click', () => {
            7
            changePosition(-1);
        });

        const changePosition = (add) => {
            const currentTestimony = document.querySelector('.testimony_body--show').dataset.id;
            value = Number(currentTestimony);
            value += add;


            sliders[Number(currentTestimony) - 1].classList.remove('testimony_body--show');
            if (value === sliders.length + 1 || value === 0) {
                value = value === 0 ? sliders.length : 1;
            }

            sliders[value - 1].classList.add('testimony_body--show');
        }

    })();
</script>

{{-- script para proposito --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Buscar el botón por su identificador
        var verMasBoton = document.getElementById("verMasBoton");

        // Agregar un controlador de eventos al botón
        verMasBoton.addEventListener("click", function() {
            // Encontrar la posición de la sección de propósito
            var proposito = document.getElementById("proposito");

            // Realizar un desplazamiento suave hasta la sección de propósito
            if (proposito) {
                proposito.scrollIntoView({
                    behavior: "smooth"
                });
            }
        });
    });
</script>

{{-- script para mision --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Buscar el botón por su identificador
        var verMasBoton = document.getElementById("verMasBotonn");

        // Agregar un controlador de eventos al botón
        verMasBoton.addEventListener("click", function() {
            // Encontrar la posición de la sección de propósito
            var proposito = document.getElementById("mision");

            // Realizar un desplazamiento suave hasta la sección de propósito
            if (proposito) {
                proposito.scrollIntoView({
                    behavior: "smooth"
                });
            }
        });
    });
</script>

{{-- script para vision --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Buscar el botón por su identificador
        var verMasBoton = document.getElementById("verMasBotonnn");

        // Agregar un controlador de eventos al botón
        verMasBoton.addEventListener("click", function() {
            // Encontrar la posición de la sección de propósito
            var proposito = document.getElementById("vision");

            // Realizar un desplazamiento suave hasta la sección de propósito
            if (proposito) {
                proposito.scrollIntoView({
                    behavior: "smooth"
                });
            }
        });
    });
</script>

</html>
