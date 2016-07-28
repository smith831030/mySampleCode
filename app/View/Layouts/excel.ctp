<?php 
$xls_filename = 'invoice_list_'.date("Y_m_d_A_H_i_s");
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename={$xls_filename}.xls" );
header( "Content-Description: PHP5 Generated Data" );
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";

echo $this->fetch('content'); 
?>