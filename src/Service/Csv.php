<?php
//src/Service/Csv.php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

//use App\Entity\SgrEvento;

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
    public function getRowsFilterByKeys($file,$keys)
    {

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
            $row['validations']['existAula'] = false;
            $row['validations']['solapa'] = false;
            $row['validations']['solapaCsv'] = array();
            $row['validations']['valuesNotValid'] = array();
            $rows[] = $row;
            
            //$row['validaciones'] = array('existeEspacio' => false);
            
        }
        
        //dump($filas);
        //dump($rows);
        fclose($manejador);
        //exit;
        return $rows;
    }


    public function checkValidValues($rowsCsv)
    {
        
        $rowsCsv = $this->checkValidValueForColumnTypeDateTime($rowsCsv, 'F_DESDE');
        $rowsCsv = $this->checkValidValueForColumnTypeDateTime($rowsCsv, 'F_HASTA');
        $rowsCsv = $this->checkValidValueForColumnTypeTime($rowsCsv, 'H_INICIO');
        $rowsCsv = $this->checkValidValueForColumnTypeTime($rowsCsv, 'H_FIN');
        $rowsCsv = $this->checkValidValueForColumnDia($rowsCsv, 'C.DIA');

        return $rowsCsv;
    }

    public function checkValidValueForColumnTypeDateTime($rowsCsv, $column)
    {

        foreach ($rowsCsv as $key => $row) {
            
            if ( false == date_create_from_format('d/m/Y', $row[$column], new \DateTimeZone('Europe/Madrid')) )
                $row['validations']['valuesNotValid'][] = 'Formato no válido para ' .  $column ; 

            $rowsCsv[$key] = $row;

        }

        return $rowsCsv;
        
    }

    public function checkValidValueForColumnTypeTime($rowsCsv, $column){

        foreach ($rowsCsv as $key => $row) {
            
            if ( false == date_create_from_format('H:i', $row[$column], new \DateTimeZone('Europe/Madrid')) )
                $row['validations']['valuesNotValid'][] = 'Formato no válido para ' .  $column ; 

            $rowsCsv[$key] = $row;

        }

        return $rowsCsv;
        
    }

    public function checkValidValueForColumnDia($rowsCsv, $column){

        foreach ($rowsCsv as $key => $row) {
            
            if ( empty($column) || $column < 0 || $column > 6)
                $row['validations']['valuesNotValid'][] = 'Formato no válido para ' .  $column ; 

            $rowsCsv[$key] = $row;

        }

        return $rowsCsv;
        
    }

    public function checkIfExistEspacio($rowsCsv, $repositoryEspacio)
    {
        
        foreach ($rowsCsv as $key => $row) {
            
            //Validation existAula
            $sgrEspacio = $repositoryEspacio->exist($row['AULA']);
                
            //Si existe Espacio
            if ($sgrEspacio)
                $row['validations']['existAula'] = true;

            $rowsCsv[$key] = $row;
        }

        return $rowsCsv;
    }

    public function passValidations($row){

        return $row['validations']['existAula'] && empty($row['validations']['valuesNotValid']) && !$row['validations']['solapa'] && !$row['validations']['solapaCsv'];
    }

    public function solapaCsv($rowsCsv,$row)
    {


        $aNumfilas = array();
                
        foreach ($rowsCsv as $key => $r) {
            $row_f_desde = date_create_from_format('d/m/Y', $row['F_DESDE'], new \DateTimeZone('Europe/Madrid'));
            $row_f_hasta = date_create_from_format('d/m/Y', $row['F_HASTA'], new \DateTimeZone('Europe/Madrid'));

            $r_f_desde = date_create_from_format('d/m/Y', $r['F_DESDE'], new \DateTimeZone('Europe/Madrid'));
            $r_f_hasta = date_create_from_format('d/m/Y', $r['F_HASTA'], new \DateTimeZone('Europe/Madrid'));
            if ($r['AULA'] == $row['AULA'] && $r['C.DIA'] == $row['C.DIA']){
                //posible solapamiento
                
                if ( 
                    (
                        ($row_f_desde <= $r_f_desde && $row_f_hasta >= $r_f_desde)
                        
                        || 
                        
                        ($row_f_desde >= $r_f_desde && $row_f_desde <= $r_f_hasta)
 
                    )

                    &&

                    (
                        ($row['H_INICIO'] <= $r['H_INICIO'] && $row['H_FIN'] > $r['H_INICIO'])
                    
                        ||

                        ($row['H_INICIO'] >= $r['H_INICIO'] && $row['H_INICIO'] < $r['H_FIN'])
                    )   
                     ){
                     //hay solapamiento
                        $aNumfilas[] = $r['numfilaCsv'];
                        //return true;
                }//segundo if
            } //primer if
                
        }//fin del foreach

        
        
        return $aNumfilas;
    }

    public function setSolapamientos(ArrayCollection $rowsCsv){
        
        foreach ($rowsCsv as $key => $row) {    
            
                $solapesCsv = new ArrayCollection();//array();
        
                //Evitar solapamientos consigo mismo
                $rowsCsv->removeElement($row);

                $solapesCsv = $this->solapaCsv($rowsCsv,$row);
                if (!empty($solapesCsv)){
                    
                    $row['validations']['solapaCsv'] = $solapesCsv;
                }
              
                //Se vuelve a añadir para seguir testando solapamientos
                $rowsCsv->add($row);
            }
        return $rowsCsv;
    }
}    
?>