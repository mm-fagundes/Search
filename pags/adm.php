<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>

<div class="container">
    <div class="i1">
        <a href="admprestador.php">Cadastro De Prestadores</a>
    </div>
    <div class="i1">
        <a href="admcliente.php">Cadastro de Clientes</a>
    </div>
</div>

<style>
    .container{
        margin-top: 100px;
        display: flex;
        height: 800px;
        justify-content: space-around;
        
    }

    .container a{
        color: black;
        text-decoration: none;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
               
    }

    .i1{
        background-color: blue;
        width: 35%;
        height: 35%;
        border: 1px solid black;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
    }

</style>



</body>
</html>