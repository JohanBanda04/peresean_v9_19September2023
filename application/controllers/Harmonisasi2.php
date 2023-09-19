<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harmonisasi2 extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('security');
//        $this->load->helper('file');

    }
    public function index()
    {
        redirect("berita/v");
    }

    public function hasil2($zona='',$aksi='' , $status='', $pemda='' )
    {

        $ceks 	 = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level 	 = $this->session->userdata('level');
//        $zona_pendek = explode("_",$zona);
//        if(count($zona_pendek)==3){
//            $zona_pendek_fix = $zona_pendek[1]."_".$zona_pendek[2];
//
//        } else if(count($zona_pendek)==4){
//            $zona_pendek_fix = $zona_pendek[1]."_".$zona_pendek[2]."_".$zona_pendek[3];
//
//        }

        if($aksi!='t'){

            $this->session->set_flashdata('msg','');

        }

        if(!isset($ceks)) {
            redirect('web/login');
        }

        /*tambahan dari sini*/
        $tbl_zona = $this->db->get_where('tbl_zona',array('id_zona'=>$_SESSION['id_zona']));

        $data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
        $data['users']  	 = $this->Mcrud->get_users();
        $data['nama_panjang_admin']  	 = $tbl_zona->row()->nama_panjang;
        $data['zona_pemda']  	 = $tbl_zona->row()->nama_zona;

        /*sampai sini */

        $data['user']  			  = $this->Mcrud->get_users_by_un($ceks);

        $this->db->order_by('id_berita', 'DESC');
        //baris berikut menangkap seluruh data yang akan tampil di tabel tiap daerah pengajuan draft harmonisasi
        //$data['query'] = $this->db->get_where("tbl_draft",array("zona_dokumen"=>$zona));
        $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>$zona));
        $cek_nama_panjang_zona = $this->db->get_where("tbl_zona",array("nama_zona"=>$zona));



//        echo "<pre>"; print_r($cek_nama_panjang_zona->result());die;

        $data['judul_web'] 	  = "DOKUMEN HASIL HARMONISASI ". strtoupper($cek_nama_panjang_zona->result()[0]->nama_panjang);
        $data['cek_nama_panjang_zona'] 	  = $cek_nama_panjang_zona;


        if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='sedang_diproses' or $status=='menunggu_koreksi' or $status=='selesai') {
            $this->db->where('status',$status);
            $this->db->where('zona_dokumen',$zona);
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
        } else if($status=='semua'){
            redirect("harmonisasi2/hasil2/".$zona);
        }


        $cek_notif = $this->db->get_where("tbl_notif", array('penerima'=>"$id_user"));
        foreach ($cek_notif->result() as $key => $value) {
            $b_notif = $value->baca_notif;
            if(!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif'=>"$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima'=>$id_user));
            }
        }


        if ($aksi == 't') {
//			    echo $zona; die;
//				if ($level!='pelaksana') {
//					redirect('404');
//				}

            //echo "tambah data"; die;

            $p = "tambah_hasil";
            $data['judul_web'] 	  = "TAMBAH DOKUMEN HARMONISASI ".strtoupper($cek_nama_panjang_zona->result()[0]->nama_panjang) ;
        }  elseif ($aksi == 'd') {
            $p = "detail";
            $data['judul_web'] 	  = "RINCIAN BAHAN BERITA";
            $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => "$id"))->row();
            if ($data['query']->id_berita=='') {redirect('404');}

            $data['cek_notif'] = $this->db->get_where("tbl_notif", array('penerima'=>"$id_user", 'id_berita'=>"$id"))->row();

            $b_notif = $data['cek_notif']->baca_notif;
            if(!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif'=>"$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima'=>$id_user, 'id_berita'=>"$id"));
            }
        } else if ($aksi == 'e') {
            if($pemda=='pemprov_ntb'){
//                    echo "edit dokumen pemprov ntb";
//                    echo "<br>id:".$id; die;
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMPROV NTB";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkot_mataram'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKOT MATARAM";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkot_bima'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKOT BIMA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_sumbawa_barat'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB SUMBAWA BARAT";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_sumbawa'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB SUMBAWA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_lombok_utara'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK UTARA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_lombok_timur'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK TIMUR";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_lombok_tengah'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK TENGAH";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_lombok_barat'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK BARAT";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_dompu'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB DOMPU";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_bima'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB BIMA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }

        } else if($aksi=='se'){
            if(!isset($ceks)){
                redirect('web/login');
            }

            $id_draft_permohonan_edit = htmlentities(strip_tags($this->input->post('id_draft_permohonan_edit')));
            $judul_draft_permohonan_edit = htmlentities(strip_tags($this->input->post('judul_draft_permohonan_edit')));
            $jenis_dokumen_edit = htmlentities(strip_tags($this->input->post('jenis_dokumen_edit')));

            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan_edit));

            $surat_permohonan_old = $cek_data->row()->lamp_surat_permohonan;
            $data_lama_url_data_dukung = $cek_data->row()->url_data_dukung;


            $max_size = 1024*5;
            $lokasi = 'file/bahan_draft_raperda';
            $this->upload->initialize(array(
                "upload_path" => "./$lokasi",
                "allowed_types" => "*",
                "max_size" => $max_size
            ));

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

            //MENANGKAP data dari inputan file 'lamp_surat_permohonan_edit'

            if(!$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $surat_permohonan_old;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($surat_permohonan_old);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }
//                echo $lamp_surat_permohonan; die;

            //berikutnya MENANGKAP lampiran post 'url_files_edit'
//            echo $_FILES['url_files_edit']; die;
            if ($_FILES['url_files_edit']['name'][0] == null) {
                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files_edit']['name'][0]);
            }

//                echo $count_edit; die;


            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files_edit']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files_edit']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files_edit']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files_edit']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files_edit']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files_edit']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else if($this->upload->do_upload('file')) {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }

//                echo $url_data_dukung; die;

            if ($simpan == 'y') {
//                    echo "tes simpanz"; die;
                $data = array(
                    'nama_draft_permohonan' => $judul_draft_permohonan_edit,
                    'jenis_dokumen' => $jenis_dokumen_edit,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'url_data_dukung' => $url_data_dukung
                );


                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id_draft_permohonan_edit));
                $this->session->set_flashdata('msg',
                    '
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong>Sukses!</strong> Berhasil disimpan.
					</div>
				<br>'
                );
            } else {
                $this->session->set_flashdata('msg',
                    '
					<div class="alert alert-warning alert-dismissible" role="alert">
						 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							 <span aria-hidden="true">&times;</span>
						 </button>
						 <strong>Gagal!</strong> ' . $pesan . '.
					</div>
				 <br>'
                );
            }

            redirect('pemda/v/'.$id);

        } else if($aksi=='ce'){
            //echo "edit draft pemda"; die;
            $id_draft_permohonan = hashids_decrypt($status);
//            echo $id_draft_permohonan; die;
            $p = "edit_draft";
            $dt_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft'=> $id_draft_permohonan))->row();
            $status = $dt_tbl_berita->status;

            $data['edit_draft'] = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan));
            $data['id_draft_permohonan'] = $id_draft_permohonan;
            $data['status'] = $status;
            $data['dt_tbl_berita'] = $dt_tbl_berita;


            $data['pemda'] = $pemda;

            $zona_doc = explode('_',$pemda);
            $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            $data['judul_web'] = "EDIT DRAFT PERMOHONAN ". strtoupper($zona_document) ;
            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan));


        } else if($aksi=='ce_kasub_perancang'){
            //echo "edts kasub perancang"; die;
            //parameter ketiga dipakai sebagai variabel tempat kirim id_berita
            $id_berita = hashids_decrypt($status);
            //echo $id_berita;die;
            $p = "edit_harmonisasi_kasub_perancang";

            $dt_tbl_berita = $this->db->get_where("tbl_berita", array('id_berita'=> $id_berita))->row();
            $status = $dt_tbl_berita->status;
            $cek_nama_panjang_zona = $this->db->get_where("tbl_zona",array("nama_zona"=>$dt_tbl_berita->zona_dokumen));

            $data['edit_hasil_harmonisasi'] = $this->db->get_where("tbl_berita", array('id_berita' => $id_berita));
            $data['id_berita'] = $id_berita;
            $data['status'] = $status;
            $data['dt_tbl_berita'] = $dt_tbl_berita;


            $data['judul_web'] = "EDIT HASIL HARMONISASI ". strtoupper($cek_nama_panjang_zona->row()->nama_panjang) ;
            $cek_data = $this->db->get_where("tbl_berita", array('id_berita' => $id_berita));


        } else if($aksi=='ce_perancang'){
            $id_draft_permohonan = hashids_decrypt($status);
            $p = "edit_proses_perancang";
//            echo $id_draft_permohonan; die;

            $data['edit_draft'] = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan));
            $data['id_draft_permohonan'] = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan));
            $data['tbl_berita_by_draft_id'] = $this->db->get_where("tbl_berita", array('id_draft' => $id_draft_permohonan));

            $data['pemda'] = $pemda;


            $zona_doc = explode('_',$pemda);

            if($zona_doc[3]!=""){
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2]."_".$zona_doc[3];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2]." ".$zona_doc[3];
            } else if($zona_doc[3]=="") {
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            }

//                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2];
//                $zona_document =  $zona_doc[1]." ".$zona_doc[2];


            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>$zona_document));
            /*tabel ny di select belakangan*/
