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
$filename = "ITEM_"."$todayz".".csv";
$dataFile = fopen($output_dir.$filename,'w');
$datetrnx=str_replace("-","",$datex);

$sqlStr="select mst.ivndpn,mst.inumbr,mst.idescr,mst.isdept,sdept.dptnam,mst.iclas,clas.dptnam,mst.ihzcod,a.curreg,mst.islum,mst.asnum,p.asname,mst.imdate
            from MMFMSLIB.SDIMST a inner join
            MMFMSLIB.INVMST mst on a.inumbr=mst.inumbr left join
            MMFMSLIB.INVDPT sdept on mst.idept=sdept.idept and mst.isdept=sdept.isdept and sdept.iclas+sdept.isclas=0 left join
            MMFMSLIB.INVDPT clas on mst.idept=clas.idept and mst.isdept=clas.isdept and mst.iclas=clas.iclas and clas.isclas=0
            inner join MMFMSLIB.APSUPP p on mst.asnum=p.asnum
			where mststr=1 and mstcur=150921 and mst.isdept<>910 and ihzcod='CVS'";

	 	    $detailx= odbc_exec($AS400,$sqlStr);
	 		while (odbc_fetch_row($detailx)) {

					$upc=odbc_result($detailx,1);
					$sku=odbc_result($detailx,2);
					$idesc=odbc_result($detailx,3);
					$srp=odbc_result($detailx,9);
					$uom=odbc_result($detailx,10);
					$catcd=odbc_result($detailx,4);
					$catds=odbc_result($detailx,5);
					$scatcd=odbc_result($detailx,6);
					$scatds=odbc_result($detailx,7);
					$ascd=odbc_result($detailx,11);
					$asnam=odbc_result($detailx,12);
					$strdate=fdate(odbc_result($detailx,13));
					$strspace="";

     fputs($dataFile,"\"$upc\",\"$sku\",\"$idesc\",\"$srp\",\"$uom\",\"$strspace\",\"$strspace\",\"$catcd\",\"$catds\",\"$scatcd\",\"$scatds\",\"$ascd\",\"$asnam\",\"$strdate\",\"$strspace\"\n");
}

  echo "\t<table>\n";
  echo "\t<tr class=\"normal\" align=\"center\">\n";
  echo "\t<td>DONE</td>\n";

?>