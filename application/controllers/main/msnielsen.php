<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msnielsen extends CI_Controller 
{


	function __construct()
	{
		error_reporting(E_ALL & ~E_NOTICE);
		parent::__construct();
		ini_set('memory_limit','30000M'); // mem
		ini_set('max_execution_time', 3000); // time
		set_time_limit(0);
		$this->load->library('session');	
	}

	public function index()
	{
		if (!$this->session->userdata('fm_username'))
		{
			redirect('main/index/login');
		}
		# Load the view for home
		$data['pagetitle'] = 'Sales FC';
		$this->load->view('templates/salesfc',$data);

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$date = new DateTime($this->input->post('datefrom'));
			$format_date_from = $date->format("Y-m-d H:i:s");
			$frmt_date_from = "$format_date_from"; 
			$datefrom =  $frmt_date_from;

			$date = new DateTime($this->input->post('dateto'));
			$format_date_to = $date->format("Y-m-d H:i:s");
			$frmt_date_to= "$format_date_to"; 
			$dateto =  $frmt_date_to;

		$AS400 = odbc_connect ('ansilive', 'pfmadmin', 'M@nager3971' ) or die ( 'Can not connect to server' );

		
		$sql_str= "select
		a.repdate,a.branch,e.PL_PLUCODE,sum(b.qty),sum(b.amount)
		from [HOVQPBOS].[dbo].[HistMain] a 
		inner join [HOVQPBOS].[dbo].[histsub] b on a.transact=b.transact and a.branch=b.branch 
		inner join [HOVQPBOS].[dbo].[mproduct] c on b.code = c.pd_prodid
		inner join [HOVQPBOS].[dbo].[mprodplu] e on e.PL_PRODID=c.pd_prodid
		where b.type='P' and a.repdate between '$datefrom' and '$dateto' and c.pd_cat3='CVS'
		group by a.repdate,a.branch,e.PL_PLUCODE,c.pd_vendor
		";
		
		$output_dir="csv.docs\\";
		$todayz=date("mdY",strtotime('+8 hours'));
		$filename = "SALESFC_"."$todayz".".csv";
		$dataFile = fopen($output_dir.$filename,'w');
		fputs($dataFile,"\"STORE\",\"EAN\",\"QUANTITY\",\"RSP\",\"NetSales\",\"GrossSales\",\"TransactionPeriod\",\"WeekNumber\",\"Year\",\"MonthNumber\"\n");
		$detailx = odbc_exec($AS400,$sql_str);
		while (odbc_fetch_row($detailx)) {

	    $ddate=odbc_result($detailx,1);
	    $csstor=odbc_result($detailx,2);
	    $ean=odbc_result($detailx,3);
	    $qty=odbc_result($detailx, 4);
	    $round_qty = round($qty);
			if($qty != 0)
			{
				$rsp = round(odbc_result($detailx, 5)) / $qty;
			}else
			{
				$rsp = 0;
			}
	    $sales = odbc_result($detailx, 5);
		$gross = odbc_result($detailx, 5);


		$date = new DateTime($ddate);
		$format_date = $date->format("ymd");
		$format_dated = "$format_date";

		$trans_date = $this->fdate_s($format_dated);


		$date = mktime(0, 0, 0, $format_dated); 
		$get_week = (int)date('YW', $format_dated); 

		$date = new DateTime($format_dated);
		$week = $date->format("W");
		$year = $date->format('Y');
		$week = "$year$week"; 

		$date = new DateTime($format_dated);
		$year = $date->format("Y");
		$year =  "$year";

			
		$timestamp = strtotime($format_dated);
		$week = date('YW', $timestamp);
		$trans_month = substr($format_dated, 2, 2);
		$trans_year = $this->fdate_year($format_dated);

     	fputs($dataFile,"\"$csstor\",\"$ean\",\"$qty\",\"$rsp\",\"$sales\",\"$gross\",\"$trans_date\",\"$week\",\"$trans_year\",\"$trans_month\"\n");
		}
		$this->session->set_flashdata("message", 'CSV Export successfully');
		redirect('main/msnielsen/index');
		}
		
	}

	public function fdate($date1)
	{
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

	public function fdate_format($date1)
	{
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
		$ret_str="$yr-$mo-$day";
		return $ret_str;
	}

	public function fdate_s($date1)
	{
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
		$ret_str="$mo/$day/$yr";
		return $ret_str;
	}

	public function fdate_year($date1)
	{
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
		$ret_str="$yr";
		return $ret_str;
	}

}