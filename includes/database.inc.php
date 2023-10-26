<?php

$conectar = mysqli_connect('localhost','cms', 'admin123','cms');

if( mysqli_connect_errno()){
    exit('Error al conectar a MySQL: '. mysqli_connect_error());
}