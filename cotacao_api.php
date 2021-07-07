<?php

/* 
 * cotacao do dolar em varias moedas, no exemplo USD-BRL e USD-JPY
 * este serviço oferece atualizações a cada 30 segundos
 * gero um insert para incluir em um suposta tabela no mysql
 */

$cotacao = new CotacaoDolar();

$cotacao->api_return("https://economia.awesomeapi.com.br/last/USD-BRL,USD-JPY");
$queryResult = $cotacao->query_return();
    

echo "<textarea>" . $queryResult . "</textarea>";



/**
 * 
 */
class CotacaoDolar {
    
    private $apiResult;
    private $insert;
            
    function __construct() {
        
        $this->apiResult = "";
        $this->insert = "";
        
    }    


    /**
     * 
     * @param type $url
     * @return type
     */
    function api_return( $url ) {


        if(!$fp=fopen($url , "r" )) {
            return false;
        }

        $conteudo = '';
        while(!feof($fp)) 
        { 
            $conteudo .= fgets($fp,1024);
        }
        fclose($fp);

        $this->apiResult = json_decode($conteudo);

        return true;

    }

    
    

    /**
     * 
     * @param type $apiResult
     * @return string
     */
    function query_return() {
        
        if( $this->apiResult == "") {
            return "";
        }
        
        $insert = "";

        // foreach over the object
        foreach ( $this->apiResult as $property => $value ) {
            //echo "Property Name =  $property";
            //echo "<br>";
            foreach ( $value as $property2 => $value2 ) {
                //echo " Property <b>{$property2}</b> Value = $value2<br>";

                if($property2 == "codein") {
                    $insert .= "('{$value2}',";
                }

                if($property2 == "high") {
                    $insert .= "'{$value2}',";
                }

                if($property2 == "create_date") {
                    $insert .= "'{$value2}'),";
                }        

            }

        }

        $insert = "INSERT INTO cotacoes ('moeda','valor','dia') values " . substr( $insert, 0, strlen($insert)-1) . ";";
        
        $this->insert = $insert;

        return $insert;

    }

    
}
