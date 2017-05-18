<?php 

	/**
	* 
	*/
	class M_main extends CI_Model
	{

		public function getUser($email, $password){
			$this->db->where("USER_EMAIL", $email);
	        $this->db->where("USER_PASSWORD", md5($password));
	        $query = $this->db->get("tm_user");
	        if($query->num_rows() == 1){
	            return $query->result();
	        }else{
	            return false;
	        }
		}

		public function cekEmail($email){
			$this->db->where('USER_EMAIL',$email);
		    $query = $this->db->get('TM_USER');
		    if ($query->num_rows() > 0){
		        return true;
		    }else{
		        return false;
		    }
		}

		public function insertUser($data){
			$this->db->insert('tm_user', $data);
		}

		public function getAllUsers(){
	        return $this->db->query('select * from tm_user');       
	    }

	    public function getAllProject(){
	        return $this->db->query('select a.*, b.PRO_DOCFILE as file, c.USER_FULLNAME as name
									from tx_project a
									inner join tx_project_doc b on a.PRO_ID=b.PRO_ID inner join tm_user c on a.PRO_USER_ID=c.USER_ID
');       
	    }

	    public function getAllCat(){
	    	return $this->db->query('SELECT * FROM tm_category'); 
	    }

	    public function insertProject($data){
	    	$this->db->insert('tx_project', $data);
	    }

	    public function insertCompany($data){
	    	$this->db->insert('tm_company', $data);
	    }

	    public function getDetProject($id){
	    	$sql = $this->db->query("SELECT A.*,  b.COMP_NAME AS COMP,C.PRO_DOCFILE AS 'PIC', D.USER_FULLNAME AS OWNER, E.COUNTRY_NAME AS 'COUNTRY', F.REGIONAL_NAME AS 'REGIONAL', G.CATEGORY_NAME AS 'CAT', H.REFF_DESC AS STATUS
									FROM tx_project A 
									LEFT JOIN tm_company B ON A.PRO_USER_ID = B.COMP_USER_ID
									LEFT JOIN tx_project_doc C ON A.PRO_ID = C.PRO_ID
									LEFT JOIN tm_user D ON A.PRO_USER_ID = D.USER_ID
									LEFT JOIN tm_country E ON A.PRO_COUNTRY = E.COUNTRY_ID
									LEFT JOIN tm_regional F ON A.PRO_REGIONAL = F.REGIONAL_ID
									LEFT JOIN tm_category G ON A.PRO_CATEGORY = G.CATEGORY_CODE
									LEFT JOIN tm_reff H ON A.PRO_STATUS = H.REFF_CODE
									WHERE A.PRO_ID = '$id'");
	    	return $sql->result();
	    }

	    public function getCredit($user_id){
	    	$sql = $this->db->query("SELECT A.USER_ID, IFNULL(A.USER_FULLNAME,'-') AS FULLNAME, IFNULL(B.COMP_NAME, '-') AS COMPANY, B.COMP_ID, IFNULL(C.CRE_AMOUNT,'-') AS AMOUNT
									FROM tm_user A 
									INNER JOIN tm_company B ON A.USER_ID = B.COMP_USER_ID
									INNER JOIN tx_credit C ON B.COMP_ID = C.CRE_COMP_ID
									WHERE A.USER_ID = '$user_id'");
	    	return $sql->result();
	    }

	    public function get_data($act, $id){
	    	if ($act == 'getIDBid') {
	    		$SQL = $this->db->query("SELECT MAX(BID_ID) AS ID FROM tx_bid")->row()->ID;
	    		return $SQL;
	    	} else if($act == 'regional'){
	    		 return $this->db->query('select * from tm_regional');
	    	} else if($act == 'country'){
	    		 return $this->db->query('select * from tm_country');
	    	} else if($act == 'project'){
	    		return $this->db->query('SELECT A.*, B.PRO_DOCFILE AS PICTURE
										FROM tx_project A
										INNER JOIN tx_project_doc B ON A.PRO_ID = B.PRO_ID
										WHERE PRO_USER_ID ="'.$id.'"'); 
	    	}else if($act == 'company'){
	    		return $this->db->query('SELECT * 
										FROM tm_company
										WHERE COMP_USER_ID ="'.$id.'"'); 
	    	}
	    }

	    public function execute($type, $act, $data, $id){
	    	if ($type == "save") {
	    		if ($act == 'insertBid') {
	    			$this->db->insert('tx_bid', $data);
	    		}
	    	}else if ($type == "edit") {
	    		if ($act == 'updateCredit') {
	    			$this->db->where('CRE_COMP_ID', $id);
					$this->db->update('tx_credit', array('CRE_AMOUNT' => $data));
	    		}elseif ($act == 'upStatus') {
	    			$this->db->where('PRO_ID', $id);
					$this->db->update('tx_project', array('PRO_STATUS' => $data));
	    			//print_r("sini".$data."=".$id);
	    		}
	    	}
	    }

	    public function getCode($email){
            $SQL = $this->db->query("SELECT MD5(USER_REGISTERDATE) AS CODE FROM TM_USER WHERE USER_EMAIL = '".$email."'")->row()->ID;
	    	return $SQL->result();
        }
            
        function sendmail($to, $nama, $subject, $isi){
			$config = array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				'smtp_user' => 'noreply.adaproyek@gmail.com',
				'smtp_pass' => '@d4pr0y3k',
				'mailtype'  => 'html', 
				'charset'   => 'iso-8859-1'
			);
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from('noreply.adaproyek@gmail.com', 'Administrator Adaproyek');
			$email = str_replace(';', ',', $to);
			$this->email->to($email);
			$bcc = str_replace(';', ',', 'ali.maruf17@gmail.com');
			$this->email->bcc($bcc);
			$this->email->subject($subject);
			$body = '<html><body style="background: #ffffff; color: #000000; font-family: arial; font-size: 13px; margin: 20px; color: #363636;"><table style="margin-bottom: 2px"><tr style="font-size: 13px; color: #0b1d90; font-weight: 700; font-family: arial;"><td width="41" style="margin: 0 0 6px 10px;"></td><td style="font-family: arial; vertical-align: middle; color: #153f6f;">'.$subject.'<br/><span style="color: #858585; font-size: 10px; text-decoration: none;">adaproyek.com</span></td></tr></table><div style="background-color: #dee8f4; margin-top: 4px; margin-bottom: 10px; padding: 5px; font-family: Verdana; font-size: 11px; width:600px; text-align:justify;">'.$isi.'</div><div style="border-top: 1px solid #dcdcdc; clear: both; font-size: 11px; margin-top: 10px; padding-top: 5px;"><div style="font-family: arial; font-size: 10px; color: #a7aaab;">Team AdaProyek</div><a style="text-decoration: none; font-family: arial; font-size: 10px; font-weight: normal;" href="adaproyek.com">Website AdaProyek </a></div></body></html>';
			$this->email->message($body);
			
			return $this->email->send();
		}
	}
?>