//                $p = "pemprov_ntb";
//                $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMPROV NTB";
//                $data['edit_berita'] = $this->db->get_where("tbl_berita", array('zona_dokumen' => $zona_document_strip));

            $data['judul_web'] = "PROSES DRAFT PERMOHONAN ". strtoupper($zona_document) ;
            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan));


        } else if ($aksi == 'h') {
            //echo $zona; die;
            //echo "hapus data broy"; die;
            if (!isset($ceks)) {
                redirect('web/login');
            }


            $id_berita = $this->input->post('id_berita');
            //$cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_berita));
            $cek_data_tbl_berita = $this->db->get_where("tbl_berita", array('id_berita' => $id_berita));

            $lamp_surat_undangan_old = $cek_data_tbl_berita->row()->lamp_surat_undangan;
//            echo $lamp_surat_undangan_old; die;

            //echo $cek_data_tbl_berita->num_rows(); die;
            //echo $cek_data_tbl_berita->row()->lamp_surat_undangan; die;

            if($cek_data_tbl_berita->num_rows() > 0){
                if($lamp_surat_undangan_old != ""){
                    /*ketika ADA file lampiran hasil harmonisasi yang disimpan*/
                    try{
                        unlink($lamp_surat_undangan_old);
                    } catch (Exception $exception){
                        echo json_encode($exception);
                    }

                    $this->db->delete('tbl_berita', array('id_berita' => $id_berita));


                    $this->session->set_flashdata('msg',
                        '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses Gan!</strong> Berhasil dihapus.
							</div>
							<br>'
                    );

                    redirect("harmonisasi2/hasil2/".$zona);
                } else {

                    /*ketika TIDAK ADA file lampiran hasil harmonisasi yang disimpan*/
                    $this->db->delete('tbl_berita', array('id_berita' => $id_berita));


                    $this->session->set_flashdata('msg',
                        '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses Gan!</strong> Berhasil dihapus.
							</div>
							<br>'
                    );

                    redirect("harmonisasi2/hasil2/".$zona);
                }
            } else {

                redirect('404_content');
            }


        } else if($aksi == 'df'){
            $id_draft_permohonan_update = $this->input->post('id');
            $cek_data = $this->db->get_where('tbl_draft',array('id_draft_permohonan'=>$id_draft_permohonan_update));
            if (!isset($ceks)) {
                redirect('web/login');
            }

            try {
                $path = $this->input->post('path');

                if (unlink($path)) {
                    $file = json_decode($cek_data->row()->url_data_dukung);
//                    var_dump($file); die;
//                    unset($file[$this->input->post('file_id')]);
                    unset($file[$this->input->post('file_id')]);

                    $data = array(
//                            'nama' => $cek_data['nama'],
//                            'tempat' => $cek_data['tempat'],
//                            'tanggal' => $cek_data['tanggal'],
//                            'peserta' => $cek_data['peserta'],
//                            'why' => $cek_data['why'],
//                            'deskripsi' => $cek_data['deskripsi'],
//                            'url_data_dukung' => json_encode(count($file)>0?$file:null),

                        'nama_draft_permohonan' => $cek_data->row()->nama_draft_permohonan,
                        'tgl_input' => $cek_data->row()->tgl_input,
                        'status' => $cek_data->row()->status,
                        'lamp_surat_permohonan' => $cek_data->row()->lamp_surat_permohonan,
                        'url_data_dukung' => json_encode(count($file)>0?$file:null),
                        'id_user' => $this->session->userdata('id_user'),
                        'jenis_dokumen' => $cek_data->row()->jenis_dokumen,
                        'zona_dokumen' => $cek_data->row()->zona_dokumen,
                        'tgl_update' => date('Y-m-d H:i:s')
                    );

//                        $this->Guzzle_model->updateAgenda($id_draft_permohonan_update, $data);
                    $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id_draft_permohonan_update));
                    $this->session->set_flashdata('msg',
                        '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                    );
                } else {
                    $this->session->set_flashdata('msg',
                        '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '." upload data!".'.
	 							</div>
	 						 <br>'
                    );
                }
//                    echo "success : " . json_encode($file);
                redirect("pemda/v/ce/".$id_draft_permohonan_update."/draft_pemprov_ntb");

            } catch (Exception $e) {
                echo json_encode($e);
            }

        }else{
            $p = "index";
//            $data['judul_web'] 	  = "Bahan Berita";
        }

        $this->load->view('users/header', $data);
        $this->load->view("users/hasil_harmonisasi/$p", $data);
        $this->load->view('users/footer');

        date_default_timezone_set('Asia/Singapore');
        $tgl = date('Y-m-d H:i:s');


        $max_size = 1024*5;
        $lokasi = 'file/bahan_draft_raperda';
        $this->upload->initialize(array(
            "upload_path" => "./$lokasi",
            "allowed_types" => "*",
            "max_size" => $max_size
        ));

        if (isset($_POST['btnsimpan_update'])) {
//            echo $zona;die;
//            echo $id_draft_permohonan;die;
            //echo "btnsimpan_update pemda"; die;
            //output dari yg diatas 'pemprov_ntb'
//            echo $id;die;
            $zona_doc = explode('_',$zona);
//            echo $zona_doc[0];die;
//            echo $zona_doc[0];die;
//            echo $zona_doc[1];die;
//            echo $zona_doc[2];die;

            if($zona_doc[2]!=""){
                $zona_document_strip =  $zona_doc[0]."_".$zona_doc[1]."_".$zona_doc[2];
//                $zona_document =  $zona_doc[1]." ".$zona_doc[2]." ".$zona_doc[3];
                $zona_document =  $zona_doc[0]." ".$zona_doc[2]." ".$zona_doc[3];
            } else if($zona_doc[2]=="") {
                $zona_document_strip =  $zona_doc[0]."_".$zona_doc[1];
//                $zona_document =  $zona_doc[1]." ".$zona_doc[2];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            }

//            echo $zona_document_strip; die;

            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

            //DARI SINI
//                    echo $_FILES['url_files']['name'];die;
            if ($_FILES['url_files']['name'][0] == null) {

                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files']['name']);
            }

            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft'))) ;
            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen'))) ;
            $simpan = '';


            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan))->row();
            $data_lama_url_data_dukung = $cek_data->url_data_dukung;
            $data_lama_lamp_surat_permohonan = $cek_data->lamp_surat_permohonan;
            $data_lama_draft_harmonisasi = $cek_data->draft_harmonisasi;
            $data_lama_naskah_akademik = $cek_data->naskah_akademik_dll;
            $data_id_user = $cek_data->id_user;
            $status = $cek_data->status;
