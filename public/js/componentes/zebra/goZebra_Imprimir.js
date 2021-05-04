function printZebra(imprimir) {
    BrowserPrint.getDefaultDevice('printer', function(printer)
        {
            printer.send(imprimir);
        }, 
        function(error_response)
        {
                alert("Problemas de coneccion con Zebra Printer. " + 
                "Por favor instalar Zebra Browser Print, o no esta corriendo. " + 
                "Si el problema continua vuelva a Instalar Zebra Browser Print.");
        }
    );
}