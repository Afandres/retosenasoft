// Importar los módulos necesarios
import * as sdk from "microsoft-cognitiveservices-speech-sdk";
import * as Audio from "audio";

// Las credenciales de Azure Cognitive Services Speech Service
const subscriptionKey = "be5189ea07e444b8ac4b82166a0ae2bd";
const serviceRegion = "eastus";

// Crear un objeto SpeechConfig
const speechConfig = new sdk.SpeechConfig(subscriptionKey, serviceRegion);

// Crear un objeto SpeechRecognizer
const recognizer = new sdk.SpeechRecognizer(speechConfig);

// Iniciar el proceso de reconocimiento de voz
recognizer.recognizeOnceAsync(
  result => {
    // El texto reconocido se encuentra en result.text
    console.log("Texto reconocido: " + result.text);

    // Reproducir el audio del texto convertido a voz
    const audio = new Audio(result.audioDataUrl);
    audio.play();
  },
  error => {
    console.error("Error al reconocer el texto: " + error);
  }
);
