<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasi extends MX_Controller
{
    public $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->model('kasi_model');
        $this->load->helper('form', 'url', 'language');
        $this->load->helper('directory');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
       
    }

    public function index()
    {

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('Home/login', 'refresh');
        } elseif ($this->ion_auth->is_kasi()) // remove this elseif if you want to enable this for non-admins
        {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');


            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user) {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
            $id = $this->ion_auth->user()->row()->id_pns;
           
            $this->template->load('temp_kasi', 'v_home', $this->data);
        } else {
            redirect('Home', 'refresh');
        }
    }
     
	public function getpns($idopd)
	{
		
		$data = $this->kasi_model->getpnsnip($idopd);
		echo json_encode($data);
	}

    public function entri()
   
    { 
        $id = $this->ion_auth->user()->row()->id_pns;
		$id_opd = $this->kasi_model->get_id_opd($id);
		$data['tahun'] = $this->kasi_model->gettahun();
		$data['matangr'] = $this->kasi_model->getmatangr();
		$data['mpgrm'] = $this->kasi_model->getmpgrm();
		$data['pns'] = $this->kasi_model->get_pns_by_opd($id_opd['unitkey']);
		$data['kasi'] = $this->kasi_model->getpnskasi($id_opd['unitkey']);
		$data['eselon'] = $this->kasi_model->getpnsbyeselon($id_opd['unitkey']);
		$data['namaunit'] = $this->kasi_model->get_namaunit($id_opd['unitkey']);
		$data['mkeg'] = $this->kasi_model->getkegiatan($id_opd['unitkey']);
		//$data['namakeg'] = $this->kasi_model->getnamakeg($id_opd['kdkegunit']);
		$datakodekegunit = $this->kasi_model->getkegiatan($id_opd['unitkey']);
		$datakodekegunitnilai = $this->kasi_model->getnilai($id_opd['unitkey']);
		for($i=0;$i<count($datakodekegunit);$i++)
		{
			$kodekegunit[$i] = $datakodekegunit[$i]['kdkegunit'];
			$kodekegunit1[$i] = $datakodekegunitnilai[$i]['nilai'];
		}

		$jumlahkodekegunit = count($kodekegunit);
		for($i=0;$i<$jumlahkodekegunit;$i++)
		{
			$datakegunit[$i] = $this->kasi_model->getnamakeg($kodekegunit[$i]);
		}
		$data['datakegunit']  = $datakegunit;
		$data['datakegunit1']  = $kodekegunit1;
		
		
		//echo json_encode ($this->kasi_model->get_namaunit($id_opd['unitkey']));
		$this->template->load('temp_kasi', 'entri',$data);
    }


	public function list1()
    {
       $id = $this->ion_auth->user()->row()->id_pns;
	   $id_opd = $this->kasi_model->get_id_opd($id);
	   $data['namaunit'] = $this->kasi_model->get_namaunit($id_opd['unitkey']);
	   $data['jabatan_user'] = $this->kasi_model->getjabatanuser($id);
	   $data['tahun'] = $this->kasi_model->gettahun();
	   $data['idpptk'] = $this->kasi_model->getkegiatanpptk($id_opd['unitkey']);
       $datakodekegunit = $this->kasi_model->getkegiatanpptk($id);
	   if($datakodekegunit!=NULL){
		 for($i=0;$i<count($datakodekegunit);$i++)
		{
			$kodekegunit[$i] = $datakodekegunit[$i]['kdkegunit'];
			$kodekegunit1[$i] = $datakodekegunit[$i]['nilai'];
		}
		
		$jumlahkodekegunit = count($kodekegunit);
		for($i=0;$i<$jumlahkodekegunit;$i++)
		{
			$datakegunit[$i] = $this->kasi_model->getnamakeg($kodekegunit[$i]);
		}
		$data['datakegunit']  = $datakegunit;
		$data['datakegunit1']  = $kodekegunit1;
		$this->template->load('temp_kasi', 'v_list', $data);
	    }else{
				$data['datakegunit']  = NULL;
				$data['datakegunit1']  = NULL;
	    $this->template->load('temp_kasi', 'v_list', $data);
			 }
	  
    }
	
	  public function list2()
    {

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('Home/login', 'refresh');
        } elseif ($this->ion_auth->is_kasi()) // remove this elseif if you want to enable this for non-admins
        {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');


            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user) {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
            $id = $this->ion_auth->user()->row()->id_pns;
            $this->data['jumlah_ts'] = $this->kasi_model->countts($id);
            $this->template->load('temp_kasi', 'v_list_laporan', $this->data);
        } else {
            redirect('Home', 'refresh');
        }
    }

	   public function json_view_kasi()
    {
        header('Content-Type: application/json');
        $id = $this->ion_auth->user()->row()->id_pns;
        echo $this->kasi_model->json($id);
    }

	
	
	

	public function simpan()
    {        
		$jumlahpnspptk = count($this->input->post('pilihpnspptk[]'));
		for ($i=0; $i<$jumlahpnspptk;$i++)
			{
				//$datasimpan[$i] = array(
				//'idpns' => $this->input->post('pilihpns'),
				//'unitkey' => $this->input->post('namaunit'),	
				//'kdkegunit' => $this->input->post('kegiatan['.$i.']'), 
				//'tahun' => $this->input->post('tahun'),	
				//'idpnspptk' => $this->input->post('pilihpnspptk['.$i.']'),
				//'idpnsppk' => $this->input->post('pilihpnsppk['.$i.']'),			
				//);
				//$this->kasi_model->insert_pptk($datasimpan[$i]); 
				
		if($this->input->post('pilihpnspptk['.$i.']')!=NULL)
			{
				$datasimpan[$i] = array(
				'idpns' => $this->input->post('pilihpns'),
				'unitkey' => $this->input->post('namaunit'),	
				'kdkegunit' => $this->input->post('kegiatan['.$i.']'), 
				'tahun' => $this->input->post('tahun'),	
				'nilai' => $this->input->post('nilai['.$i.']'),	
				'idpnspptk' => $this->input->post('pilihpnspptk['.$i.']'),
				'idpnsppk' => $this->input->post('pilihpnsppk['.$i.']'),			
				);
		$this->kasi_model->insert_pptk($datasimpan[$i]); 
			}
			}
		
				//	echo json_encode ($this->input->post('kegiatan[]'));
				//	echo json_encode ($this->input->post('pilihpnspptk[]'));

		{
		redirect(site_url('Kasi/entri'));
		}  
			
	}
	
	public function php_info()
    {
        echo phpinfo();
    }
 
	public function logout()
    {
        $this->data['title'] = "Logout";
        // log the user out
        $logout = $this->ion_auth->logout();
        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('Kasi', 'refresh');
    }
	
	
}
