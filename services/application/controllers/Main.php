<?php 
	/**
	* 
	*/
	class Main extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->library('form_validation');
			$this->load->model('m_main');
		}

		public function viewProject(){
		    $user = $this->m_main->getAllProject();
		    $result = $user->result_array();
		    $this->output
		       ->set_content_type('application/json')
		       ->set_output(json_encode($result));
		         
		    return;
		}

		public function loginUser(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$data = json_decode($this->input->post('data'));
			$email = $data->email;
			$password = $data->password;
			$loginUser = $this->m_main->getUser($email, $password);
			$LOG = $loginUser[0]->USER_EMAIL;
			//print_r($LOG);die();
			if($LOG === $email){

                $this->db->query("UPDATE TM_USER SET USER_LASTLOGIN = NOW() WHERE USER_ID = '".$loginUser[0]->USER_ID."'");
				echo json_encode(array("result" => $loginUser, "value" => 1));
			} /*else {
				echo json_encode(array("result" => "failed", "value" => 11));
			}*/
		}

		public function registerUser(){
			$_POST = json_decode(file_get_contents('php://input'), true);
	    	$data = json_decode($this->input->post('data'));
	    	$email = $data->email;
	    	$cek = $this->m_main->cekEmail($email);
	    	if(!$cek){
		    	$data = array(
		    		'USER_FULLNAME' => $data->nama_lengkap,
		    		'USER_PHONE' => $data->phone,
		    		'USER_EMAIL' => $data->email,
		    		'USER_PASSWORD' => md5($data->password),
		    		'USER_SEX' => $data->jk,
		    		'USER_BIRTHDATE' => $data->tgl_lahir,
		    		'USER_REGISTERDATE' => date('Y-m-d H:i:s'),
		    		'USER_STATUS' => 'US01'
		    		);
		    	$this->m_main->insertUser($data);
		    	$link = "http://localhost:8080/development/adaprojek/#/activate?param=".$data['USER_EMAIL']."|".md5($data['USER_REGISTERDATE']);
                        
                $isi = "Selamat anda berhasil terdaftar di adaproyek. Silakan lakukan aktivasi akun dengan klik link dibawah atau copy link tersebut dan paste di web browser anda. <br><br>".$link."<br><br>Terima Kasih.";
                $this->m_main->sendmail($data['USER_EMAIL'], $data['USER_FULLNAME'], 'Welcome to AdaProyek', $isi);
	    		echo json_encode(array("result" => "Register Success", "value" => "1"));
			} else {
				echo json_encode(array("result" => "Email Sudah ada!", "value" => "0"));
			}
		}

		public function optCat(){
		    $user = $this->m_main->getAllCat();//
		    $result = $user->result_array();
		    $this->output
		       ->set_content_type('application/json')
		       ->set_output(json_encode($result));
		         
		    return;
		}

		public function list_project(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$id = json_decode($this->input->post('user_id'));
			$data = $this->m_main->get_data('project', $id);
		    $result = $data->result_array();
		    $this->output
		       ->set_content_type('application/json')
		       ->set_output(json_encode($result));
		         
		    return;
		}

		public function list_company(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$id = json_decode($this->input->post('user_id'));
			$data = $this->m_main->get_data('company', $id);
		    $result = $data->result_array();
		    $this->output
		       ->set_content_type('application/json')
		       ->set_output(json_encode($result));
		         
		    return;
		}

		public function activate($param){
            $arrparam = explode('|', $param);
            $user = $this->m_main->getCode($param);
            if($user[0]->CODE == $arrparam[1]){
                $this->db->where('USER_EMAIL', $arrparam[0]);
                $this->db->update('tm_user', array('USER_STATUS' => 'US02'));
                echo json_encode(array('result' => 'Berhasil'));
                return "Sukses";
            }
        }

		public function dataCountry(){
		    $user = $this->m_main->get_data('country','');
		    $result = $user->result_array();
		    $this->output
		       ->set_content_type('application/json')
		       ->set_output(json_encode($result));
		         
		    return;
		}

		public function dataRegional(){
		    $user = $this->m_main->get_data('regional','');
		    $result = $user->result_array();
		    $this->output
		       ->set_content_type('application/json')
		       ->set_output(json_encode($result));
		         
		    return;
		}

		public function do_upload($user_id, $file, $tmp_name){
			echo $user_id.$file.$tmp_name;
			die();
        	$config['upload_path'] = 'assets/images/uploads';
	        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
	        $config['max_size'] = '5000';
	        $this->load->library('upload', $config);	
	        if ( ! $this->upload->do_upload('file')) {
	            echo $this->upload->display_errors();
	        } else {
		   	  //here $file_data receives an array that has all the info
			  //pertaining to the upload, including 'file_name'
	            $file_data = $this->upload->data();
	            $IDFile = $this->db->query("SELECT MAX(PRO_ID) AS ID FROM tx_project_doc")->row()->ID;

		    	if ($ID == NULL) {
		    		$IDFile = 1;
		    	} else {
		    		$IDFile = $ID+1;
		    	}

				$dataFile = array(
					'PRO_DOC_ID' =>$IDFile,
					'PRO_ID' =>$user_id,
					'PRO_DOCFILE' =>$file,
					'PRO_UPLOAD_DATE' =>date('Y-m-d H:i:s'),
				);
				$this->db->insert('tx_project_doc', $dataFile);
	            /*$data = array(
                    'agenda_id' => $this->input->post('agenda_id'),
                    'file'      => $file_data['file_name']
	            );*/
	            /*$this->load->model('agendas_model');
	            $this->m_main->add_attachment($data);*/
	            print_r($file_data); 
	        }
        }

		public function createProject(){
			
			/*$_POST = json_decode(file_get_contents('php://input'), true);
			$data = json_decode($this->input->post('data'));*/
			//print_r($this->upload->do_upload($_FILES['file']));
	    	$ID = $this->db->query("SELECT MAX(PRO_ID) AS ID FROM tx_project")->row()->ID;

	    	if ($ID == NULL) {
	    		$IDDATA = 1;
	    	} else {
	    		$IDDATA = $ID+1;
	    	}

	    	$dataArray = array(
	    		'PRO_ID' => $IDDATA,
	    		'PRO_USER_ID' => $this->input->post('user_id'),
	    		'PRO_TITLE' => $this->input->post('title'),
	    		'PRO_CATEGORY' => $this->input->post('category'),
	    		'PRO_DESC' => $this->input->post('description'),
	    		'PRO_LOCATION' => $this->input->post('location'),
	    		'PRO_REGIONAL' =>  $this->input->post('regional'),
	    		'PRO_COUNTRY' =>  $this->input->post('country'),
	    		'PRO_BUDGET' => $this->input->post('budget'),
	    		'PRO_CREATE_DATE' =>  date('Y-m-d H:i:s'),
	    		'PRO_STATUS' => "PS03"
	    		);

	    	#$this->m_main->insertProject($dataArray);
			$user_id = $this->input->post('user_id');
			$file = $_FILES['file']['name'];
			$tmp_name = $_FILES['file']['tmp_name'];
			//$this->do_upload($user_id, $file, $tmp_name);
			print_r($dataArray);

			if($_FILES['file']['name']!=""){
				//echo "simi";
				$folder_path = date("Ymd");
				$uploads_dir = 'assets/images/foto';
				/*
				if (!is_dir($uploads_dir)){
					mkdir($uploads_dir);
				}*/
				$uploads_dir .= "/";
				$orig_name = $_FILES['file']['name'];
				$change_name = date("His").".".pathinfo($orig_name, PATHINFO_EXTENSION);
				$path = 'assets/images/foto'."/".$change_name;//'.$folder_path."/".$change_name;
				ini_set('upload_max_filesize', '5M');
				$allowed = array('gif','png','jpg','JPG');
				$config['remove_spaces'] = TRUE;
				$config['allowed_types'] = 'exe|sql|psd|pdf|xls|ppt|php|php4|php3|js|swf|Xhtml|zip|wav|bmp|gif|jpg|jpeg|png|html|htm|txt|rtf|mpeg|mpg|avi|doc|docx|xlsx';
				$config['upload_path'] = $uploads_dir;
				$config['file_name'] = $change_name;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if(!$this->upload->do_upload("file")){
					echo "error tuh";
					echo $this->upload->display_errors();
				}else{
					echo "sini";
					$data = $this->upload->data();
					print_r($data);
					/*$myfile = fopen($uploads_dir . "/index.html", "w");
					$text = "<?php if(!defined('BASEPATH'))exit('Directory access is forbidden.'); ?>";
					fwrite($myfile, $text);
					fclose($myfile);*/
				}
					//$DATA['PATH'] = $path;
			}
			
			$IDDocFile = $this->db->query("SELECT MAX(PRO_DOC_ID) AS ID FROM tx_project_doc")->row()->ID;

	    	if ($IDDocFile == NULL) {
	    		$IDFile = 1;
	    	} else {
	    		$IDFile = $IDDocFile+1;
	    	}

			$dataFile = array(
				'PRO_DOC_ID' =>$IDFile,
				'PRO_ID' =>$this->input->post('user_id'),
				'PRO_DOCFILE' =>$path,
				'PRO_UPLOAD_DATE' =>date('Y-m-d H:i:s'),
			);
			#$this->db->insert('tx_project_doc', $dataFile);
			print_r($dataFile);
			echo json_encode(array("result" => "Berhasil", "value" => 1));
		}

		public function createCompany(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$dataPost = json_decode($this->input->post('data'));
	    	$ID = $this->db->query("SELECT MAX(COMP_ID) AS ID FROM tm_company")->row()->ID;
	    	$dataArray = array(
	    		'COMP_ID' => $ID+1,
	    		'COMP_USER_ID' => $dataPost->user_id,
	    		'COMP_IDNUMBER' => $dataPost->npwp,
	    		'COMP_NAME' => $dataPost->company,
	    		'COMP_ADDRESS' => $dataPost->address,
	    		'COMP_REGIONAL' =>  $dataPost->regional,
	    		'COMP_COUNTRY' =>  $dataPost->country,
	    		'COMP_TELP' => $dataPost->telephone,
	    		'COMP_FAX' => $dataPost->fax,
	    		'COMP_HEADERPIC' => NULL,
	    		'COMP_STATUS' => 'PS01'
	    		);
			$this->m_main->insertCompany($dataArray);

			echo json_encode(array("result" => "Berhasil", "value" => "1"));
		}

		public function detailProject(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$idPost = json_decode($this->input->post('id'));
			$result = $this->m_main->getDetProject($idPost);
			//print_r($result);//
			//echo json_encode(array("result" =>$result,"value"=>"Berhasil"));	
			echo json_encode(array('result' => $result,"value" =>"Berhasil"));	         
		    //return;
		}

		public function dataUser(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$user_id = json_decode($this->input->post('user_id'));
			$result = $this->m_main->getCredit($user_id);
			$rows = count($result);
			echo json_encode(array("result" =>$result,"value"=>"Berhasil","data" => $rows));		         
		    return;
		}

		function getInBid(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$dataGet = json_decode($this->input->post('data'));
			
			//print_r($dataArray);//die();
			echo json_encode(array('result' => $dataGet,"value" =>"Berhasil"));
		}

		public function inBid($act){
			if ($act = 'save') {
				$_POST = json_decode(file_get_contents('php://input'), true);
				$data = json_decode($this->input->post('data'));
				#print_r($data);//die();
				$max_id = $this->m_main->get_data('getIDBid', '');
		    	if ($max_id == NULL) {
		    		$IDDATA = 1;
		    	} else {
		    		$IDDATA = $max_id+1;
		    	}
		    	#print_r($IDDATA);
				$dataArray = array(
					'BID_ID' => $IDDATA,
					'BID_PRO_ID' => $data->pro_id,
					'BID_COMP_ID' => $data->comp_id,
					'BID_USER_ID' => $data->user_id,
					'BID_OVERPRICE' => $data->over_price,
					'BID_OVERDESC' => $data->over_desc,
					'BID_CREATE_DATE' => date('Y-m-d H:i:s'),
					'BID_STATUS' => 'PS02'
				);
				#print_r($dataArray);
				$this->m_main->execute('save','insertBid', $dataArray,'');
				$saldo = ($data->amount) - ($data->budgetProject);
				#print_r($saldo);
				$this->m_main->execute('edit','updateCredit', $saldo, $data->comp_id);
				echo json_encode(array('result' => $data, "value" => "Berhasil"));
			}
		}

		public function upStatus(){
			$_POST = json_decode(file_get_contents('php://input'), true);
			$dataID = json_decode($this->input->post('id'));
			$this->m_main->execute('edit','upStatus','PS02', $dataID);
			//print_r($result);	
			echo json_encode(array('result' => "Berhasil","value" =>"Update Status"));
		}
	}
?>