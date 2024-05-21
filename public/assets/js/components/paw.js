class PAW {
    /**
     * Libreria para herramientas js genericas 
     */
   //nuevoElemento("script", "", {scr: URL, name: "nombreDelScript"})  
   static nuevoElemento(tag, contenido, atributos = {}) {
       let elemento = document.createElement(tag);
      
       for (const atributo in atributos) {
           elemento.setAttribute(atributo, atributos[atributo])
       }
      if (contenido.tagName) //revisar si es necesario para el carrousel o no
          elemento.appendChild(contenido); 
      else
          elemento.appendChild(document.createTextNode(contenido));
  
      return elemento;
   }   
  
   static cargarScript (nombre, url, fnCallback = null) {
       let elemento = document.querySelector("script#" + nombre);
       if (!elemento) {
          //Creo el tag script
          elemento = this.nuevoElemento("script","",{src: url, id: nombre});
          
          //Funcion de Callback
          if (fnCallback)
              elemento.addEventListener("load", fnCallback);
  
          document.head.appendChild(elemento);
       }
  
      return elemento;
   }

   //función para agregar stylesheet de css al head mediante un path
   static agregarStyle(path) {
        let css = document.createElement("link");
        css.rel = "stylesheet";
        css.href = path;
        document.head.appendChild(css);
    }

  }