//            echo $status;die;
//            echo $data_id_user;die;


            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else  {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
//                echo $url_file;die;
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }




            if( !$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $data_lama_lamp_surat_permohonan;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_surat_permohonan);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            /*untuk update data draft_harmonisasi_edit dari sini*/
            if( !$this->upload->do_upload('draft_harmonisasi_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_draft_harmonisasi = $data_lama_draft_harmonisasi;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_draft_harmonisasi = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_draft_harmonisasi);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }
            /*sampai sini*/

            /*untuk update data naskah_akademik_edit dari sini*/
            if( !$this->upload->do_upload('naskah_akademik_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_naskah_akademik_harmonisasi = $data_lama_naskah_akademik;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_naskah_akademik_harmonisasi = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_naskah_akademik);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }
            /*sampai sini*/



            $cek_data_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft'=> $id_draft_permohonan));
            //echo $cek_data_tbl_berita->lamp_surat_undangan;die;
            $lamp_surat_harmonisasi = $cek_data_tbl_berita->row()->lamp_surat_undangan;

            if($simpan=='y'){
                $data = array(
                    'nama_draft_permohonan' => $nama_draft,
                    'jenis_dokumen' => $jenis_dokumen,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'draft_harmonisasi' => $lamp_draft_harmonisasi,
                    'url_data_dukung' => $url_data_dukung,
                    'naskah_akademik_dll' => $lamp_naskah_akademik_harmonisasi
                );

                //cukx

                if($cek_data_tbl_berita->num_rows()<=0){
                    $data_tbl_berita = array(
                        'id_user' => $data_id_user,
                        'nama_kegiatan' => $nama_draft,
                        'tgl_kegiatan' => date('Y-m-d H:i:s'),
                        'tgl_update' => date('Y-m-d H:i:s'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'jenis_dokumen' => $jenis_dokumen,
                        'zona_dokumen' => $zona_document,
                        'id_draft' => $id_draft_permohonan,
                    );
                    $this->db->insert('tbl_berita',$data_tbl_berita);
                } else {
                    $data_tbl_berita = array(
                        'tgl_update' => date('Y-m-d H:i:s'),
                        'nama_kegiatan' => $nama_draft,
                        'status' => $status,
                        'lamp_surat_undangan' => $lamp_surat_harmonisasi,
                    );
                    $this->db->update('tbl_berita',$data_tbl_berita, array('id_draft'=>$id_draft_permohonan));
                }

//                echo $id; die;
                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id_draft_permohonan));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                );
            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );
//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("pemda/draft/".$zona);
        }

        if (isset($_POST['btnsimpan_update_perancang'])) {
            //LANJUTKAN DISINI UTK MELAKUKAN UPDATE PADA FILE CONTROLLER PEMDA DAN VIEW EDIT DARI PEMDA,KASUB,PERANCANG
            //PADA PROJECT DI SERVER PUSDATIN
//            echo $id;die;
//            echo $id_draft_permohonan;die;
            echo "btnsimpan_update_perancang"; die;
            //$cek_nama_judul = htmlentities(strip_tags($this->input->post('nama_draft')));
            //echo $cek_nama_judul; die;
            $zona_doc = explode('_',$zona);
            if($zona_doc[2]!=""){
                $zona_document_strip =  $zona_doc[0]."_".$zona_doc[1]."_".$zona_doc[2];
                $zona_document =  $zona_doc[0]." ".$zona_doc[1]." ".$zona_doc[2];
            } else if($zona_doc[2]=="") {
                $zona_document_strip =  $zona_doc[0]."_".$zona_doc[1];
                $zona_document =  $zona_doc[0]." ".$zona_doc[1];
            }

            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

            //DARI SINI
//                    echo $_FILES['url_files']['name'];die;
            if ($_FILES['url_files']['name'][0] == null) {

                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files']['name']);
            }

            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft'))) ;
            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen'))) ;
            $status = htmlentities(strip_tags($this->input->post('status_dokumen'))) ;

            //echo $status; die;
//            echo $id; die;
            $simpan = '';

            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan))->row();
            $data_lama_url_data_dukung = $cek_data->url_data_dukung;
            $data_lama_lamp_surat_permohonan = $cek_data->lamp_surat_permohonan;
            $nama_perancang = $cek_data->nama_perancang;
            $data_id_user = $cek_data->id_user;

            //echo $nama_perancang; die;

            $cek_data_harmonisasi = $this->db->get_where("tbl_berita",array('id_draft' => $id_draft_permohonan))->row();
            $data_lama_lamp_harmonisasi = $cek_data_harmonisasi->lamp_surat_undangan;

            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else  {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
//                echo $url_file;die;
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }

            //echo $url_data_dukung; die;

            /*menangkap data file untuk lamp_surat_permohonan_edit*/
            if( !$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $data_lama_lamp_surat_permohonan;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_surat_permohonan);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            //echo $lamp_surat_permohonan;die;

            /*menangkap data file untuk hasil lamp_harmonisasi*/
            //lamp_harmonisasi cukx
            if( !$this->upload->do_upload('lamp_harmonisasi')){
                $lamp_surat_harmonisasi = $data_lama_lamp_harmonisasi;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));

            } else  {
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_harmonisasi = preg_replace('/ /', '_', $filename);
//                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_harmonisasi);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            //sampai sini bisa
            //echo $lamp_surat_harmonisasi; die;
            $cek_data_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft' => $id_draft_permohonan));

            if($simpan=='y'){
                $data = array(
                    //'nama_draft_permohonan' => $nama_draft,
                    //'jenis_dokumen' => $jenis_dokumen,
                    //'tgl_update' => date('Y-m-d H:i:s'),
                    //'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    //'url_data_dukung' => $url_data_dukung

                    'nama_draft_permohonan' => $nama_draft,
                    'jenis_dokumen' => $jenis_dokumen,
                    'status' => $status,
                    'nama_perancang' => $nama_perancang,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'url_data_dukung' => $url_data_dukung
                );

                if($cek_data_tbl_berita->num_rows()<=0){
                    $data_tbl_berita = array(
                        'id_user' => $data_id_user,
                        'nama_kegiatan' => $nama_draft,
                        'tgl_kegiatan' => date('Y-m-d H:i:s'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'jenis_dokumen' => $jenis_dokumen,
                        'zona_dokumen' => $zona_document,
                        'id_draft' => $id_draft_permohonan,
                    );

                    $this->db->insert('tbl_berita',$data_tbl_berita);
                } else {
                    $data_tbl_berita = array(
                        'tgl_update' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'nama_kegiatan' => $nama_draft,
                        'lamp_surat_undangan' => $lamp_surat_harmonisasi,
                    );
                    $this->db->update('tbl_berita',$data_tbl_berita, array('id_draft'=>$id_draft_permohonan));
                }

//                echo $id; die;
                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id_draft_permohonan));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                );
            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );
//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("pemda/draft/".$zona);
        }

        if (isset($_POST['btnsimpan_update_kasub_perancang'])) {
//            echo $zona; die;
//            echo $id; die;
            echo "btnsimpan_update_kasub_perancang"; die;
            $zona_doc = explode('_',$zona);
            if($zona_doc[2]!=""){
                $zona_document_strip =  $zona_doc[0]."_".$zona_doc[1]."_".$zona_doc[2];
                $zona_document =  $zona_doc[0]." ".$zona_doc[1]." ".$zona_doc[2];
            } else if($zona_doc[2]=="") {
                $zona_document_strip =  $zona_doc[0]."_".$zona_doc[1];
                $zona_document =  $zona_doc[0]." ".$zona_doc[1];
            }


            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

//                    echo $_FILES['url_files']['name'];die;
            if ($_FILES['url_files']['name'][0] == null) {

                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files']['name']);
            }

            $id_draft_permohonan = htmlentities(strip_tags($this->input->post('id_draft_permohonan'))) ;
//            echo $id_draft_permohonan; die;
            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft'))) ;
            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen'))) ;
            $status = htmlentities(strip_tags($this->input->post('status_dokumen'))) ;
            $nama_perancang = htmlentities(strip_tags($this->input->post('nama_perancang'))) ;

            $simpan = '';

            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan))->row();
            $data_lama_url_data_dukung = $cek_data->url_data_dukung;
            $data_lama_lamp_surat_permohonan = $cek_data->lamp_surat_permohonan;
            $data_id_user = $cek_data->id_user;



            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else  {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
//                echo $url_file;die;
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }

//            lamp_surat_permohonan_edit
            if( !$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $data_lama_lamp_surat_permohonan;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_surat_permohonan);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            //echo $data_lama_lamp_surat_permohonan;die;
            //echo $url_data_dukung;die;

            $cek_data_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft' => $id_draft_permohonan));
//            echo $cek_data_tbl_berita->num_rows(); die;


//            echo $id_draft_permohonan;die;
            if($simpan=='y'){
                $data = array(
                    'nama_draft_permohonan' => $nama_draft,
                    'jenis_dokumen' => $jenis_dokumen,
                    'status' => $status,
                    'nama_perancang' => $nama_perancang,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'url_data_dukung' => $url_data_dukung
                );

                if($cek_data_tbl_berita->num_rows()<=0){
                    //maka insert
                    /*belum masuk id_user*/
                    $data_tbl_berita = array(
                        'id_user' => $data_id_user,
                        'nama_kegiatan' => $nama_draft,
                        'tgl_kegiatan' => date('Y-m-d H:i:s'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'jenis_dokumen' => $jenis_dokumen,
                        'zona_dokumen' => $zona,
                        'id_draft' => $id_draft_permohonan,
                    );

                    $this->db->insert('tbl_berita',$data_tbl_berita);

                } else {
                    //maka update
                    $data_tbl_berita = array(
                        'tgl_update' => date('Y-m-d H:i:s'),
                        'status' => $status,
                    );

                    $this->db->update('tbl_berita',$data_tbl_berita, array('id_draft'=>$id_draft_permohonan));
                }

                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id_draft_permohonan));

                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                );
            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );
//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("pemda/draft/".$zona);
        }

        if (isset($_POST['btnsimpan'])) {
            //echo "zona dok: ".$zona;die;
            //echo "id user : ".$id_user;die;
            //echo "btnsimpan cuy!";die;
            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                mkdir($lokasi, 0777, $rekursif = true);
            }

            if ($_FILES['url_files']['name'][0] == null) {

                $count = 0;
            } else {
                $count = count($_FILES['url_files']['name']);
            }

            /*LANJUTKAN DISINI*/
//                    echo '<pre>'; print_r($_FILES); exit;

            if ($count != 0) {
                for ($i = 0; $i < $count; $i++) {

                    if (!empty($_FILES['url_files']['name'][$i])) {

                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if (!$this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
//                            var_dump($filename); exit;
                        }
                    }
                }
            } else {
                $simpan = 'y';
            }



//            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft')));
//            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen')));

            $nama_kegiatan 	 = htmlentities(strip_tags($this->input->post('nama_kegiatan')));
            $jenis_dokumen 	 = htmlentities(strip_tags($this->input->post('jenis_dokumen')));
            $status 	 = htmlentities(strip_tags($this->input->post('status')));
            $zona_dokumen 	 = htmlentities(strip_tags($this->input->post('zona_dokumen')));


            /*sampai jenis_dokumen aman*/
//                    echo $jenis_dokumen; exit;
            $simpan = '';

            if( ! $this->upload->do_upload('lamp_surat_undangan')){
                $simpan = 'n';
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
            } else {
//                        echo "upload data";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_undangan = preg_replace('/ /', '_', $filename);
//                        echo $lamp_surat_permohonan; die;
//                        echo "tester"; die;
                $simpan = 'y';
            }

            //echo $nama_kegiatan."<br>".$jenis_dokumen."<br>".$status."<br>".$zona_dokumen."<br>".$lamp_surat_undangan;die;

            //lanjutkan disini
            if ($simpan=='y') {
//					    echo "tes"; die;
                /*darimana id_user*/
                $data = array(
                    'lamp_surat_undangan'	=> $lamp_surat_undangan,

                    'id_user'				=> $id_user,
                    'nama_kegiatan'   		=> $nama_kegiatan,
                    'tgl_input'   		    => date('Y-m-d H:i:s'),
                    'tgl_update'   		    => date('Y-m-d H:i:s'),
                    'jenis_dokumen'   		=> $jenis_dokumen,
                    'zona_dokumen'   		=> $zona_dokumen,

                    'status'   		        => $status,

                );
                $this->db->insert('tbl_berita',$data);

                $id_berita = $this->db->insert_id();
                $this->Mcrud->kirim_notif($id_user,'humas',$id_berita,'berita','pelaksana_kirim_berita');

                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil disimpan.
							</div>
							<br>'
                );
            }else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );

