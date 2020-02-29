<?php
//src/Service/Csv.php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Csv extends AbstractController{


    /**
     * @param $file //archivo csv
     * @param $columnas //columnas que debe tener el archivo csv
     *
     * @return $keyError //array con columnas no encontradas
    */
	public function isValidHeader($file,$columnas){
        
        $manejador = fopen($file,"r");

        if ( ( $fila = fgetcsv($manejador,0,',','"') ) == NULL) return array();// Pendiente: --> lanzar exception

        $keyError = array();
        foreach ($columnas as $columna) {
            if (in_array($columna, $fila) === false) $keyError[] = $columna;
        }
        
        fclose($manejador);
        
        return $keyError;
	}
    
    /**
     * @param $file //archivo csv
     * @param $keys //columnas a leer del archivo csv
     *
     * @return $rows //array con los valores de las columnas $keys  
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
            $row['numfilaCsv'] = $numfila;
            $rows[] = $row;
            
            //$row['validaciones'] = array('existeEspacio' => false);
            
        }
        
        //dump($filas);
        dump($rows);
        fclose($manejador);
        exit;
        return $rows;
    }


    public function solapaCsv($rowsCsv,$row){


        $aIndicesSolapes = array();
        foreach ($rowsCsv as $key => $r) {
                            
            if ($r['AULA'] == $row['AULA'] && $r['C.DIA'] == $row['C.DIA']){
                //posible solapamiento
                if ( 
                    (
                        ($r['F_DESDE'] <= $row['F_HASTA'] && $row['F_HASTA'] <= $r['F_HASTA'])
                        
                        || 
                        
                        ($r['F_DESDE'] <= $row['F_DESDE'] && $row['F_DESDE'] <= $r['F_HASTA'])
 
                    )

                    &&

                    (
                        ($r['H_INICIO'] <= $row['H_INICIO'] && $r['H_FIN'] > $row['H_INICIO'])
                    
                        ||

                        ($r['H_INICIO'] < $row['H_FIN'] && $r['H_FIN'] >= $row['H_FIN'])
                    )   
                     ){
                     //hay solapamiento
                        $aIndicesSolapes[] = $r['numfilaCsv'];
                        //return true;
                }//segundo if
            } //primer if
                
        }//fin del foreach
        
        return $aIndicesSolapes;
    }
}    
?>