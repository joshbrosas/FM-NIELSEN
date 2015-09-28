<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller 
{


    public function __construct()
	{
		error_reporting(E_ALL & ~E_NOTICE);
		parent::__construct();
		$this->load->library('session');
	}


	public function login()
	{
		$data['pagetitle'] = 'Login Page';
		$this->load->view('templates/login',$data);


		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$jda_username = strtoupper($this->input->post('username'));
			$jda_password = $this->input->post('password');
			
			if($jda_username == 'DOLF' && $jda_password == 'admin123') //override account for local testing
			{
				$this->session->set_userdata('jda_username',$jda_username);
				$this->session->set_userdata('jda_password',$jda_password);
				redirect('main/index');				
			}
			else
			{
				$process_error = "Login Failed!";
				$data['process_error'] = $process_error;
				redirect('main/index/login');	
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('main/index/login');
	}
	public function index()
	{
		if (!$this->session->userdata('jda_username'))
		{
			redirect('main/index/login');
		}

		# Load the view for home
		$data['pagetitle'] = 'Home';
		$this->load->view('templates/home',$data);
	}

	public function sales()
	{
		# Load the view for sales
		$data['pagetitle'] = "Sales";
		$this->load->view('templates/sales', $data);

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$datefrom = str_replace("/", "",  $this->input->post('datefrom'));
			$dateto = str_replace("/", "",  $this->input->post('dateto'));
			
			$date = new DateTime($this->input->post('datefrom'));
			$format_date_from = $date->format("ymd");
			$frmt_date_from = "$format_date_from"; 
			$datefrom =  $frmt_date_from;

			$date = new DateTime($this->input->post('dateto'));
			$format_date_to = $date->format("ymd");
			$frmt_date_to= "$format_date_to"; 
			$dateto =  $frmt_date_to;
			
		$cnString = "odbc:DRIVER={iSeries Access ODBC Driver}; ".
					"SYSTEM=172.16.1.9; ".
					"DATABASE=MMFMSLIB; ".
					"UID=DCLACAP; ".
					"PWD=PASSWORD";		
		$this->dbh = new PDO($cnString,"","");
		$query = "select a.csdate,a.csstor,b.ivndpn,sum(a.csqty),sum(a.csexpr) as csexpr
					from MMFMSLIB.CSHDET a inner join MMFMSLIB.INVMST b on a.cssku=b.inumbr
					where a.cscen=1 and a.csdate between {$datefrom} and {$dateto} group by a.csdate,a.csstor,b.ivndpn 
					";
		$statement = $this->dbh->prepare($query);
		$statement->execute();	
		$result  = $statement->fetchAll();

			$output_dir="csv.docs\\";
			$todayz=date("mdY",strtotime('+8 hours'));
			$filename = "SALES_"."$todayz".".csv";
			$dataFile = fopen($output_dir.$filename,'w');

		foreach ($result as $value) {

			$ddate =  $value['CSDATE'];
			$ean = $value['IVNDPN'];
			$qty = $value['QTY'];
			$rsp = $value['CSEXPR'];
			$sales = $value['CSEXPR'];
			$gross =  $value['CSEXPR'];

			$date = new DateTime($value['CSDATE']);
			$month = $date->format("m");
			$dates = $date->format("d");
			$week = $date->format("W");
			$year = $date->format('Y');
			$transactiondate =  "$month/$dates/$year"; 
			
			$date = new DateTime($value['CSDATE']);
			$week = $date->format("W");
			$year = $date->format('Y');
			$week = "$year$week"; 

			$date = new DateTime($value['CSDATE']);
			$year = $date->format("Y");
			$year =  "$year";
			
			$date = new DateTime($value['CSDATE']);
			$month = $date->format("n");
			$month =  "$month";

			fputs($dataFile,"\"$ddate\",\"$ean\",\"$qty\",\"$rsp\",\"$sales\",\"$gross\",\"$transactiondate\",\"$week\",\"$year\",\"$month\"\n");
		}
			$this->session->set_flashdata("message", 'CSV Export successfully');
			redirect('main/index/sales');

		}
	}

	public function item()
	{
		# Load the view for item
		$data['pagetitle'] = "Item";
		$this->load->view('templates/item', $data);
	}

	public function fdate($date1){
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

	public function store()
	{
		# Load the view for store
		#$this->load->library("mstgen");
		$data['pagetitle'] = "Store";
		$this->load->view('templates/store', $data);

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$datefrom = str_replace("/", "",  $this->input->post('datefrom'));
			$dateto = str_replace("/", "",  $this->input->post('dateto'));
			
			$date = new DateTime($this->input->post('datefrom'));
			$format_date_from = $date->format("ymd");
			$frmt_date_from = "$format_date_from"; 
			$datefrom =  $frmt_date_from;

			$date = new DateTime($this->input->post('dateto'));
			$format_date_to = $date->format("ymd");
			$frmt_date_to= "$format_date_to"; 
			$dateto =  $frmt_date_to;
			
		$cnString = "odbc:DRIVER={iSeries Access ODBC Driver}; ".
					"SYSTEM=172.16.1.9; ".
					"DATABASE=MMFMSLIB; ".
					"UID=DCLACAP; ".
					"PWD=PASSWORD";		
		$this->dbh = new PDO($cnString,"","");
		$query = "select strnum,strnam,stadd1,stcity,stsdat,stcldt from MMFMSLIB.TBLSTR where strnum < 999";
		$statement = $this->dbh->prepare($query);
		$statement->execute();	
		$result  = $statement->fetchAll();


			$output_dir="csv.docs\\";
			$todayz=date("mdY",strtotime('+8 hours'));
			$filename = "STORE_"."$todayz".".csv";
			$dataFile = fopen($output_dir.$filename,'w');

		foreach ($result as $value) {

			$strnum =  $value['STRNUM'];
			$strnam = $value['STRNAM'];
			$stradd = $value['STADD1'];
			$straddx=str_replace(",","",$stradd);
			$stcity = $value['STCITY'];
			$stsdat = $this->fdate($value['STSDAT']);
			$stcldt =  $this->fdate($value['STCLDT']);
			 $strspace="";

     fputs($dataFile,"\"$strnum\",\"$strnam\",\"$straddx\",\"$stcity\",\"$stsdat\",\"$stcldt\",\"$strdate\",\"$strclose\",\"$strspace\",\"$strspace\"\n");
		}
			$this->session->set_flashdata("message", 'CSV Export successfully');
			redirect('main/index/store');

		}
	}

	

}