//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("harmonisasi2/hasil2/".$zona);
        }



        /*nah ini dia ketika di klik simpan saat selesai edit*/
        if (isset($_POST['btnsimpan_edit'])) {
            //echo "btnsimpan_edit cuy"; die;

            $nama_kegiatan 	 = htmlentities(strip_tags($this->input->post('nama_kegiatan')));
            $jenis_dokumen 	 = htmlentities(strip_tags($this->input->post('jenis_dokumen')));
            $zona_dokumen 	 = htmlentities(strip_tags($this->input->post('zona_dokumen')));
            $status	 = htmlentities(strip_tags($this->input->post('status')));
            $id_berita	 = htmlentities(strip_tags($this->input->post('id_berita')));

            //echo "id_berita : ".$id_berita; die;
            $data_lama = $this->db->get_where('tbl_berita',array('id_berita'=>$id_berita))->row();
            $lamp_surat_undangan_lama = $data_lama->lamp_surat_undangan;
            //echo $lamp_surat_undangan_lama;die;
            //echo $nama_kegiatan."<br>".$jenis_dokumen."<br>".$status."<br>".$zona_dokumen."<br>".$lamp_surat_undangan_lama; die;

            if ( ! $this->upload->do_upload('lamp_surat_undangan'))
            {
                $simpan = 'n';
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                $lamp_surat_undangan_baru = "";
            }
            else
            {
                $gbr = $this->upload->data();
                /*keterangan : $lokasi = 'file/bahan_berita';*/
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_undangan_baru = preg_replace('/ /', '_', $filename);

                try{
                    //$path_lama_akan_dihapus = $lamp_surat_undangan_lama;
                    //unlink($path_lama_akan_dihapus);
                    unlink($lamp_surat_undangan_lama);

                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';


            }


//            echo $lamp_surat_undangan_lama; die;
            $pesan = '';
            $simpan = 'y';


            if ($simpan=='y') {
//					    echo "tes"; die;

                if($lamp_surat_undangan_baru==""){
//                    echo "masih dgn data lama"; die;
                    $lamp_surat_undangan_update = $lamp_surat_undangan_lama;

                    /*ini dia cara hapus file di storage*/
                } else if($lamp_surat_undangan_baru!=""){

//                    echo "data baru tidak sama dengan data lama"; die;
                    $lamp_surat_undangan_update = $lamp_surat_undangan_baru;

                }

                //echo $lamp_surat_undangan_update;die;
                $data = array(
                    'lamp_surat_undangan'	=> $lamp_surat_undangan_update,


                    'nama_kegiatan'   		=> $nama_kegiatan,
                    'jenis_dokumen'   		=> $jenis_dokumen,
                    'status'   		        => $status,
                    'zona_dokumen'   		=> $zona_dokumen,

                );
                $this->db->update('tbl_berita',$data, array('id_berita'=>$id_berita));


                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil disimpan.
							</div>
							<br>'
                );



            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );

//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
//					 redirect("berita/v");

            redirect("harmonisasi2/hasil2/".$zona_dokumen);

        }




    }


    public function v($aksi='' , $id='', $pemda='',$status='' )
    {

//		$id = hashids_decrypt($id);
//        echo $id; die;
        $ceks 	 = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level 	 = $this->session->userdata('level');

//		echo $this->uri->segment(1);
//		echo $this->uri->segment(2);
//		echo $this->uri->segment(3); die;

        if($aksi!='t'){

            $this->session->set_flashdata('msg','');

        }

        if(!isset($ceks)) {
            redirect('web/login');
        }



        $data['user']  			  = $this->Mcrud->get_users_by_un($ceks);

        if ($level=='pelaksana') {
            $this->db->where('id_user',$id_user);
        }
        //dicomment
        if ($aksi=='proses' or $aksi=='konfirmasi' or $aksi=='selesai') {
            $this->db->where('status',$aksi);
        }
        $this->db->order_by('id_berita', 'DESC');
        $data['query'] = $this->db->get("tbl_berita");


        $cek_notif = $this->db->get_where("tbl_notif", array('penerima'=>"$id_user"));
        foreach ($cek_notif->result() as $key => $value) {
            $b_notif = $value->baca_notif;
            if(!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif'=>"$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima'=>$id_user));
            }
        }


        if ($aksi == 't') {
            $zona = $id;
//			    echo $zona; die;
//			    echo "tes"; die;
//				if ($level!='pelaksana') {
//					redirect('404');
//				}

            $p = "tambah_draft";
            $zona_besar = explode('_',$zona);
            $zona_besar2 = strtoupper($zona_besar[0])." ".strtoupper($zona_besar[1])." ".strtoupper($zona_besar[2]);
            $data['judul_web'] 	  = "TAMBAH DRAFT RAPERDA ".$zona_besar2 ;
        }  else if ($aksi=='draft_pemprov_ntb'){
//                echo "pemprov ntb"; die;
            $this->db->order_by('id_draft_permohonan', 'DESC');
//                $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemprov_ntb"));
            $data['query'] = $this->db->get_where("tbl_draft",array("zona_dokumen"=>"pemprov_ntb"));
            /*tabel ny di select belakangan*/
            $p = "draft_pemprov_ntb";
            $data['judul_web'] 	  = "DOKUMEN DRAFT PEMPROV NTB";

        } else if($aksi=='pemkot_mataram'){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkot_mataram"));
            $p = "pemkot_mataram";
            $data['judul_web'] 	= "DOKUMEN HARMONISASI PEMKOT MATARAM";
        } else if($aksi=='pemkot_bima'){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkot_bima"));
            $p = "pemkot_bima";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKOT BIMA";
        } else if($aksi=='pemkab_sumbawa_barat'){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_sumbawa_barat"));
            $p = "pemkab_sumbawa_barat";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB SUMBAWA BARAT";
        } else if($aksi=="pemkab_sumbawa"){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_sumbawa"));
            $p = "pemkab_sumbawa";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB SUMBAWA ";
        } else if($aksi=="pemkab_lombok_utara"){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_lombok_utara"));
            $p = "pemkab_lombok_utara";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK UTARA ";
        } else if($aksi=="pemkab_lombok_timur"){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_lombok_timur"));
            $p = "pemkab_lombok_timur";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK TIMUR ";
        } else if($aksi=="pemkab_lombok_tengah"){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_lombok_tengah"));
            $p = "pemkab_lombok_tengah";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK TENGAH ";
        } else if($aksi=="pemkab_lombok_barat"){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_lombok_barat"));
            $p = "pemkab_lombok_barat";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK BARAT ";
        } else if($aksi=="pemkab_dompu"){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_dompu"));
            $p = "pemkab_dompu";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB DOMPU ";
        } else if($aksi=="pemkab_bima"){
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>"pemkab_bima"));
            $p = "pemkab_bima";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB BIMA ";
        } elseif ($aksi == 'd') {
            $p = "detail";
            $data['judul_web'] 	  = "RINCIAN BAHAN BERITA";
            $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => "$id"))->row();
            if ($data['query']->id_berita=='') {redirect('404');}

            $data['cek_notif'] = $this->db->get_where("tbl_notif", array('penerima'=>"$id_user", 'id_berita'=>"$id"))->row();

            $b_notif = $data['cek_notif']->baca_notif;
            if(!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif'=>"$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima'=>$id_user, 'id_berita'=>"$id"));
            }
        } else if ($aksi == 'e') {
            if($pemda=='pemprov_ntb'){
//                    echo "edit dokumen pemprov ntb";
//                    echo "<br>id:".$id; die;
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMPROV NTB";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkot_mataram'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKOT MATARAM";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkot_bima'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKOT BIMA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_sumbawa_barat'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB SUMBAWA BARAT";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_sumbawa'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB SUMBAWA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_lombok_utara'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK UTARA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_lombok_timur'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK TIMUR";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_lombok_tengah'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK TENGAH";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_lombok_barat'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK BARAT";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_dompu'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB DOMPU";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_bima'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB BIMA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }

        } else if($aksi=='se'){
            if(!isset($ceks)){
                redirect('web/login');
            }

            $id_draft_permohonan_edit = htmlentities(strip_tags($this->input->post('id_draft_permohonan_edit')));
            $judul_draft_permohonan_edit = htmlentities(strip_tags($this->input->post('judul_draft_permohonan_edit')));
            $jenis_dokumen_edit = htmlentities(strip_tags($this->input->post('jenis_dokumen_edit')));

            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan_edit));

            $surat_permohonan_old = $cek_data->row()->lamp_surat_permohonan;
            $data_lama_url_data_dukung = $cek_data->row()->url_data_dukung;


            $max_size = 1024*5;
            $lokasi = 'file/bahan_draft_raperda';
            $this->upload->initialize(array(
                "upload_path" => "./$lokasi",
                "allowed_types" => "*",
                "max_size" => $max_size
            ));

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

            //MENANGKAP data dari inputan file 'lamp_surat_permohonan_edit'

            if(!$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $surat_permohonan_old;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($surat_permohonan_old);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }
//                echo $lamp_surat_permohonan; die;

            //berikutnya MENANGKAP lampiran post 'url_files_edit'
            echo $_FILES['url_files_edit']; die;
            if ($_FILES['url_files_edit']['name'][0] == null) {
                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files_edit']['name'][0]);
            }

//                echo $count_edit; die;


            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files_edit']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files_edit']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files_edit']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files_edit']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files_edit']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files_edit']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else if($this->upload->do_upload('file')) {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }

