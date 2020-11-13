<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

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
		$this->load->view('view-index');
	}

	public function call_tren(){
		header('Content-Type: application/json');
		$this->load->model('model_api');
		$tren = $this->model_api->getDataTren(2018);

		$arrMonth = [
						array('bulan'=>'','jml'=>0),
						array('bulan'=>'Januari','jml'=>0),
						array('bulan'=>'Februari','jml'=>0),
						array('bulan'=>'Maret','jml'=>0),
						array('bulan'=>'April','jml'=>0),
						array('bulan'=>'Mei','jml'=>0),
						array('bulan'=>'Juni','jml'=>0),
						array('bulan'=>'Juli','jml'=>0),
						array('bulan'=>'Agustus','jml'=>0),
						array('bulan'=>'September','jml'=>0),
						array('bulan'=>'Oktober','jml'=>0),
						array('bulan'=>'November','jml'=>0),
						array('bulan'=>'Desember','jml'=>0)
					];

		$dataMonth = [];

		for ($i=1; $i < 13; $i++) { 
			foreach ($tren as $key => $value) {
				if($value->bulan==$i){
					$arrMonth[$i]['jml']=(int)$value->jml;
				}
			}
		}

		$bulan = [];
		$dataSheet = [];

		unset($arrMonth[0]);
		foreach ($arrMonth as $key => $value) {
			array_push($bulan, $value['bulan']);
			array_push($dataSheet, $value['jml']);
		}

		$dataGraph = array(
			'labelBulan'=>$bulan,
			'dataSheet'=>$dataSheet
		);

		
		echo json_encode($dataGraph);
	}

	public function call_budget(){
		header('Content-Type: application/json');
		$this->load->model('model_api');
		$budget = $this->model_api->getDataBudget();

		$dataGraph = array(
			'label'=>['CAPEX','OPEX'],
			'data'=>[$budget->ada,$budget->dihapus],
			'color'=>['rgba(67, 141, 232, 0.5)','rgba(191, 247, 5,0.8)']
		);

		echo json_encode($dataGraph);
	}

	public function call_top_seller(){
		header('Content-Type: application/json');
		$this->load->model('model_api');
		$budget = $this->model_api->getTopSeller();

		$label = [];
		foreach ($budget as $key => $value) {
			array_push($label,$value->nama);
		}

		$data = [];
		foreach ($budget as $key => $value) {
			array_push($data,$value->jumlah);
		}

		$dataGraph = array(
			'label'=>$label,
			'data'=>$data,
			'color'=>['rgba(67, 141, 232, 0.5)']
		);

		echo json_encode($dataGraph);
	}

	public function number_format_dots_rupiah($number=null) {
		$num = number_format($number,0,',','.');

		$v = explode('.',$num);

		// print_r(count($v));

		if(count($v)==1)
			return $num;
		elseif(count($v)==2)
			return $v[0]."RB";
		elseif(count($v)==3)
			return $v[0]."JT";
		elseif(count($v)==4)
			return $v[0]."M";
		elseif(count($v)==5)
			return $v[0]."T";
		
	    // return "Rp ".number_format($number,0,',','.');
	}

	public function call_count_all(){
		header('Content-Type: application/json');
		$this->load->model('model_api');
		
		$Bumn = $this->model_api->getCountBumn();
		$UMKM = $this->model_api->getCountUMKM();
		$UMKMProject = $this->model_api->getCountUMKMProject();
		$OrderTransaksi = $this->model_api->getCountOrderTransaksi();
		$NilaiTransaksi = $this->model_api->getCountNilaiTransaksi();

		$data = array(
			'success'=>true,
			'data'=>array(
				strtolower('Bumn')=>$Bumn,
				strtolower('UMKM')=>$UMKM,
				strtolower('UMKMProject')=>$UMKMProject,
				strtolower('OrderTransaksi')=>$OrderTransaksi,
				strtolower('NilaiTransaksi')=>$this->number_format_dots_rupiah($NilaiTransaksi)
			)
		);

		echo json_encode($data);
	}

	public function call_json(){
		header('Content-Type: application/json');
		echo file_get_contents('./assets/geojson/indonesia.json');
	}

	public function call_jumlah(){
		header('Content-Type: application/json');

		$this->load->model('model_api');

		$id = $this->input->post('id');
		$jml = $this->model_api->getCountUser($id);

		echo json_encode(array(
			'success'=>true,
			'data'=>array(
				'provinsi'=>$jml->prov,
				'jumlah'=>$jml->jumlah
			)
		));
	}

	// public function genkoor(){
	// 	header('Content-Type: application/json');
	// 	$js = file_get_contents('./assets/geojson/indonesia.json');
		
	// 	$arrIns = array();

	// 	$geo = json_decode($js);
	// 	foreach ($geo->features as $key => $value) {
	// 		$d = $geo->features[$key]->properties;
	// 		array_push($arrIns,array(
	// 			'id'=>$d->id_1,
	// 			'prov'=>$d->state,
	// 			'jumlah'=>rand(100,1000)
	// 		));
	// 	}

	// 	$this->db->insert_batch('jumlah_user',$arrIns);
	// }
	
}
