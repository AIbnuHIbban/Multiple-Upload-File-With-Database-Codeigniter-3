<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index(){
		$this->load->view('welcome_message');
	}

	public function prosesUpload(){
		// Panggil Model M_Welcome
		$this->load->model('M_Welcome');

		// Hitung Jumlah File/Gambar yang dipilih
		$jumlahData = count($_FILES['gambar']['name']);

		// Lakukan Perulangan dengan maksimal ulang Jumlah File yang dipilih
		for ($i=0; $i < $jumlahData ; $i++):

			// Inisialisasi Nama,Tipe,Dll.
			$_FILES['file']['name']     = $_FILES['gambar']['name'][$i];
			$_FILES['file']['type']     = $_FILES['gambar']['type'][$i];
			$_FILES['file']['tmp_name'] = $_FILES['gambar']['tmp_name'][$i];
			$_FILES['file']['size']     = $_FILES['gambar']['size'][$i];

			// Konfigurasi Upload
			$config['upload_path']          = './assets/upload/';
			$config['allowed_types']        = 'gif|jpg|png|pdf';

			// Memanggil Library Upload dan Setting Konfigurasi
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if($this->upload->do_upload('file')){ // Jika Berhasil Upload

				$fileData = $this->upload->data(); // Lakukan Upload Data

				// Membuat Variable untuk dimasukkan ke Database
				$uploadData[$i]['judul'] = $fileData['file_name']; 
			}

		endfor; // Penutup For

		if($uploadData !== null){ // Jika Berhasil Upload

			// Insert ke Database 
			$insert = $this->M_Welcome->upload($uploadData);
			
			if($insert){ // Jika Berhasil Insert
				echo "
					<a href='".base_url()."'> Kembali </a> 
					<br>
					Berhasil Upload ";
			}else{ // Jika Tidak Berhasil Insert
				echo "Gagal Upload";
			}

		}
	}
}