//                echo $url_data_dukung; die;

            if ($simpan == 'y') {
//                    echo "tes simpanz"; die;
                $data = array(
                    'nama_draft_permohonan' => $judul_draft_permohonan_edit,
                    'jenis_dokumen' => $jenis_dokumen_edit,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'url_data_dukung' => $url_data_dukung
                );


                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id_draft_permohonan_edit));
                $this->session->set_flashdata('msg',
                    '
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<strong>Sukses!</strong> Berhasil disimpan.
					</div>
				<br>'
                );
            } else {
                $this->session->set_flashdata('msg',
                    '
					<div class="alert alert-warning alert-dismissible" role="alert">
						 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							 <span aria-hidden="true">&times;</span>
						 </button>
						 <strong>Gagal!</strong> ' . $pesan . '.
					</div>
				 <br>'
                );
            }

            redirect('pemda/v/'.$id);

        } else if($aksi=='ce'){
            $p = "edit_draft";
            $dt_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft'=> $id))->row();
            $status = $dt_tbl_berita->status;

            $data['edit_draft'] = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id));
            $data['id_draft_permohonan'] = $id;
            $data['status'] = $status;
            $data['dt_tbl_berita'] = $dt_tbl_berita;


            $data['pemda'] = $pemda;

            $zona_doc = explode('_',$pemda);
            $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            $data['judul_web'] = "EDIT DRAFT PERMOHONAN ". strtoupper($zona_document) ;
            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id));


        } else if($aksi=='ce_kasub_perancang'){
            $p = "edit_proses_kasub_perancang";

            $dt_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft'=> $id))->row();
            $status = $dt_tbl_berita->status;

            $data['edit_draft'] = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id));
            $data['id_draft_permohonan'] = $id;
            $data['status'] = $status;
            $data['dt_tbl_berita'] = $dt_tbl_berita;

            $data['pemda'] = $pemda;


            $zona_doc = explode('_',$pemda);
            $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            $data['judul_web'] = "PROSES DRAFT PERMOHONAN ". strtoupper($zona_document) ;
            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id));


        }else if($aksi=='ce_perancang'){
            $p = "edit_proses_perancang";

            $data['edit_draft'] = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id));
            $data['id_draft_permohonan'] = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id));
            $data['tbl_berita_by_draft_id'] = $this->db->get_where("tbl_berita", array('id_draft' => $id));

            $data['pemda'] = $pemda;


            $zona_doc = explode('_',$pemda);

            if($zona_doc[3]!=""){
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2]."_".$zona_doc[3];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2]." ".$zona_doc[3];
            } else if($zona_doc[3]=="") {
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            }

//                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2];
//                $zona_document =  $zona_doc[1]." ".$zona_doc[2];


            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get_where("tbl_berita",array("zona_dokumen"=>$zona_document));
            /*tabel ny di select belakangan*/
//                $p = "pemprov_ntb";
//                $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMPROV NTB";
//                $data['edit_berita'] = $this->db->get_where("tbl_berita", array('zona_dokumen' => $zona_document_strip));

            $data['judul_web'] = "PROSES DRAFT PERMOHONAN ". strtoupper($zona_document) ;
            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id));


        } else if ($aksi == 'h') {

            if (!isset($ceks)) {
                redirect('web/login');
            }

            $id_draft_permohonan = $this->input->post('id_draft_permohonan');
            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id_draft_permohonan));
            $lamp_surat_permohonan_old = $cek_data->row()->lamp_surat_permohonan;
            $url_data_dukung = $cek_data->row()->url_data_dukung;
            $files = json_decode($url_data_dukung, true);

//                echo $lamp_surat_permohonan_old; die;
//                foreach ($files as $key=>$file){
//                    echo $file; die;
//                }
//                var_dump($files);die;



            if($cek_data->num_rows() != 0){
//                    echo strtolower($this->uri->segment(3)); die;
//                    echo $id; die;
//                    echo $cek_data->num_rows(); die;

//                    try{
//                        foreach ($files as $key=>$file){
//                            unlink($file);
//                        }
//                    } catch (Exception $e){
//                        echo json_encode($e);
//                    }

                try{
                    unlink($lamp_surat_permohonan_old);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                foreach ($files as $key => $file){
                    try{
                        unlink($file);
                    } catch (Exception $e){
                        echo json_encode($e);
                    }
                }

//                    try {
//                        foreach ($files as $key => $file) {
//                            unlink($file);
//                        }
//                    } catch (Exception $e) {
//                        echo json_encode($e);
//                    }

                $this->db->delete('tbl_draft', array('id_draft_permohonan' => $id_draft_permohonan));


                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses1!</strong> Berhasil dihapus.
							</div>
							<br>'
                );

                if($id=='pemprov_ntb'){
                    redirect("pemda/v/draft_pemprov_ntb");
                }


            } else {
//                    echo "no data"; die;
                redirect('404_content');
            }



        } else if($aksi == 'df'){
//			    echo "trying to delete the file"; die;
            $id_draft_permohonan_update = $this->input->post('id');
            $cek_data = $this->db->get_where('tbl_draft',array('id_draft_permohonan'=>$id_draft_permohonan_update));
            if (!isset($ceks)) {
                redirect('web/login');
            }

            try {
                $path = $this->input->post('path');

                if (unlink($path)) {
                    $file = json_decode($cek_data->row()->url_data_dukung);
                    //echo $file; die;
//                    var_dump($file); die;
//                    unset($file[$this->input->post('file_id')]);
                    unset($file[$this->input->post('file_id')]);

                    $data = array(
//                            'nama' => $cek_data['nama'],
//                            'tempat' => $cek_data['tempat'],
//                            'tanggal' => $cek_data['tanggal'],
//                            'peserta' => $cek_data['peserta'],
//                            'why' => $cek_data['why'],
//                            'deskripsi' => $cek_data['deskripsi'],
//                            'url_data_dukung' => json_encode(count($file)>0?$file:null),

                        'nama_draft_permohonan' => $cek_data->row()->nama_draft_permohonan,
                        'tgl_input' => $cek_data->row()->tgl_input,
                        'status' => $cek_data->row()->status,
                        'lamp_surat_permohonan' => $cek_data->row()->lamp_surat_permohonan,
                        'url_data_dukung' => json_encode(count($file)>0?array_values($file):null),
                        'id_user' => $this->session->userdata('id_user'),
                        'jenis_dokumen' => $cek_data->row()->jenis_dokumen,
                        'zona_dokumen' => $cek_data->row()->zona_dokumen,
                        'tgl_update' => date('Y-m-d H:i:s')
                    );

//                        $this->Guzzle_model->updateAgenda($id_draft_permohonan_update, $data);
                    $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id_draft_permohonan_update));
                    $this->session->set_flashdata('msg',
                        '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                    );
                } else {
                    $this->session->set_flashdata('msg',
                        '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '." upload data!".'.
	 							</div>
	 						 <br>'
                    );
                }
                //echo "success : " . json_encode($file); die;
                redirect("pemda/v/ce/".$id_draft_permohonan_update."/draft_pemprov_ntb");

            } catch (Exception $e) {
                echo json_encode($e);
            }

        }else{
            $p = "index";
            $data['judul_web'] 	  = "Bahan Berita";
        }

        $this->load->view('users/header', $data);
        $this->load->view("users/draft_raperda/$p", $data);
        $this->load->view('users/footer');

        date_default_timezone_set('Asia/Singapore');
        $tgl = date('Y-m-d H:i:s');


        $max_size = 1024*5;
        $lokasi = 'file/bahan_draft_raperda';
        $this->upload->initialize(array(
            "upload_path" => "./$lokasi",
            "allowed_types" => "*",
            "max_size" => $max_size
        ));

        if (isset($_POST['btnsimpan_update'])) {
//            echo $id;die;
            $zona_doc = explode('_',$pemda);
            if($zona_doc[3]!=""){
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2]."_".$zona_doc[3];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2]." ".$zona_doc[3];
            } else if($zona_doc[3]=="") {
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            }

            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

            //DARI SINI
//                    echo $_FILES['url_files']['name'];die;
            if ($_FILES['url_files']['name'][0] == null) {

                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files']['name']);
            }

            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft'))) ;
            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen'))) ;
            $simpan = '';


            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id))->row();
            $data_lama_url_data_dukung = $cek_data->url_data_dukung;
            $data_lama_lamp_surat_permohonan = $cek_data->lamp_surat_permohonan;
            $data_id_user = $cek_data->id_user;
            $status = $cek_data->status;
//            echo $status;die;
//            echo $data_id_user;die;


            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else  {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
//                echo $url_file;die;
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }



            if( !$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $data_lama_lamp_surat_permohonan;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_surat_permohonan);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            /*LANJUTKAN DISINI*/
//                    echo '<pre>'; print_r($_FILES); exit;

//            if ($count != 0) {
//                for ($i = 0; $i < $count; $i++) {
//
//                    if (!empty($_FILES['url_files']['name'][$i])) {
//
//                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
//                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
//                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
//                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
//                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];
//
//                        if (!$this->upload->do_upload('file')) {
//                            $simpan = 'n';
//                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                        } else {
//                            $gbr = $this->upload->data();
//                            $filename = "$lokasi/" . $gbr['file_name'];
//                            $url_file[$i] = preg_replace('/ /', '_', $filename);
//                            $simpan = 'y';
////                            var_dump($filename); exit;
//                        }
//                    }
//                }
//            } else {
//                $simpan = 'y';
//            }





            /*sampai jenis_dokumen aman*/
//                    echo $jenis_dokumen; exit;
//            $simpan = '';

