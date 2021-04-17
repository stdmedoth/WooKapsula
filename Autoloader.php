<?php 

class Autoload {    
	

	public function __construct() {
        spl_autoload_register(array($this, 'load_kapsula_classes', ));
    }

    /*
		@param String $ClassName
		Carrega as classes no diretório pelo nome
		Namespace\Classe -> Classe
	*/
    private function load_kapsula_classes($ClassName) {  

        $class_folders = [
            'Kapsula' => '/Kapsula/',
            'WooKapsula' => '/WooCommerceKapsula/'
        ];

    	$ClassName = ltrim($ClassName, '\\');
        $file = '/';

    	if ($lastNsPos = strrpos($ClassName, '\\')) {
    		$namespace = substr($ClassName, 0, $lastNsPos);
            $import = false;
            foreach ($class_folders as $key => $value) {
                if($namespace == $key){
                    $file = __DIR__ . $value;
                    $import = true;
                    break;
                }
            }

            if(!$import){
                return ;
            }

            $ClassName     = substr($ClassName, $lastNsPos + 1);            
	        $file .= str_replace('\\', '/', $ClassName) . '/' . $ClassName . ".php";    			
            //var_dump($file);
            include ( $file );
        }
    }
}

$autoload = new Autoload();