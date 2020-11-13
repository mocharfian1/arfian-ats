<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_api extends CI_Model {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
	}

	

	public function getDataTren($tahun=null){
		$query = $this->db->query("select month(insert_date) as bulan,count(*) as jml from item_pemesanan where year(insert_date)='".$tahun."' group by month(insert_date)");

		if($query->num_rows()>0){
			return $query->result();
		}else{
			return null;
		}
	}

	public function getDataBudget(){
		$query = $this->db->query("SELECT sum(case WHEN is_delete = 0 THEN 1 else 0 end) as ada, sum(case WHEN is_delete = 1 THEN 1 else 0 end) as dihapus FROM `pos_item`");

		if($query->num_rows()>0){
			return $query->row();
		}else{
			return null;
		}
	}

	public function getTopSeller(){
		$query = $this->db->query("SELECT pos_kategori.description as nama,count(*) as jumlah FROM `pos_item` join pos_kategori on pos_item.id_kategori=pos_kategori.id where pos_kategori.is_delete=0 and pos_item.is_delete=0 group by id_kategori");

		if($query->num_rows()>0){
			return $query->result();
		}else{
			return null;
		}
	}

	public function getCountBumn(){
		$query = $this->db->get('pos_kategori');

		if($query->num_rows()>0){
			return count($query->result());
		}else{
			return null;
		}
	}

	public function getCountUMKM(){
		$query = $this->db->get('item_pemesanan');

		if($query->num_rows()>0){
			return count($query->result());
		}else{
			return null;
		}
	}

	public function getCountUMKMProject(){
		$query = $this->db->get('pos_item');

		if($query->num_rows()>0){
			return count($query->result());
		}else{
			return null;
		}
	}

	public function getCountOrderTransaksi(){
		$query = $this->db->get('harga');

		if($query->num_rows()>0){
			return count($query->result());
		}else{
			return null;
		}
	}

	public function getCountNilaiTransaksi(){
		$query = $this->db->query("select sum(harga) as harga from harga");

		if($query->num_rows()>0){
			return $query->row()->harga;
		}else{
			return null;
		}
	}

	public function getCountUser($id=null){

		$query = $this->db->get_where('jumlah_user',array('id'=>$id));

		if($query->num_rows()>0){
			return $query->row();
		}else{
			return null;
		}
	}

	
}
