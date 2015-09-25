<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller 
{


    public function __construct()
	{
		error_reporting(E_ALL & ~E_NOTICE);
		parent::__construct();
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
		$cnString = "odbc:DRIVER={iSeries Access ODBC Driver}; ".
					"SYSTEM=172.16.1.9; ".
					"DATABASE=MMFMSLIB; ".
					"UID=DCLACAP; ".
					"PWD=PASSWORD";		
		$this->dbh = new PDO($cnString,"","");
		$query = "select a.csdate,a.csstor,b.ivndpn,sum(a.csqty),sum(a.csexpr) as csexpr
					from MMFMSLIB.CSHDET a inner join MMFMSLIB.INVMST b on a.cssku=b.inumbr
					where a.cscen=1 and a.csdate between 150815 and 150830 group by a.csdate,a.csstor,b.ivndpn 
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


		$data['pagetitle'] = "Sales";
		$this->load->view('templates/sales', $data);
	}

	public function item()
	{
		# Load the view for item
		$data['pagetitle'] = "Item";
		$this->load->view('templates/item', $data);
	}

	public function store()
	{
		# Load the view for store
		$this->load->library("mstgen");
		$data['pagetitle'] = "Store";
		$this->load->view('templates/store', $data);
	}

}
