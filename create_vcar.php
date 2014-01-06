<?php 
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}
	$query = "CREATE TABLE vcar(vcar_no character varying(6),vcar_supplier character varying(50),vcar_fargo_part character varying(20), vcar_rev character varying(4),vcar_vendor_part character varying(20),vcar_order_date character varying(10),vcar_desc character varying(20),vcar_serial_no character varying(20),vcar_price character varying(10), vcar_buyer character varying(20), vcar_inspection_level character varying(10), vcar_po_no character varying(7),vcar_qty_received character varying(5), vcar_qty_rejected character varying(5), vcar_account_no character varying(10),vcar_date character varying(10), vcar_noncon character varying(400), vcar_root_cause character varying(400), vcar_eng character varying(1), vcar_s_car character varying(1), vcar_other character varying(1), vcar_none character varying(1), vcar_repair_qty character varying(5),vcar_rework_qty character varying(5), vcar_as_is_qty character varying(5), vcar_scrap_qty character varying(5), vcar_return_qty character varying(5), vcar_replace_qty character varying(5), vcar_actionee character varying(25), vcar_due_date character varying(10), vcar_corrective_action character varying(400), vcar_project character varying(25), vcar_project_date character varying(10), vcar_purchasing character varying(25), vcar_purchasing_date character varying(10), vcar_quality character varying(25), vcar_quality_date character varying(10), vcar_operation character varying(25), vcar_operation_date character varying(10), vcar_tech character varying(25), vcar_tech_date character varying(10), vcar_prod_control character varying(25), vcar_prod_date character varying(10), vcar_prepared_by character varying(25), vcar_prepared_date character varying(10))";
	$result = pg_query($conn, $query) or die("Error in query: $query . " . pg_last_error($conn));
	

	

