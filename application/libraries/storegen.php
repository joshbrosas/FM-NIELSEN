<?php
$AS400=odbc_connect("MMJVSLIB", "DCLACAP", "PASSWORD");

ignore_user_abort(true);
set_time_limit(0);

@$datex= $_GET['mydate'];

$today=date("Ymd",strtotime('+8 hours'));
$todayz=date("mdY",strtotime('+8 hours'));
//$today='100226';


function fdate($date1){
$len=strlen($date1);
if($len < 4 or $len == 0 or $date1 == "")
	return 0;
$day=substr($date1,$len-2,2);
$mo=substr($date1,$len-4,2);
if($len==5)
	$yr="0" . substr($date1,0,1);
elseif($len==4)
	$yr="00";
else
	$yr=substr($date1,0,2);
	if($yr >= 80){
	$yr=$yr+1900;
	}else{
	$yr=$yr+2000;
	}
$ret_str="$mo$day$yr";
return $ret_str;
}



$nwdate=fdate($today);


$len=strlen($datex);
if($len < 4 or $len == 0 or $datex == "")
	return 0;
$yy=substr($datex,$len-4,4) + 2000;
$mo=substr($datex,$len-6,2);
$day=substr($datex,$len-8,2);

$datetrn="$mo$day$yy";

$datetrn=fdate($datex);
$datetrn=str_replace("-","",$datetrn);

$output_dir="csv.docs\\";
// open a datafile
$filename = "STORE_"."$todayz".".csv";
$dataFile = fopen($output_dir.$filename,'w');
$datetrnx=str_replace("-","",$datex);

$sqlStr="select strnum,strnam,stadd1,stcity,stsdat,stcldt from MMFMSLIB.TBLSTR where strnum < 999";

	 	    $detailx= odbc_exec($AS400,$sqlStr);
	 		while (odbc_fetch_row($detailx)) {

	    $strno=odbc_result($detailx,1);
	    $strname=odbc_result($detailx,2);
	    $stradd=odbc_result($detailx,3);
	    $straddx=str_replace(",","",$stradd);
	    $straddcity=odbc_result($detailx,4);
	    $strdate=fdate(odbc_result($detailx,5));
	    $strclose=fdate(odbc_result($detailx,6));
	    $strspace="";



//insert all Entries - PFM PO

     fputs($dataFile,"\"$strno\",\"$strname\",\"$straddx\",\"$straddcity\",\"$strspace\",\"$strspace\",\"$strdate\",\"$strclose\",\"$strspace\",\"$strspace\"\n");
}

 echo "\t<table>\n";
  echo "\t<tr class=\"normal\" align=\"center\">\n";
  echo "\t<td>DONE</td>\n";



?>