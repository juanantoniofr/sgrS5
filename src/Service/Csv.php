<?php
//src/Service/Csv.php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Csv extends AbstractController{

	private $columnas; //Keys columnas a leer del csv
	private $columnasCsv; //Keys todas las columnas del CSV
	private $file; //nombre del archivo csv
	private $fila; //fila leida csv
	private $datos; //asociativo donde key=nombreColumnaCSV, Value=valorDeColumnaEnUnaFila
	private $f; //puntero de lectura

	public function __construct($columnasCsv = [],$columnasValidas = [],$file = '' ){

		$this->columnasCsv = $columnasCsv;
		$this->columnas = $columnasValidas;
		$this->file = $file;
	
    }

	public function open(){

        if (!$this->file)
            return null;
		
        return ($this->f = fopen($this->file,"r"));
	}

	public function close(){
		
        return fclose($this->f);
	}
    /**
     * bueno
    */
	public function isValidHeader($file,$columnas){
        
        $manejador = fopen($file,"r");

        if ( ( $fila = fgetcsv($manejador,0,',','"') ) == NULL) return false;

        $keyError = array();
        foreach ($columnas as $columna) {
            if (in_array($columna, $fila) === false) $keyError[] = $columna;
        }
        
        fclose($manejador);
        
        if (!$keyError)
            return true;
        
        return $keyError;
	}
    /*
     * Bueno
    */
    public function getRowsFilterByKeys($file,$keys){

        $rows = array();
        $manejador = fopen($file,"r");

        //Primera fila del Csv: contiene las caabeceras.
        if ( ( $keysCsv = fgetcsv($manejador,0,',','"') ) == NULL) return false;
        
        //$aIndices, contine las posiciones de las columnas con valores válidos. Se pasan por parámetro $columnas
        //$aIndices = array();
        foreach ($keys as $key) {

            $position = array_search($key, $keysCsv);
            if ($position){
                $positions[] = $position;
                $mappedKeys[$key] = $position;
            }
        }

        //dump($keysCsv);
        //dump($keys);
        //dump($positions);
        //dump($mappedKeys);
        //exit;
        $numfila = 0;
        while ( ($fila = fgetcsv($manejador,0,',','"')) != NULL) {
            
            $filasCsv[] = $fila;
            
            //Guardar en $row las columnas validas de la fila leida
            $row = array();
            $numfila = $numfila + 1;
            foreach ($mappedKeys as $key => $positionCsvFila) {
                
                $row[$key] = $fila[$positionCsvFila];
            }
            $rows[] = $row;
            $row['numfilaCsv'] = $numfila;
            $row['validaciones'] = array('existeEspacio' => false);
            
        }
        
        //dump($filas);
        //dump($rows);
        fclose($manejador);
        //exit;
        return $rows;

    }
	public function leeFila() {
		
        if ( ( $this->fila = fgetcsv($this->f,0,',','"') ) == NULL) return false;
		$indice = 0;
		foreach ($this->columnasCsv as $columna) {
			$this->datos[$columna] = $this->fila[$indice];
			$indice++;
		}
		return true;
	}


	/**
        * 
        * Comprueba que el fichero CSV no es vacío, y al mneos, contiene las columnas a leer
        * 
        * @param $file :file
        *
        * @return $resultado :array ('error' => true|false, 'columnasNoValida' => 'vacio|columnas no validas')  
        *
        *
    */

	public function isValidCsv(){
        
        $resultado = array( 'error' => false,
                            'columnasNoValidas' => array(),
                            'msg-error' => 'Fichero no válido: <br />',
                        );
        
        if (empty($this->file)){
            $resultado['error'] = true;
            $resultado['msg-error'] = 'No se ha seleccionado ningún archivo *.csv';
            return $resultado;
        }

        $columnasValidas = $this->columnas;
        $f = fopen($this->file,"r");
        //Lee nombres de las columnas
        $columnasCSV = fgetcsv($f,0,',','"');
        foreach($columnasValidas as $columna){
            if (in_array($columna, $columnasCSV) === false) {
                $resultado['error'] = true;
                $resultado['msg-error'] = $resultado['msg-error'] . 'columna ' . $columna . 'no encontrada <br />';
                $resultado['columnasNoValidas'][] = $columna;    
            } 
        }
        fclose($f);
        return $resultado;
    }

    

    /**
        * 
        * Devuelve el valor de la Key=$columna del array $fila
        * 
        * @param $fila :array
        * @param $columna :string
        * 
        * @return $fila :array   
        *
        *
    */

    public function getValue($columna){

        return $this->datos[$columna];
    }


}

?>