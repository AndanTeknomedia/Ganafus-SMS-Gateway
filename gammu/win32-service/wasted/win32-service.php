<?php
include_once ('win32-service-constants.php');

class Win32Service
{
    private static $instance;
    
    public $service_name;
    public $display_name;
    public $description;
    private function __construct($svc_name, $disp_name, $desc)
    {
        $this->service_name = $svc_name;
        $this->display_name = $disp_name;
        $this->description  = $desc;
        $this->start_service();
    }
    
    private function install_service()
    {
        $svc = win32_query_service_status ($this->service_name);
        if ($svc == WIN32_ERROR_SERVICE_DOES_NOT_EXIST)
        {
            $createService = win32_create_service(array(
				'params'        => __FILE__." install",
				'service'       => $this->service_name,
				'display'       => $this->display_name,
                'description'   => $this->description,                
                'start_type'    => WIN32_SERVICE_AUTO_START
            ));
			$svc=win32_query_service_status ($this->service_name);
			return ($createService != WIN32_NO_ERROR) && ($svc != WIN32_ERROR_SERVICE_DOES_NOT_EXIST);
        }
        else
        {
            return true;
        }
    }
    
    private function delete_service()
    {
        
    }
}
?>