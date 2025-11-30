import os

# Función para reemplazar el texto en cada archivo
def reemplazar_texto(directorio, texto_antiguo, texto_nuevo):
    # Iterar sobre todos los archivos en el directorio
    for archivo_nombre in os.listdir(directorio):
        archivo_ruta = os.path.join(directorio, archivo_nombre)
        
        # Verificar si es un archivo y no un directorio
        if os.path.isfile(archivo_ruta):
            try:
                # Abrir el archivo en modo lectura
                with open(archivo_ruta, 'r', encoding='utf-8') as archivo:
                    contenido = archivo.read()
                
                # Reemplazar el texto
                contenido_nuevo = contenido.replace(texto_antiguo, texto_nuevo)
                
                # Si el contenido ha cambiado, escribir los cambios
                if contenido != contenido_nuevo:
                    with open(archivo_ruta, 'w', encoding='utf-8') as archivo:
                        archivo.write(contenido_nuevo)
                    print(f"Texto reemplazado en el archivo: {archivo_nombre}")
                else:
                    print(f"No se encontraron cambios en el archivo: {archivo_nombre}")
            
            except Exception as e:
                print(f"Error al procesar el archivo {archivo_nombre}: {e}")

# Solicitar al usuario la cadena a reemplazar y la nueva cadena
reemplazar = "http://borocito.local" # ATENTO, REEPLAZARA EL HTTP:// TAMBIEN, SI USAS HTTP O HTTPS, DEBERAS INDICARLO.
directorio = input("Ingresa la ruta del directorio donde se encuentran los archivos: ") # SI ES LA CARPETA ACTUAL EN DONDE DESEAS HACER EL REEMPLAZO, USA '.' (sin comillas, solo el punto)
texto_nuevo = input(f"Ingresa el texto con el que reemplazar '{reemplazar}': ")

# Llamar a la función para hacer el reemplazo
reemplazar_texto(directorio, reemplazar, texto_nuevo)
