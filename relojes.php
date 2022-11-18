<?php
abstract class Reloj 
{
    protected $digitos;
    protected $microwattsGastados;
    protected $contadorSeg;
    protected function encender()
    {
        $this->microwattsGastados = 36;
    }
    protected function convertirSegundos(int $digito)
    {
    for($i=0; $i < sizeof($this->digitos); $i++){
        $this->digitos[$i] = intval(date("His", $digito)[$i]);
        }
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
    protected abstract function calcularGastoDigito(int $digito);

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
    protected function calcularGastoDigito(int $digito)
    {
        switch ($digito){
            case 0:
                return 6;
            case 1:
                return 2;
            case 2:
                return 5;
            case 3:
                return 5;
            case 4:
                return 4;
            case 5:
                return 5;
            case 6:
                return 5;
            case 7:
                return 3;
            case 8:
                return 7;
            case 9:
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
        if (($this->contadorDec == sizeof($this->digitos)) && ($this->contadorDec != 0)){
            $this->contadorDec = 0;
            $this->casoCero = 2;
        }else $this->casoCero = 0;
    }
    protected function calcularGastoDigito(int $digito)
    {   
        $this->comprobarCero();
        switch ($digito){
            case 0:
                return $this->casoCero;
            case 1: 
                return 0;
            case 2:
                return 4;
            case 3:
                return 1;
            case 4:
                return 1;
            case 5:
                return 1;
            case 6:
                return 1;
            case 7:
                return 1;
            case 8:
                return 4;
            case 9:
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
$resultado = $relojPremium->getGastoEnergetico(4);
echo 'Reloj Premium (4seg)       : ' . $resultado . "\n";

// Completar con resolucion de punto 2
$unDiaRelojEstandar = $relojEstandar->getGastoEnergetico(86399); 
$unDiaRelojPremium = $relojPremium->getGastoEnergetico(86399);
$microwattsEnUnWatt= 1000000;

$ahorro = ($unDiaRelojEstandar - $unDiaRelojPremium) / $microwattsEnUnWatt;

echo 'Ahorro Premium vs Estandar : ' . $ahorro . "\n";
