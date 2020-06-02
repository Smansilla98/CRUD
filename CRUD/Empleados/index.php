<?php
require 'empleados.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>
</head>
<body>
<div class="container">
 <form action="" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Empleado</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div><br>
        <div class="modal-body">
        <div class="form-row">
            <input type="hidden" required name="txtID" value="<?php echo $txtID;?>"placeholder="" id="txtID" require="">
            
                <div class="form-group col-md-4">
            <label for="">Nombre(s):</label>
            <input type="text" class="form-control <?php echo (isset($error["Nombre"]))?"is-invalid":"";?>" required  name="txtNombre" value="<?php echo $txtNombre;?>"placeholder="" id="txtNombre" require="">
            <div class="invalid-feedback">
            <?php echo (isset($error["Nombre"]))?$error["Nombre"]:"";?>
            </div>
            <br></div>

            <div class="form-group col-md-4">
            <label for="">ApellidoP:</label>
            <input type="text" class="form-control <?php echo (isset($error["ApellidoP"]))?"is-invalid":"";?>" required name="txtApellidoP" value="<?php echo $txtApellidoP;?>" placeholder="" id="txtApellidoP" require="">
            <div class="invalid-feedback">
            <?php echo (isset($error["ApellidoP"]))?$error["ApellidoP"]:"";?>
            </div>
            <br></div>

            <div class="form-group col-md-4">
            <label for="">ApellidoM:</label>
            <input type="text" class="form-control <?php echo (isset($error["ApellidoM"]))?"is-invalid":"";?>"   required name="txtApellidoM" value="<?php echo $txtApellidoM;?>" placeholder="" id="txtApellidoM" require="">
            <div class="invalid-feedback">
            <?php echo (isset($error["ApellidoM"]))?$error["ApellidoM"]:"";?>
            </div>
            <br></div>    

            <div class="form-group col-md-12">        
            <label for="">Correo:</label>
            <input type="email"class="form-control <?php echo (isset($error["Correo"]))?"is-invalid":"";?>"   required name="txtCorreo" value="<?php echo $txtCorreo;?>" placeholder="" id="txtCorreo" require="">
            <div class="invalid-feedback">
            <?php echo (isset($error["Correo"]))?$error["Correo"]:"";?>
            </div>
            <br></div>
            
            <div class="form-group col-md-12">  
            <label for="">Foto:</label>
            <?php if($txtFoto!=""){?>
            <br/>
            <img class="img-thumbnail rounded mx-auto d-block" width="100px" src="../Imagenes/<?php echo $txtFoto;?>" /> 
            <br/>
            <br/>
            <?php }?>


            <input type="file" class="form-control <?php echo (isset($error["Foto"]))?"is-invalid":"";?>"  accept="image/*" name="txtFoto" value="<?php echo $txtFoto;?>" placeholder="" id="txtFoto" require="">
            <div class="invalid-feedback">
            <?php echo (isset($error["Foto"]))?$error["Foto"]:"";?>
            </div>
            </div><br>
            
        </div>
        </div>
        <div class="modal-footer col-md-12">
          <button value="btnAgregar"  <?php echo $accionAgregar;?> class="btn btn-success" type="submit" name="accion">Agregar</button>
          <button value="btnModificar"<?php echo $accionModificar;?> class="btn btn-warning" type="submit" name="accion">Modificar</button>
          <button value="btnEliminar" onclick="return Confirmar('¿Realmente deseas eliminar?');" <?php echo $accionEliminar;?> class="btn btn-danger" type="submit" name="accion">Eliminar</button>
          <button value="btnCancelar" <?php echo $accionCancelar;?> class="btn btn-primary" type="submit" name="accion">Cancelar</button>          
        </div>
        </div>
    </div>
    </div>
    <br/><br/>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Agregar registro +  
    </button>
    <br>
    <br>
    
    
  
    
 </form>
    <!-- Armado de tabla para devolucion de datos via PHP -->
    <div class="row">
       <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Foto</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!--Se realiza foreach de la lista de registros-->
            <?php foreach($listaEmpleados as $empleado){ ?>
            <tr><!--Se imprime dato buscado item por item, con el nombre y el dato que se desea devolver-->
                <td><img class="img-thumbnail" width="100px" src="../Imagenes/<?php echo $empleado['Foto'];?>" /></td>
                <td><?php echo $empleado['Nombre'];?> <?php echo $empleado['ApellidoP'];?> <?php echo $empleado['ApellidoM'];?></td>
                <td><?php echo $empleado['Correo'];?></td>
                <td>
                <!--se recupera de forma oculta la informacion seleccionada, y se va directo al formulario original -->
                <form action="" method="post">
                <input type="hidden" name="txtID" value="<?php echo $empleado['ID'];?>">
                <input type="submit" value="Seleccionar" class="btn btn-info" name="accion">
                <button value="btnEliminar" onclick="return Confirmar('¿Realmente deseas eliminar?');" type="submit" class="btn btn-danger" name="accion">Eliminar</button>


                

                </form>
                </td>
            </tr>

            <?php } ?>
        </table>

</div>

<?php if($mostrarModal){?>
    <script>
        $('#exampleModal').modal("show");
    </script>
<?php }?>
<script>
    function Confirmar(Mensaje) {
        return(confirm(Mensaje))?true:false;
        
    }
</script>
</div>

</body>
</html>