//            if( ! $this->upload->do_upload('lamp_surat_permohonan')){
//                $simpan = 'n';
//                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//            } else {
////                        echo "upload data";die;
//                $gbr = $this->upload->data();
//                $filename = "$lokasi/".$gbr['file_name'];
//                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);
////                        echo $lamp_surat_permohonan; die;
////                        echo "tester"; die;
//                $simpan = 'y';
//            }
//            echo $simpan; die;

            //lanjutkan disini
            $cek_data_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft'=> $id));
            //echo $cek_data_tbl_berita->lamp_surat_undangan;die;
            $lamp_surat_harmonisasi = $cek_data_tbl_berita->row()->lamp_surat_undangan;

            if($simpan=='y'){
                $data = array(
                    'nama_draft_permohonan' => $nama_draft,
                    'jenis_dokumen' => $jenis_dokumen,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'url_data_dukung' => $url_data_dukung
                );

                if($cek_data_tbl_berita->num_rows()<=0){
                    $data_tbl_berita = array(
                        'id_user' => $data_id_user,
                        'nama_kegiatan' => $nama_draft,
                        'tgl_kegiatan' => date('Y-m-d H:i:s'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'jenis_dokumen' => $jenis_dokumen,
                        'zona_dokumen' => $zona_document,
                        'id_draft' => $id,
                    );
                    $this->db->insert('tbl_berita',$data_tbl_berita);
                } else {
                    $data_tbl_berita = array(
                        'tgl_update' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'lamp_surat_undangan' => $lamp_surat_harmonisasi,
                    );
                    $this->db->update('tbl_berita',$data_tbl_berita, array('id_draft'=>$id));
                }

//                echo $id; die;
                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                );
            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );
//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("pemda/v/draft_pemprov_ntb");
        }

        if (isset($_POST['btnsimpan_update_perancang'])) {
//            echo $id;die;

            $zona_doc = explode('_',$pemda);
            if($zona_doc[3]!=""){
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2]."_".$zona_doc[3];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2]." ".$zona_doc[3];
            } else if($zona_doc[3]=="") {
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            }

            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

            //DARI SINI
//                    echo $_FILES['url_files']['name'];die;
            if ($_FILES['url_files']['name'][0] == null) {

                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files']['name']);
            }

            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft'))) ;
            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen'))) ;
            $status = htmlentities(strip_tags($this->input->post('status_dokumen'))) ;

            //echo $status; die;
//            echo $id; die;
            $simpan = '';

            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id))->row();
            $data_lama_url_data_dukung = $cek_data->url_data_dukung;
            $data_lama_lamp_surat_permohonan = $cek_data->lamp_surat_permohonan;
            $nama_perancang = $cek_data->nama_perancang;
            $data_id_user = $cek_data->id_user;

            //echo $nama_perancang; die;

            $cek_data_harmonisasi = $this->db->get_where("tbl_berita",array('id_draft' => $id))->row();
            $data_lama_lamp_harmonisasi = $cek_data_harmonisasi->lamp_surat_undangan;

            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else  {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
//                echo $url_file;die;
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }

            //echo $url_data_dukung; die;

            /*menangkap data file untuk lamp_surat_permohonan_edit*/
            if( !$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $data_lama_lamp_surat_permohonan;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_surat_permohonan);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            //echo $lamp_surat_permohonan;die;

            /*menangkap data file untuk hasil lamp_harmonisasi*/
            if( !$this->upload->do_upload('lamp_harmonisasi')){
                $lamp_surat_harmonisasi = $data_lama_lamp_harmonisasi;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));

            } else  {
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_harmonisasi = preg_replace('/ /', '_', $filename);
//                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_harmonisasi);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            //sampai sini bisa
            //echo $lamp_surat_harmonisasi; die;
            $cek_data_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft' => $id));

            if($simpan=='y'){
                $data = array(
                    //'nama_draft_permohonan' => $nama_draft,
                    //'jenis_dokumen' => $jenis_dokumen,
                    //'tgl_update' => date('Y-m-d H:i:s'),
                    //'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    //'url_data_dukung' => $url_data_dukung

                    'nama_draft_permohonan' => $nama_draft,
                    'jenis_dokumen' => $jenis_dokumen,
                    'status' => $status,
                    'nama_perancang' => $nama_perancang,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'url_data_dukung' => $url_data_dukung
                );

                if($cek_data_tbl_berita->num_rows()<=0){
                    $data_tbl_berita = array(
                        'id_user' => $data_id_user,
                        'nama_kegiatan' => $nama_draft,
                        'tgl_kegiatan' => date('Y-m-d H:i:s'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'jenis_dokumen' => $jenis_dokumen,
                        'zona_dokumen' => $zona_document,
                        'id_draft' => $id,
                    );

                    $this->db->insert('tbl_berita',$data_tbl_berita);
                } else {
                    $data_tbl_berita = array(
                        'tgl_update' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'lamp_surat_undangan' => $lamp_surat_harmonisasi,
                    );
                    $this->db->update('tbl_berita',$data_tbl_berita, array('id_draft'=>$id));
                }

//                echo $id; die;
                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                );
            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );
//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("pemda/v/".$pemda);
        }

        if (isset($_POST['btnsimpan_update_kasub_perancang'])) {

//            echo $pemda; die;
//            echo $id; die;
            $zona_doc = explode('_',$pemda);
            if($zona_doc[3]!=""){
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2]."_".$zona_doc[3];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2]." ".$zona_doc[3];
            } else if($zona_doc[3]=="") {
                $zona_document_strip =  $zona_doc[1]."_".$zona_doc[2];
                $zona_document =  $zona_doc[1]." ".$zona_doc[2];
            }

            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }

//                    echo $_FILES['url_files']['name'];die;
            if ($_FILES['url_files']['name'][0] == null) {

                $count_edit = 0;
            } else {
                $count_edit = count($_FILES['url_files']['name']);
            }

            $id_draft_permohonan = htmlentities(strip_tags($this->input->post('id_draft_permohonan'))) ;
//            echo $id_draft_permohonan; die;
            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft'))) ;
            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen'))) ;
            $status = htmlentities(strip_tags($this->input->post('status_dokumen'))) ;
            $nama_perancang = htmlentities(strip_tags($this->input->post('nama_perancang'))) ;

            $simpan = '';

            $cek_data = $this->db->get_where("tbl_draft", array('id_draft_permohonan' => $id))->row();
            $data_lama_url_data_dukung = $cek_data->url_data_dukung;
            $data_lama_lamp_surat_permohonan = $cek_data->lamp_surat_permohonan;
            $data_id_user = $cek_data->id_user;



            if($count_edit != 0 ){

                for($i = 0; $i < $count_edit; $i++){
                    if(!empty($_FILES['url_files']['name'][$i])){
                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if ( ! $this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else  {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
                        }
                    }
                }
//                echo $url_file;die;
                $file_lama = json_decode($data_lama_url_data_dukung=='null'?"[]":$data_lama_url_data_dukung);
                $url_data_dukung =  json_encode(array_merge($file_lama, $url_file));
            } else{
                $url_data_dukung = $data_lama_url_data_dukung;
                $simpan = 'y';
            }

            if( !$this->upload->do_upload('lamp_surat_permohonan_edit')){
//                    echo "tidak upload data maka gunakan DATA LAMA";die;
                $lamp_surat_permohonan = $data_lama_lamp_surat_permohonan;
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
//                $simpan = 'n';

            } else  {
//                    echo "upload data maka gunakan DATA BARU";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);

                try{
                    unlink($data_lama_lamp_surat_permohonan);
                } catch (Exception $e){
                    echo json_encode($e);
                }

                $simpan = 'y';
            }

            $cek_data_tbl_berita = $this->db->get_where("tbl_berita", array('id_draft' => $id));
//            echo $cek_data_tbl_berita->num_rows(); die;



            if($simpan=='y'){
                $data = array(
                    'nama_draft_permohonan' => $nama_draft,
                    'jenis_dokumen' => $jenis_dokumen,
                    'status' => $status,
                    'nama_perancang' => $nama_perancang,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'lamp_surat_permohonan' => $lamp_surat_permohonan,
                    'url_data_dukung' => $url_data_dukung
                );

                if($cek_data_tbl_berita->num_rows()<=0){
                    //maka insert
                    /*belum masuk id_user*/
                    $data_tbl_berita = array(
                        'id_user' => $data_id_user,
                        'nama_kegiatan' => $nama_draft,
                        'tgl_kegiatan' => date('Y-m-d H:i:s'),
                        'tgl_input' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'jenis_dokumen' => $jenis_dokumen,
                        'zona_dokumen' => $zona_document,
                        'id_draft' => $id,
                    );

                    $this->db->insert('tbl_berita',$data_tbl_berita);

                } else {
                    //maka update
                    $data_tbl_berita = array(
                        'tgl_update' => date('Y-m-d H:i:s'),
                        'status' => $status,
                    );

                    $this->db->update('tbl_berita',$data_tbl_berita, array('id_draft'=>$id));
                }

                $this->db->update('tbl_draft',$data, array('id_draft_permohonan'=>$id));

                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil update.
							</div>
							<br>'
                );
            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );
