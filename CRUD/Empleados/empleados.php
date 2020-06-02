<?php
    $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
    $txtApellidoP=(isset($_POST['txtApellidoP']))?$_POST['txtApellidoP']:"";
    $txtApellidoM=(isset($_POST['txtApellidoM']))?$_POST['txtApellidoM']:"";
    $txtCorreo=(isset($_POST['txtCorreo']))?$_POST['txtCorreo']:"";
    $txtFoto=(isset($_FILES['txtFoto']["name"]))?$_FILES['txtFoto']["name"]:"";

    $accion=(isset($_POST['accion']))?$_POST['accion']:"";

    $error=array();

    $accionAgregar="";
    $accionModificar=$accionEliminar=$accionCancelar="disabled";
    $mostrarModal=false;
    //incluir archivo de conexion
    include("../Conexion/conexion.php");

switch ($accion) {
    case 'btnAgregar':
        #validaciones
        if($txtNombre==""){
            $error["Nombre"]="Escribe el nombre";
        }
        if($txtApellidoP==""){
            $error["ApellidoP"]="Escribe el Apellido Paterno";
        }
        if($txtApellidoM==""){
            $error["ApellidoM"]="Escribe el Apellido Materno";
        }
        if($txtCorreo==""){
            $error["Correo"]="Escribe el Correo";
        }
        if($txtFoto==""){
            $error["Foto"]="Sube la foto";
        }
        if (count($error)>0){
            $mostrarModal=true;
        break;
        }




            //crear un objeto mediante metodo PDO y agregar instruccion SQL
            $sentencia=$pdo->prepare("INSERT INTO empleados(Nombre,ApellidoP,ApellidoM,Correo,Foto)
            /*Agregar en VALUES la referencia a los valores*/
            VALUES (:Nombre,:ApellidoP,:ApellidoM,:Correo,:Foto)");

            //declarar los valores de las sentencias, con los valores declarados al principio 
            $sentencia->bindParam(':Nombre',$txtNombre);
            $sentencia->bindParam(':ApellidoP',$txtApellidoP);
            $sentencia->bindParam(':ApellidoM',$txtApellidoM);
            $sentencia->bindParam(':Correo',$txtCorreo);
            //se busca una fecha exacta cuando se sube el archivo para evitar replicas
            //Se concatena el nombre del archivo subido y la fecha
            //Caso contrario, se vuelve a la imagen predeterminada
            $Fecha= new DateTime();
            $nombreArchivo=($txtFoto!="")?$Fecha->getTimestamp()."_".$_FILES['txtFoto']["name"]:"imagen.jpg";
            //reolecta la fotografia
            $tmpFoto= $_FILES["txtFoto"]["tmp_name"];
            //Copia al servidor la foto, con la nueva direccion de guardar y nuevo nombre
            if($tmpFoto!=""){
                move_uploaded_file($tmpFoto,"../Imagenes/".$nombreArchivo);
            }

            $sentencia->bindParam(':Foto',$nombreArchivo);
            $sentencia->execute();
            header('Location: index.php');

        break;
    case 'btnModificar':
         //Modifica un objeto mediante metodo PDO y agregar instruccion SQL
         //Los elementos seleccionados, realizan referencia a los elementos a editar. Ejemplo-> Nombre<-Elemento :Nombre <-Declarado en sentencia
            $sentencia=$pdo->prepare(" UPDATE empleados SET
            Nombre=:Nombre,
            ApellidoP=:ApellidoP,
            ApellidoM=:ApellidoM,
            Correo=:Correo WHERE id=:id");
            //declarar los valores de las sentencias, con los valores declarados al principio 
            $sentencia->bindParam(':Nombre',$txtNombre);
            $sentencia->bindParam(':ApellidoP',$txtApellidoP);
            $sentencia->bindParam(':ApellidoM',$txtApellidoM);
            $sentencia->bindParam(':Correo',$txtCorreo);
            $sentencia->bindParam(':id',$txtID);
            $sentencia->execute();
            
                  
            $Fecha= new DateTime();
            $nombreArchivo=($txtFoto!="")?$Fecha->getTimestamp()."_".$_FILES['txtFoto']["name"]:"imagen.jpg";
            //recolecta la fotografia
            $tmpFoto= $_FILES["txtFoto"]["tmp_name"];
            //Copia al servidor la foto, con la nueva direccion de guardar y nuevo nombre
            if($tmpFoto!=""){
                move_uploaded_file($tmpFoto,"../Imagenes/".$nombreArchivo);
            
                $sentencia=$pdo->prepare("SELECT Foto FROM empleados WHERE id=:id");
                $sentencia->bindParam(':id',$txtID);
                $sentencia->execute();
                $empleado=$sentencia->fetch(PDO::FETCH_LAZY);
                print_r($empleado);

                if(isset($empleado["Foto"])){
                    if(file_exists("../Imagenes/".$empleado["Foto"])){

                        if($empleado["Foto"]!="imagen.jpg"){
                        unlink("../Imagenes/".$empleado["Foto"]);
                        }

                    }
                }                             
            $sentencia=$pdo->prepare(" UPDATE empleados SET
            Foto=:Foto WHERE id=:id"); 
            $sentencia->bindParam(':Foto',$nombreArchivo);
            $sentencia->bindParam(':id',$txtID);
            $sentencia->execute();                
            }

            header('Location: index.php');

        echo $txtID;
        echo "Presionaste btnModificar";
        break;
    case 'btnEliminar':

        $sentencia=$pdo->prepare("SELECT Foto FROM empleados WHERE id=:id");
        $sentencia->bindParam(':id',$txtID);
        $sentencia->execute();
        $empleado=$sentencia->fetch(PDO::FETCH_LAZY);
        print_r($empleado);

        if(isset($empleado["Foto"])&&($empleado["Foto"]!="imagen.jpg")){
            if(file_exists("../Imagenes/".$empleado["Foto"])){
                unlink("../Imagenes/".$empleado["Foto"]);

            }
        }
        //Elimina elemento seleccionado via ID y ejecuta en SQL
            $sentencia=$pdo->prepare("DELETE FROM empleados WHERE id=:id");
           //De acuerdo al elemento seleccionado, se envia para lograr la eliminación
            $sentencia->bindParam(':id',$txtID);
            $sentencia->execute();

            header('Location: index.php');
        
        echo $txtID;
        echo "Presionaste btnEliminar";
        break;
    case 'btnCancelar':
        header('Location: index.php');
        break;
  
    
    case 'Seleccionar':
        $accionAgregar="disabled";
        $accionModificar=$accionEliminar=$accionCancelar="";
        $mostrarModal=true;

        $sentencia=$pdo->prepare("SELECT * FROM empleados WHERE id=:id");
        $sentencia->bindParam(':id',$txtID);
        $sentencia->execute();
        $empleado=$sentencia->fetch(PDO::FETCH_LAZY);

        $txtNombre=$empleado['Nombre'];
        $txtApellidoP=$empleado['ApellidoP'];
        $txtApellidoM=$empleado['ApellidoM'];
        $txtCorreo=$empleado['Correo'];
        $txtFoto=$empleado['Foto'];


        break;}
   
//
    $sentencia=$pdo->prepare("SELECT * FROM empleados WHERE 1");
    $sentencia->execute();
    $listaEmpleados=$sentencia->fetchAll(PDO::FETCH_ASSOC);

    

?>