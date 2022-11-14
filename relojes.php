<?php
abstract class Reloj 
{
    protected $digitos;
    protected $microwattsGastados;
    protected $contadorSeg;
   protected function convertirSegundos(int $digito)
   {
    $horas = floor($digito/3600);
    $minutos = floor(($digito-($horas*3600))/60);
    $segundos = $digito-($horas*3600)-($minutos*60);
    if($horas < 10){
         $this->digitos[0] = '0';
         $this->digitos[1] = strval($horas);
        }else{
            $horas = strval($horas);
            $this->digitos[0] = $horas[0];
            $this->digitos[1] = $horas[1];
        }
    if($minutos < 10){
        $this->digitos[2] = '0';
        $this->digitos[3] = strval($minutos);
        }else{
            $minutos = strval($minutos);
            $this->digitos[2] = $minutos[0];
            $this->digitos[3] = $minutos[1];
        }
    if($segundos < 10){
        $this->digitos[4] = '0';
        $this->digitos[5] = strval($segundos);
        }else{
            $segundos = strval($segundos);
            $this->digitos[4] = $segundos[0];
            $this->digitos[5] = $segundos[1];
        }
    }
    protected function encender()
    {
        $this->microwattsGastados = 36;
    }
    public function getGastoEnergetico(int $segundos)
    {
    $this->encender();    
    $this->contadorSeg = 1;
    while ($this->contadorSeg <= $segundos){
        $this->convertirSegundos($this->contadorSeg);
        foreach($this->digitos as $digito){
            $this->microwattsGastados += $this->calcularGastoDigito($digito);
            }
        $this->contadorSeg++;
        }
        return $this->microwattsGastados;
    }
    protected abstract function calcularGastoDigito(int $num);

}

class RelojEstandar extends Reloj
{
    protected $digitos;
    protected $microwattsGastados;
    protected $contadorSeg;
    function __construct()
    {
        $this->digitos = [0,0,0,0,0,0];
    }
    protected function calcularGastoDigito(int $num)
    {
        switch ($num){
            case '0':
                return 6;
            case '1':
                return 2;
            case '2':
                return 5;
            case '3':
                return 5;
            case '4':
                return 4;
            case '5':
                return 5;
            case '6':
                return 5;
            case '7':
                return 3;
            case '8':
                return 7;
            case '9':
                return 5;
        }
    }
}

class RelojPremium extends Reloj
{
    protected $digitos;
    protected $microwattsGastados;
    public $contadorSeg;
    private $contadorDec;
    private $casoCero;
    function __construct()
    {
        $this->digitos = [0,0,0,0,0,0];
        $this->contadorDec = 0;
    }
    private function comprobarCero()
    {
        if (($this->contadorSeg % 10) == 0){
            $this->contadorDec++;
        }
        if (($this->contadorDec == 6) && ($this->contadorDec != 0)){
            $this->contadorDec = 0;
            $this->casoCero = 2;
        }else $this->casoCero = 0;
    }
    protected function calcularGastoDigito(int $num)
    {   
        $this->comprobarCero();
        switch ($num){
            case '0':
                return $this->casoCero;
            case '1': 
                return 0;
            case '2':
                return 4;
            case '3':
                return 1;
            case '4':
                return 1;
            case '5':
                return 1;
            case '6':
                return 1;
            case '7':
                return 1;
            case '8':
                return 4;
            case '9':
                return 0;
        }
    }   
}


// Casos de Prueba
$relojEstandar = new RelojEstandar();
$resultado     = $relojEstandar->getGastoEnergetico(0);
echo 'Reloj Estandar  (0seg)     : ' . $resultado . "\n";
$resultado = $relojEstandar->getGastoEnergetico(4);
echo 'Reloj Estandar (4seg)      : ' . $resultado . "\n";

$relojPremium = new RelojPremium();
$resultado    = $relojPremium->getGastoEnergetico(0);
echo 'Reloj Premium  (0seg)      : ' . $resultado . "\n";
$resultado = $relojPremium->getGastoEnergetico(10);
echo 'Reloj Premium (4seg)       : ' . $resultado . "\n";

// Completar con resolucion de punto 2
$unDiaRelojEstandar = $relojEstandar->getGastoEnergetico(86400); 
$unDiaRelojPremium = $relojPremium->getGastoEnergetico(86400);
$microwattsEnUnWatt= 1000000;

$ahorro = ($unDiaRelojEstandar - $unDiaRelojPremium) / $microwattsEnUnWatt;

echo 'Ahorro Premium vs Estandar : ' . $ahorro . "\n";


