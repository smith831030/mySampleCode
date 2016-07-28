<?php
$xls_filename = 'analysis_'.date("Y_m_d_A_H_i_s");;
$xls_data =  urldecode(stripslashes($_POST['xls_data']));
header( "Content-type: application/vnd.ms-excel; charset=euc-kr" );
header( "Content-Disposition: attachment; filename={$xls_filename}.xls" );
header( "Content-Description: PHP5 Generated Data" );
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
echo "<h1>".$_POST['title']."</h1>";
echo "<h2>".$_POST['sdate']."~".$_POST['edate']."</h2>";
echo $xls_data; 
?>