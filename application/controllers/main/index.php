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
			$csstor = $value['CSSTOR'];
			$ean = $value['IVNDPN'];
			$qty = $value['QTY'];
			$rsp = $value['CSEXPR'];
			$sales = $value['CSEXPR'];
			$gross =  $value['CSEXPR'];


			$trans_date = $this->fdate_s($ddate);


			// $trans_date = new DateTime($ddate);
			// $month = $trans_date->format("m");
			// $dates = $trans_date->format("d");
			// $week = $trans_date->format("W");
			// $year = $trans_date->format('Y');
			// $transactiondate =  "$month/$dates/$year"; 
			

			
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
			$trans_month = $this->fdate_month($ddate);
			$trans_year = $this->fdate_year($ddate);


			fputs($dataFile,"\"$csstor\",\"$ean\",\"$qty\",\"$rsp\",\"$sales\",\"$gross\",\"$trans_date\",\"$week\",\"$trans_year\",\"$trans_month\"\n");
		}
			$this->session->set_flashdata("message", 'CSV Export successfully');
			redirect('main/index/sales');

		}
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

	public function item()
	{
		# Load the view for item
		$data['pagetitle'] = "Item";
		$this->load->view('templates/item', $data);

		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$cnString = "odbc:DRIVER={iSeries Access ODBC Driver}; ".
					"SYSTEM=172.16.1.9; ".
					"DATABASE=MMFMSLIB; ".
					"UID=DCLACAP; ".
					"PWD=PASSWORD";		
		$this->dbh = new PDO($cnString,"","");
		$query = "select mst.ivndpn,mst.inumbr,mst.idescr,mst.isdept,sdept.dptnam,mst.iclas,clas.dptnam,mst.ihzcod,a.curreg,mst.islum,mst.asnum,p.asname,mst.imdate
            from MMFMSLIB.SDIMST a inner join
            MMFMSLIB.INVMST mst on a.inumbr=mst.inumbr left join
            MMFMSLIB.INVDPT sdept on mst.idept=sdept.idept and mst.isdept=sdept.isdept and sdept.iclas+sdept.isclas=0 left join
            MMFMSLIB.INVDPT clas on mst.idept=clas.idept and mst.isdept=clas.isdept and mst.iclas=clas.iclas and clas.isclas=0
            inner join MMFMSLIB.APSUPP p on mst.asnum=p.asnum
			where mststr=1 and mstcur=150921 and mst.isdept<>910 and ihzcod='CVS'";
		$statement = $this->dbh->prepare($query);
		$statement->execute();	
		$result  = $statement->fetchAll();

		$output_dir="csv.docs\\";
		$todayz=date("mdY",strtotime('+8 hours'));
		$filename = "ITEM_"."$todayz".".csv";
		$dataFile = fopen($output_dir.$filename,'w');
		
		foreach ($result as $value) 
		{
					$upc 	 = $value['IVNDPN'];
					$sku   	 = $value['INUMBR'];
					$idesc 	 = $value['IDESCR'];
					$srp 	 = $value['CURREG'];
					$uom	 = $value['ISLUM'];
					$catcd	 = $value['ISDEPT'];
					$catds	 = $value['DPTNAM'];
					$scatcd  = $value['ICLAS'];
					$scatds  = $value['DPTNAM'];
					$ascd	 = $value['ASNUM'];
					$asnam   = $value['ASNAME'];
					$strdate = $this->fdate($value['IMDATE']);
					$strspace= "";

		fputs($dataFile,"\"$upc\",\"$sku\",\"$idesc\",\"$srp\",\"$uom\",\"$strspace\",\"$strspace\",\"$catcd\",\"$catds\",\"$scatcd\",\"$scatds\",\"$ascd\",\"$asnam\",\"$strdate\",\"$strspace\"\n");
		}


		 // $ftp_server="ftp.172.16.1.84";
		 // $ftp_user_name="root";
		 // $ftp_user_pass="Extern@l";
		 // $file_to_upload = fopen($output_dir.$filename,'w');
		 // $remote_file = "/var/www/hosts/debsasr_backup_82415/public_html/";

		 // // set up basic connection
		 // $conn_id = ftp_connect($ftp_server);

		 // // login with username and password
		 // $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

		 // // upload a file

		 // echo $ftp_user_name.$ftp_user_pass;
		 // exit();
		 // if (ftp_put($conn_id, $remote_file, $file_to_upload, FTP_ASCII)) {
		 //    echo "successfully uploaded $file\n";
		 //    exit;
		 // } else {
		 //    echo "There was a problem while uploading $file\n";
		 //    exit;
		 //    }
		 // // close the connection
		 // ftp_close($conn_id);

		$this->session->set_flashdata("message", 'CSV Export successfully');
		redirect('main/index/item');


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

	public function fdate_month($date1)
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
		$ret_str="$mo";
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