//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("pemda/draft/".$zona);
        }

        if (isset($_POST['btnsimpan'])) {
            /*id sudah sampai disini*/

            //udh bisa sampai sini aman
            //echo "coba simpan"; die;
            if(!isset($ceks)){
                redirect('web/login');
            }

            if (!is_dir($lokasi)) {
                # jika tidak maka folder harus dibuat terlebih dahulu
                mkdir($lokasi, 0777, $rekursif = true);
            }


//                    echo '<pre>'; print_r($_FILES); die;




            //DARI SINI
//                    echo $_FILES['url_files']['name'];die;
            if ($_FILES['url_files']['name'][0] == null) {

                $count = 0;
            } else {
                $count = count($_FILES['url_files']['name']);
            }

            /*LANJUTKAN DISINI*/
//                    echo '<pre>'; print_r($_FILES); exit;

            if ($count != 0) {
                for ($i = 0; $i < $count; $i++) {

                    if (!empty($_FILES['url_files']['name'][$i])) {

                        $_FILES['file']['name'] = $_FILES['url_files']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['url_files']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['url_files']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['url_files']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['url_files']['size'][$i];

                        if (!$this->upload->do_upload('file')) {
                            $simpan = 'n';
                            $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        } else {
                            $gbr = $this->upload->data();
                            $filename = "$lokasi/" . $gbr['file_name'];
                            $url_file[$i] = preg_replace('/ /', '_', $filename);
                            $simpan = 'y';
//                            var_dump($filename); exit;
                        }
                    }
                }
            } else {
                $simpan = 'y';
            }


            $nama_draft = htmlentities(strip_tags($this->input->post('nama_draft')));
            $jenis_dokumen = htmlentities(strip_tags($this->input->post('jenis_dokumen')));

            /*sampai jenis_dokumen aman*/
//                    echo $jenis_dokumen; exit;
            $simpan = '';


            /*lamp_surat_permohonan*/
            /*dari sini*/
            if( ! $this->upload->do_upload('lamp_surat_permohonan')){
                $simpan = 'n';
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
            } else {
//                        echo "upload data";die;
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_permohonan = preg_replace('/ /', '_', $filename);
//                        echo $lamp_surat_permohonan; die;
//                        echo "tester"; die;
                $simpan = 'y';
            }
            /*sampai sini*/


            /*naskah_akademik_dll*/
            /*dari sini*/
            if( ! $this->upload->do_upload('naskah_akademik_dll')){
                $simpan = 'n';
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
            } else {
                $gbrz= $this->upload->data();
                $filenamez = "$lokasi/".$gbrz['file_name'];
                $naskah_akademik_dll = preg_replace('/ /', '_', $filenamez);
                $simpan = 'y';
            }
            /*sampai sini*/


            //lanjutkan disini
            if($simpan=='y'){
//                        $zona_doc = explode('_',(strtolower($this->uri->segment(3)))) ;
                $data = array(
                    'lamp_surat_permohonan'	=> $lamp_surat_permohonan,
                    'naskah_akademik_dll'	=> $naskah_akademik_dll,

                    'nama_draft_permohonan'	=> $nama_draft,
                    'jenis_dokumen'	        => $jenis_dokumen,
                    'id_user'   		    => $id_user,
                    'tgl_input'   		    => date('Y-m-d H:i:s'),
                    'tgl_update'   		    => date('Y-m-d H:i:s'),
                    'zona_dokumen'   		=> $id,
                    'url_data_dukung'      => json_encode($url_file),
                    'status'   		        => 'belum_diproses',
                );

                $this->db->insert('tbl_draft',$data);

                $id_berita = $this->db->insert_id();
                $this->Mcrud->kirim_notif($id_user,'humas',$id_berita,'berita','pelaksana_kirim_berita');

                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil disimpan.
							</div>
							<br>'
                );
            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );
//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
            redirect("pemda/v/draft_pemprov_ntb");
        }



        /*nah ini dia ketika di klik simpan saat selesai edit*/
        if (isset($_POST['btnsimpan_edit'])) {
//            echo $pemda;die;

//            echo "btnsimpan_edit"; die;
            $nama_kegiatan 	 = htmlentities(strip_tags($this->input->post('nama_kegiatan')));
            $jenis_dokumen 	 = htmlentities(strip_tags($this->input->post('jenis_dokumen')));
            $zona_dokumen 	 = htmlentities(strip_tags($this->input->post('zona_dokumen')));
            $status	 = htmlentities(strip_tags($this->input->post('status')));

            $data_lama = $this->db->get_where('tbl_berita',array('id_berita'=>$id))->row();
            $lamp_surat_undangan_lama = $data_lama->lamp_surat_undangan;

//            echo $lamp_surat_undangan_lama; die;
            if ( ! $this->upload->do_upload('lamp_surat_undangan'))
            {
                $simpan = 'n';
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                $lamp_surat_undangan_baru = "";
            }
            else
            {
                $gbr = $this->upload->data();
                /*keterangan : $lokasi = 'file/bahan_berita';*/
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_undangan_baru = preg_replace('/ /', '_', $filename);
                $simpan = 'y';


            }


//            echo $lamp_surat_undangan_lama; die;
            $pesan = '';
            $simpan = 'y';

            if ($simpan=='y') {
//					    echo "tes"; die;

                if($lamp_surat_undangan_baru==""){
//                    echo "masih dgn data lama"; die;
                    $lamp_surat_undangan_update = $lamp_surat_undangan_lama;

                    /*ini dia cara hapus file di storage*/
                } else if($lamp_surat_undangan_baru!=""){

//                    echo "data baru tidak sama dengan data lama"; die;
                    $lamp_surat_undangan_update = $lamp_surat_undangan_baru;
                    try{
                        $path_lama_akan_dihapus = $lamp_surat_undangan_lama;
                        unlink($path_lama_akan_dihapus);

                    } catch (Exception $e){
                        echo json_encode($e);
                    }
                }
                $data = array(
                    'lamp_surat_undangan'	=> $lamp_surat_undangan_update,


                    'nama_kegiatan'   		=> $nama_kegiatan,
                    'jenis_dokumen'   		=> $jenis_dokumen,
                    'zona_dokumen'   		=> $zona_dokumen,
                    'status'   		        => $status,

                );
                $this->db->update('tbl_berita',$data, array('id_berita'=>$id));


                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil disimpan.
							</div>
							<br>'
                );



            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );

