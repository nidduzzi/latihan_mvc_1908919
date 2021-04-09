<?php 

/******************************************
PRAKTIKUM RPL
******************************************/

class Task extends DB{
	
	// Mengambil data
	function getTask(){
		// Query mysql select data ke tb_to_do
		$query = "SELECT * FROM tb_to_do";
        // check if session is active
        if(session_status() != PHP_SESSION_ACTIVE) session_start();
        // order by selected button
        if(isset($_SESSION['sort']) && !empty($_SESSION['sort'])){
            if($_SESSION['sort'] != "default"){
                $query.= " ORDER BY ".$_SESSION['sort'] . " ASC";
            }
        }

        
		// Mengeksekusi query
		return $this->execute($query);
	}
	

}



?>
