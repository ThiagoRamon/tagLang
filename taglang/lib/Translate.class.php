<?php
	
class Translate{

	const   EXTENSION     = ".property";
	const   FILE_NAME_DEFAULT     = "message";
	const   ALL_PERMITION = 0777;
	const   READ_MODE     = "r";
	const   WRITE_MODE    = "w";
	const   PATH_DEFAULT  =  "app/locale/translate/";
	const   LC_MESSAGE    = "LC_MESSAGE"; 
	const   LANG_DEFAULT  = "pt_BR";
	private $documentroot; 
    private $requestURI;  
	private $filename;
	private $language;
	private $translationPath;
	private $currentPath;
	private $currentFile;

	function __construct($criatedMode = false, $translationPath =NULL, $language = NULL , $filename=NULL){

	    $this->documentroot    = $_SERVER["DOCUMENT_ROOT"];
		$this->requestURI      = $_SERVER["REQUEST_URI"];
		$this->language        = ($language!=NULL ? $language : self::LANG_DEFAULT);
		$this->translationPath = ($translationPath!=null ? $translationPath : self::PATH_DEFAULT).self::LC_MESSAGE.'/'.$this->language;
		$this->filename        = ($filename!=NULL ? $filename : self::FILE_NAME_DEFAULT);
		$this->currentPath     = $this->documentroot.$this->requestURI.$this->translationPath;
		$this->currentFile     = $this->currentPath.'/'.$this->filename.self::EXTENSION;
		
		if($criatedMode){
			$this->__init();
		}

	}

	private function __init(){
		$this->create_default($this->translationPath);
	}	




	public function create_f_property($translationPath, $language, $filename="message"){
		$this->path = $translationPath.'/'.self::LC_MESSAGE.'/'.$language;
		$this->$language = $language;
		$this->filename = $filename;
		$this->create_default($this->path);
	}
	private function create_default($uri){
		
		$path = $this->create_folder($uri);
		$this->create_file_property($this->currentFile);	
	}

	private function create_folder($translationPath = NULL){

		$folder = @split("/", $translationPath);
        $path;
		foreach ($folder as $key => $value) {
			
			if($key>0){
				$path = $path."/". $value;
			}else{
				$path  = $value ;
			}
			if(!file_exists($path))
				mkdir($path, intval(7777));
		}

			return $path;
	}

	private function create_file_property($file = NULL){
		if(!file_exists($file))
			fopen($file, self::WRITE_MODE);
	}



public function _parse_client_language() {

        $http_accept = getenv('HTTP_ACCEPT_LANGUAGE');

        if (isset($http_accept) && strlen($http_accept) > 1) {
            # Split 
            $x = explode(",", $http_accept);
            foreach ($x as $val) {
                if (preg_match("/(.*);q=([0-1]{0,1}.d{0,4})/i", $val, $matches))
                    $lang[$matches[1]] = (float) $matches[2];
                else
                    $lang[$val] = 1.0;
            }

            #default language (highest q-value)
            $qval = 0.0;
            foreach ($lang as $key => $value) {
                if ($value > $qval) {
                    $qval = (float) $value;
                    return $key;
                }
            }
        }
        return self::$tag_default;
    }


    public function __gettext($idmessage){

		$counter_file = $this->currentPath.'/'.$this->filename.self::EXTENSION;
		clearstatcache();
		ignore_user_abort(true);   
		if(file_exists($counter_file)){
			$fh = fopen($counter_file, self::READ_MODE); 
			while (($data = fgetcsv($fh, 1000, ';')) !== false) { 
			    foreach ($data as $value) { 
			    	//$string  = @split("=", $value);
			    	$id = substr($value, 0, strpos($value, "="));
					
			    	//echo "<pre>";print_r($string); echo "</pre>";
				    	if($id== $idmessage){
				    		fclose($fh);
				    		return $value =substr($value,(strpos($value, "=")+1),strlen($value));
				    	}
					}
			    } 
			} 
			return $idmessage;
	}
	public function __get($key){
		if(!isset($key)){
			return "";
		}
		return $this->$key;
	}

	public function __set($key, $value){
		$this->$key = $value;
	}

}

?>