//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
//					 redirect("berita/v");
            redirect("harmonisasi/v/".$zona_dokumen);

        }




    }


    public function v2($aksi='' , $id='', $status='' )
    {
        $id = hashids_decrypt($id);
        $ceks 	 = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level 	 = $this->session->userdata('level');

//		echo $this->uri->segment(1);
//		echo $this->uri->segment(2);
//		echo $this->uri->segment(3); die;

        if($aksi!='t'){
            $this->session->set_flashdata('msg','');

        }

        if(!isset($ceks)) {
            redirect('web/login');
        }

        $data['user']  			  = $this->Mcrud->get_users_by_un($ceks);

        if ($level=='pelaksana') {
            $this->db->where('id_user',$id_user);
        }
        //dicomment
        if ($aksi=='proses' or $aksi=='konfirmasi' or $aksi=='selesai') {
            $this->db->where('status',$aksi);
        }
        $this->db->order_by('id_berita', 'DESC');
        $data['query'] = $this->db->get("tbl_berita");


        $cek_notif = $this->db->get_where("tbl_notif", array('penerima'=>"$id_user"));
        foreach ($cek_notif->result() as $key => $value) {
            $b_notif = $value->baca_notif;
            if(!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif'=>"$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima'=>$id_user));
            }
        }


        if ($aksi == 't') {
//			    echo "tes"; die;
//				if ($level!='pelaksana') {
//					redirect('404');
//				}
            $p = "tambah";
            $data['judul_web'] 	  = "TAMBAH DOKUMEN HARMONISASI";
        } else if ($aksi=='pemprov_ntb'){
//            echo "pemprov ntb"; die;

            /*'belum_diproses','perbaikan','draft_sedang_dibuat','menunggu_koreksi','selesai'*/
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='sedang_diproses' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemprov_ntb");
            } else if($status=='semua'){
                redirect("pemda/v/draft_pemprov_ntb");
            }
            $this->db->order_by('id_draft_permohonan', 'DESC');
            $data['query'] = $this->db->get("tbl_draft");
            /*tabel ny di select belakangan*/

            $p = "draft_pemprov_ntb";
            $data['judul_web'] 	  = "DOKUMEN DRAFT PEMPROV NTB";

        } else if($aksi=='pemkot_mataram'){
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkot_mataram");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkot_mataram");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkot_mataram";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKOT MATARAM";


        } else if($aksi=='pemkot_bima'){

            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkot_bima");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkot_bima");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkot_bima";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKOT BIMA";

        } else if($aksi=='pemkab_sumbawa_barat'){
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_sumbawa_barat");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_sumbawa_barat");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_sumbawa_barat";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB SUMBAWA BARAT";

        } else if($aksi=="pemkab_sumbawa"){
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_sumbawa");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_sumbawa");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_sumbawa";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB SUMBAWA";


        } else if($aksi=="pemkab_lombok_utara"){
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_lombok_utara");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_lombok_utara");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_lombok_utara";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK UTARA";


        } else if($aksi=="pemkab_lombok_timur"){
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_lombok_timur");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_lombok_timur");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_lombok_timur";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK TIMUR";


        } else if($aksi=="pemkab_lombok_tengah"){
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_lombok_tengah");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_lombok_tengah");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_lombok_tengah";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK TENGAH";


        } else if($aksi=="pemkab_lombok_barat"){
            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_lombok_barat");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_lombok_barat");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_lombok_barat";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB LOMBOK BARAT";



        } else if($aksi=="pemkab_dompu"){

            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_dompu");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_dompu");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_dompu";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB DOMPU";

        } else if($aksi=="pemkab_bima"){

            if ($status=='belum_diproses' or $status=='perbaikan' or $status=='draft_sedang_dibuat' or $status=='menunggu_koreksi' or $status=='selesai') {
                $this->db->where('status',$status);
                $this->db->where('zona_dokumen',"pemkab_bima");
            } else if($status=='semua'){
                redirect("harmonisasi/v/pemkab_bima");
            }
            $this->db->order_by('id_berita', 'DESC');
            $data['query'] = $this->db->get("tbl_berita");
            /*tabel ny di select belakangan*/

            $p = "pemkab_bima";
            $data['judul_web'] 	  = "DOKUMEN HARMONISASI PEMKAB BIMA";

        } elseif ($aksi == 'd') {
            $p = "detail";
            $data['judul_web'] 	  = "RINCIAN BAHAN BERITA";
            $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => "$id"))->row();
            if ($data['query']->id_berita=='') {redirect('404');}

            $data['cek_notif'] = $this->db->get_where("tbl_notif", array('penerima'=>"$id_user", 'id_berita'=>"$id"))->row();

            $b_notif = $data['cek_notif']->baca_notif;
            if(!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif'=>"$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima'=>$id_user, 'id_berita'=>"$id"));
            }
        } else if ($aksi == 'e') {
            if($pemda=='pemprov_ntb'){
//                    echo "edit dokumen pemprov ntb";
//                    echo "<br>id:".$id; die;
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMPROV NTB";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkot_mataram'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKOT MATARAM";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkot_bima'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKOT BIMA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_sumbawa_barat'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB SUMBAWA BARAT";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_sumbawa'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB SUMBAWA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_lombok_utara'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK UTARA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            } else if($pemda=='pemkab_lombok_timur'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK TIMUR";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_lombok_tengah'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK TENGAH";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_lombok_barat'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB LOMBOK BARAT";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_dompu'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB DOMPU";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }else if($pemda=='pemkab_bima'){
                $p = "edit";
                $data['judul_web'] = "EDIT DOKUMEN RAPERDA PEMKAB BIMA";
                $data['query'] = $this->db->get_where("tbl_berita", array('id_berita' => $id))->row();
                if ($data['query']->id_berita == '') {
                    redirect('404');
                }
            }



        } else if ($aksi == 'h') {

//			    echo "hapus data bro"; die;
            $cek_data = $this->db->get_where("tbl_berita", array('id_berita' => $id));
            $lamp_surat_lama = $cek_data->row()->lamp_surat_undangan;
//				echo $lamp_surat_lama; die;
//				echo $cek_data->result_array()[0]['lamp_surat_undangan'];die;
//				echo $cek_data->row()->lamp_surat_undangan;die;
//				echo $cek_data->num_rows();die;
//                $this->db->delete('tbl_berita', array('id_berita' => $id));die;
            if ($cek_data->num_rows() != 0) {
//					if ($cek_data->row()->status!='menunggu') {
//							redirect('404');
//						}
//						if ($cek_data->row()->lampiran != '') {
//							unlink($cek_data->row()->lampiran);
//						}
                // $this->db->delete('tbl_notif', array('pengirim'=>$id_user,'id_berita'=>$id));
                $this->db->delete('tbl_berita', array('id_berita' => $id));
                try {
                    unlink($lamp_surat_lama);
                } catch (Exception $e) {
                    echo json_encode($e);
                }

                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses1!</strong> Berhasil dihapus.
							</div>
							<br>'
                );


                if($pemda=='pemprov_ntb'){

                    redirect("harmonisasi/v/pemprov_ntb");
                } else if($pemda=='pemkot_mataram'){
                    redirect("harmonisasi/v/pemkot_mataram");
                } else if ($pemda=='pemkot_bima'){
                    redirect("harmonisasi/v/pemkot_bima");
                } else if($pemda=='pemkab_sumbawa_barat'){
                    redirect("harmonisasi/v/pemkab_sumbawa_barat");
                } else if($pemda=='pemkab_sumbawa'){
                    redirect("harmonisasi/v/pemkab_sumbawa");
                } else if($pemda=="pemkab_lombok_utara"){
                    redirect("harmonisasi/v/pemkab_lombok_utara");
                } else if($pemda=="pemkab_lombok_timur"){
                    redirect("harmonisasi/v/pemkab_lombok_timur");
                } else if ($pemda=="pemkab_lombok_tengah"){
                    redirect("harmonisasi/v/pemkab_lombok_tengah");
                } else if($pemda=="pemkab_lombok_barat") {
                    redirect("harmonisasi/v/pemkab_lombok_barat");
                } else if ($pemda=="pemkab_dompu"){
                    redirect("harmonisasi/v/pemkab_dompu");
                } else if($pemda=="pemkab_bima"){
                    redirect("harmonisasi/v/pemkab_bima");
                }

//						redirect("berita/v");
                redirect("harmonisasi/v/pemprov_ntb");
            }else {
                redirect('404_content');
            }
        } else if($aksi == 'df'){
            echo "delete lampiran";die;
        }else{
            $p = "index";
            $data['judul_web'] 	  = "Bahan Berita";
        }

        $this->load->view('users/header', $data);
        $this->load->view("users/draft_raperda/$p", $data);
        $this->load->view('users/footer');

        date_default_timezone_set('Asia/Singapore');
        $tgl = date('Y-m-d H:i:s');

        $lokasi = 'file/bahan_berita';
        $this->upload->initialize(array(
            "upload_path"   => "./$lokasi",
            "allowed_types" => "*"
        ));

        if (isset($_POST['btnsimpan'])) {

            //echo "tess"; die;
            $nama_kegiatan 	 = htmlentities(strip_tags($this->input->post('nama_kegiatan')));
            $jenis_dokumen 	 = htmlentities(strip_tags($this->input->post('jenis_dokumen')));
            $zona_dokumen 	 = htmlentities(strip_tags($this->input->post('zona_dokumen')));

            $simpan = '';



            if ( ! $this->upload->do_upload('lamp_surat_undangan'))
            {
                $simpan = 'n';
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
            }
            else
            {
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_undangan = preg_replace('/ /', '_', $filename);
                $simpan = 'y';
            }
            if ($simpan=='y') {
//					    echo "tes"; die;
                $data = array(
                    'lamp_surat_undangan'	=> $lamp_surat_undangan,

                    'id_user'				=> $id_user,
                    'nama_kegiatan'   		=> $nama_kegiatan,
                    'jenis_dokumen'   		=> $jenis_dokumen,
                    'zona_dokumen'   		=> $zona_dokumen,

                );
                $this->db->insert('tbl_berita',$data);

                $id_berita = $this->db->insert_id();
                $this->Mcrud->kirim_notif($id_user,'humas',$id_berita,'berita','pelaksana_kirim_berita');

                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil disimpan.
							</div>
							<br>'
                );
            }else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );

//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
//					 redirect("berita/v");

//					 redirect("harmonisasi/v/pemprov_ntb");
            redirect("harmonisasi/v/".$zona_dokumen);

        }

        if (isset($_POST['btnsimpan_edit'])) {
//            echo $pemda;die;

//            echo "btnsimpan_edit"; die;
            $nama_kegiatan 	 = htmlentities(strip_tags($this->input->post('nama_kegiatan')));
            $jenis_dokumen 	 = htmlentities(strip_tags($this->input->post('jenis_dokumen')));
            $zona_dokumen 	 = htmlentities(strip_tags($this->input->post('zona_dokumen')));

            $data_lama = $this->db->get_where('tbl_berita',array('id_berita'=>$id))->row();
            $lamp_surat_undangan_lama = $data_lama->lamp_surat_undangan;

//            echo $lamp_surat_undangan_lama; die;
            if ( ! $this->upload->do_upload('lamp_surat_undangan'))
            {
                $simpan = 'n';
                $pesan  = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
            }
            else
            {
                $gbr = $this->upload->data();
                $filename = "$lokasi/".$gbr['file_name'];
                $lamp_surat_undangan_baru = preg_replace('/ /', '_', $filename);
                $simpan = 'y';


            }


//            echo $lamp_surat_undangan_lama; die;
            $pesan = '';
            $simpan = 'y';

            if ($simpan=='y') {
//					    echo "tes"; die;

                if($lamp_surat_undangan_baru==""){
//                    echo "masih dgn data lama"; die;
                    $lamp_surat_undangan_update = $lamp_surat_undangan_lama;

                    /*ini dia cara hapus file di storage*/
                } else if($lamp_surat_undangan_baru!=""){
//                    echo "data baru tidak sama dengan data lama"; die;
                    $lamp_surat_undangan_update = $lamp_surat_undangan_baru;
                    try{
                        $path_lama_akan_dihapus = $lamp_surat_undangan_lama;
                        unlink($path_lama_akan_dihapus);

                    } catch (Exception $e){
                        echo json_encode($e);
                    }
                }
                $data = array(
                    'lamp_surat_undangan'	=> $lamp_surat_undangan_update,


                    'nama_kegiatan'   		=> $nama_kegiatan,
                    'jenis_dokumen'   		=> $jenis_dokumen,
                    'zona_dokumen'   		=> $zona_dokumen,

                );
                $this->db->update('tbl_berita',$data, array('id_berita'=>$id));


                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil disimpan.
							</div>
							<br>'
                );



            } else {

                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> '.$pesan.'.
	 							</div>
	 						 <br>'
                );

//							redirect("berita/v/$aksi/".hashids_decrypt($id));
//							redirect("harmonisasi/v/t");
            }
//					 redirect("berita/v");
            redirect("harmonisasi/v/".$zona_dokumen);

        }




    }

    public function ajax()
    {
        if (isset($_POST['btnkirim'])) {
            $id = $this->input->post('id');
            $data = $this->db->get_where('tbl_berita',array('id_berita'=>$id))->row();
            $pesan_humas = $data->pesan_humas;
            $status = $data->status;
            echo json_encode(array('pesan_petugas'=>$pesan_humas,'status'=>$status));
        } else {
            redirect('404');
        }
    }

}
