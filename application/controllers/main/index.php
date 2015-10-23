<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller 
{
    public function __construct()
	{
		error_reporting(E_ALL & ~E_NOTICE);
		parent::__construct();
		ini_set('memory_limit','30000M'); // mem
		ini_set('max_execution_time', 3000); // time
		$this->load->library('session');
		$this->load->library('ftp');

	}

	public function login()
	{
		$data['pagetitle'] = 'Login Page';
		$this->load->view('templates/login',$data);

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{

			$jda_username = $this->security->xss_clean($this->input->post('username'));
			$jda_password = $this->security->xss_clean($this->input->post('password'));

			$this->db->select('username, password');
			$this->db->where('username', $jda_username);
			$this->db->where('password', $jda_password);  
			$query = $this->db->get('nlsn_login');

			if($query->num_rows() == 1)
			{
				$this->session->set_userdata('fm_username',$jda_username);
				$this->session->set_userdata('fm_password',$jda_password);
				redirect('main/index');	
			}
			else
			{
				$this->session->set_flashdata("message", "Incorrect Username/Password");
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
		if (!$this->session->userdata('fm_username'))
		{
			redirect('main/index/login');
		}
		# Load the view for home
		$data['pagetitle'] = 'Home';
		$this->load->view('templates/home',$data);
	}

	public function sales()
	{
		if (!$this->session->userdata('fm_username'))
		{
			redirect('main/index/login');
		}
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

			try {
				$cnString = "odbc:DRIVER={iSeries Access ODBC Driver}; ".
					"SYSTEM=172.16.1.9; ".
					"DATABASE=MMFMSLIB; ".
					"UID=DCLACAP; ".
					"PWD=PASSWORD";		
			$this->dbh = new PDO($cnString,"","");
			$query = "select a.csdate,a.csstor,b.ivndpn,sum(a.csqty) as csqty,sum(a.csexpr) as csexpr
				  from MMFMSLIB.CSHDET a inner join MMFMSLIB.INVMST b on a.cssku=b.inumbr
				  where a.cscen=1 and a.csdate between {$datefrom} and {$dateto} and b.isdept <> 910 and b.ihzcod='CVS' group by a.csdate,a.csstor,b.ivndpn";

			$statement = $this->dbh->prepare($query);
			$statement->execute();	
			$result  = $statement->fetchAll();
		
			$output_dir="csv.docs\\";
			$todayz=date("mdY",strtotime('+8 hours'));
			$filename = "SALES_"."$todayz".".csv";
			$dataFile = fopen($output_dir.$filename,'w');
			fputs($dataFile,"\"STORE\",\"EAN\",\"QUANTITY\",\"RSP\",\"NetSales\",\"GrossSales\",\"TransactionPeriod\",\"WeekNumber\",\"Year\",\"MonthNumber\"\n");
		foreach ($result as $value) {

			$ddate =  $value['CSDATE'];
			$csstor = $value['CSSTOR'];
			$ean = $value['IVNDPN'];
			$qty = round($value['CSQTY']);
			if($qty != 0)
			{
				$rsp = round($value['CSEXPR']) / $qty;
			}else
			{
				$rsp = 0;
			}
			$sales = $value['CSEXPR'];
			$gross =  $value['CSEXPR'];


			$trans_date = $this->fdate_s($ddate);


			$date = mktime(0, 0, 0, $ddate); 
			$get_week = (int)date('YW', $date); 

			$date = new DateTime($ddate);
			$week = $date->format("W");
			$year = $date->format('Y');
			$week = "$year$week"; 

			$date = new DateTime($value['CSDATE']);
			$year = $date->format("Y");
			$year =  "$year";

			
			$timestamp = strtotime($this->fdate_format($ddate));
			$week = date('YW', $timestamp);
			$trans_month = substr($ddate, 2, 2);
			$trans_year = $this->fdate_year($ddate);

			fputs($dataFile,"\"$csstor\",\"$ean\",\"$qty\",\"$rsp\",\"$sales\",\"$gross\",\"$trans_date\",\"$week\",\"$trans_year\",\"$trans_month\"\n");
		}

			$this->dbh = null;
			$this->session->set_flashdata("message", 'CSV Export successfully');
			redirect('main/index/sales');
			} catch (Exception $e) {
				echo "Please Check Connection Settings.";
				exit();
			}	
		}
	}

	public function store()
	{
		if (!$this->session->userdata('fm_username'))
		{
			redirect('main/index/login');
		}
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
			
			try {
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

			$strnum  =  $value['STRNUM'];
			$strnam  = $value['STRNAM'];
			$stradd  = $value['STADD1'];
			$straddx = str_replace(",","",$stradd);
			$stcity = $value['STCITY'];
			$stsdat = $this->fdate($value['STSDAT']);
			$stcldt =  $this->fdate($value['STCLDT']);
			 $strspace="";

		fputs($dataFile,"\"$strnum\",\"$strnam\",\"$straddx\",\"$stcity\",\"$strspace\",\"$strspace\",\"$stsdat\",\"$stcldt\",\"$strspace\",\"$strspace\"\n");		}
			$this->dbh = null;
			$this->session->set_flashdata("message", 'CSV Export successfully');
			redirect('main/index/store');
			} catch (Exception $e) {
				echo "Please Check connection settings";
				exit();	
			}

		}
	}

	public function item()
	{
		if (!$this->session->userdata('fm_username'))
		{
			redirect('main/index/login');
		}
		# Load the view for item
		$data['pagetitle'] = "Item";
		$this->load->view('templates/item', $data);

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			try {
				
			$date = new DateTime($this->input->post('selectdate'));
			$format_date_from = $date->format("ymd");
			$frmt_date_from = "$format_date_from"; 
			$getdate =  $frmt_date_from;

			$cnString = "odbc:DRIVER={iSeries Access ODBC Driver}; ".
					"SYSTEM=172.16.1.9; ".
					"DATABASE=MMFMSLIB; ".
					"UID=DCLACAP; ".
					"PWD=PASSWORD";		
		$this->dbh = new PDO($cnString,"","");
		$query = "	select mst.ivndpn,mst.inumbr,mst.idescr,mst.isdept,sdept.dptnam,mst.iclas,clas.dptnam,mst.ihzcod,a.curreg,mst.islum,mst.asnum,p.asname,mst.imdate
            		from MMFMSLIB.SDIMST a inner join
            		MMFMSLIB.INVMST mst on a.inumbr=mst.inumbr left join
            		MMFMSLIB.INVDPT sdept on mst.idept=sdept.idept and mst.isdept=sdept.isdept and sdept.iclas+sdept.isclas=0 left join
            		MMFMSLIB.INVDPT clas on mst.idept=clas.idept and mst.isdept=clas.isdept and mst.iclas=clas.iclas and clas.isclas=0
            		inner join MMFMSLIB.APSUPP p on mst.asnum=p.asnum
					where mststr=1 and mstcur={$getdate} and mst.isdept<>910 and ihzcod='CVS'";
		
		$statement = $this->dbh->prepare($query);
		$statement->execute();	
		$result  = $statement->fetchAll();

		$output_dir="csv.docs\\";
		$todayz=date("mdY",strtotime('+8 hours'));
		$filename = "ITEM_"."$todayz".".csv";
		$dataFile = fopen($output_dir.$filename,'w');
		
		foreach ($result as $value) 
		{
				$upc 	  = $value['IVNDPN'];
				$sku   	  = $value['INUMBR'];
				$idesc 	  = $value['IDESCR'];
				$srp 	  = $value['CURREG'];
				$uom	  = $value['ISLUM'];
				$catcd	  = $value['ISDEPT'];
				$catds	  = $value['DPTNAM'];
				$scatcd   = $value['ICLAS'];
				$scatds   = $value['DPTNAM'];
				$ascd	  = $value['ASNUM'];
				$asnam    = $value['ASNAME'];
				$strdate  = $this->fdate($value['IMDATE']);
				$strspace = "";

			fputs($dataFile,"\"$upc\",\"$sku\",\"$idesc\",\"$srp\",\"$uom\",\"$strspace\",\"$strspace\",\"$catcd\",\"$catds\",\"$scatcd\",\"$scatds\",\"$ascd\",\"$asnam\",\"$strdate\",\"$strspace\"\n");
		
		}

		$this->dbh = null;
		$this->session->set_flashdata("message", 'CSV Export successfully');
		redirect('main/index/item');
			} catch (Exception $e) {
				echo "Please check connection settings.";
				exit();
			}
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
