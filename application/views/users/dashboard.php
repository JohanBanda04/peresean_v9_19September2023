<?php
$cek    = $user->row();
$level  = $cek->level;


?>
<!-- begin #content -->
<div id="content" class="content">
	  <!-- begin breadcrumb -->
	  <ol class="breadcrumb pull-right">
		<li class="active">Dashboard</li>
	  </ol>
	  <!-- end breadcrumb -->
	  <!-- begin page-header -->
    <?php
    $nama_user = explode('_',$_SESSION['username']);
    if(count($nama_user)==3){
        $nama_user_fix = $nama_user[0]." ".$nama_user[1]." ".$nama_user[2];
    } else if (count($nama_user)==2){
        $nama_user_fix = $nama_user[0]." ".$nama_user[1];
    } else if (count($nama_user)==1){
        $nama_user_fix = $nama_user[0];
    }
    ?>
	  <h1 class="page-header" style="font-size: 25px">Dashboard  <small> <?php echo strtoupper($nama_user_fix);?></small></h1>
	  <h3 class="page-header"  style="font-size: 18px">Selamat Datang di PERESEAN</h3>
	  <!-- end page-header -->
	  <!-- begin row -->



	<!-- DASHBOARD superADMIN -->

	<div class="row">
        <div class="panel panel-inverse">
            <div class="panel-body">
                <div style="margin-left: 3px" class="row hidden">
                    <h5>Unggah Dokumen Hasil Harmonisasi</h5>
                    <br>
                    <a href="harmonisasi/v/t.html" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Dokumen </a>

                </div>

<!--                <hr>-->
                <h5>Lihat Dokumen Raperdaz <?php
                    /*tempat orek2*/
                    //echo $nama_lengkap;
                    //echo "<pre>"; print_r($zona_daerah_list);
                ?></h5>
                <div class="row">

                    <div <?php if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='kasub_perancang'){ ?> class="col-md-6" <?php } else { ?> class="hidden col-md-6" <?php } ?>>
                        <a href="harmonisasi/v/harmonisasi.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-dark-blue text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Dokumen Harmonisasi</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }

                                    echo number_format($this->db->get('tbl_berita')->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>
                        </a>

                    </div>

                    <div  <?php if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='kasub_perancang'){ ?> class="col-md-6" <?php } else {?> class="hidden col-md-6" <?php } ?> >
                        <a href="harmonisasi/v/draft_pemda.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Dokumen Draft Harmonisasi</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }

                                    echo number_format($this->db->get('tbl_draft')->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>


                    <!--dari sini sampai sini-->
                    <div <?php if($_SESSION['nama_zona']=='pemprov_ntb'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='perancang') { ?> class="col-md-3" <?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemprov_ntb.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-purple text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemprov NTB</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }

                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemprov_ntb'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>
                        </a>

                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemprov_ntb'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemprov_ntb.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-dark-blue-light text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemprov NTB</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemprov_ntb'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemkot_mataram'){?> class="col-md-6" <?php }  else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkot_mataram.html" style="text-decoration: none; ">
                            <div class="widget widget-stats bg-gradient-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkot Mataram</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkot_mataram'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>
                        </a>
                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemkot_mataram'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkot_mataram.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-dark-blue-light text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkot Mataram</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkot_mataram'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>



                    <div <?php if($_SESSION['nama_zona']=='pemkot_bima'){?> class="col-md-6" <?php }  else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkot_bima.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-dark-blue-light text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkot Bima</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkot_bima'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>
                        </a>
                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemkot_bima'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkot_bima.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkot Bima</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkot_bima'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemkab_sumbawa_barat'){?> class="col-md-6" <?php }   else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_sumbawa_barat.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Sumbawa Barat</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_sumbawa_barat'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>
                        </a>

                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemkab_sumbawa_barat'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_sumbawa_barat.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Sumbawa Barat</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_sumbawa_barat'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_sumbawa'){?> class="col-md-6" <?php }  else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_sumbawa.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-green text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Sumbawa</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_sumbawa'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>

                        </a>
                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemkab_sumbawa'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_sumbawa.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Sumbawa</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_sumbawa'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_utara'){?> class="col-md-6" <?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_lombok_utara.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-blue-inverse text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Utara</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_lombok_utara'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>

                        </a>
                    </div>

                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_utara'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_lombok_utara.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Utara</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_lombok_utara'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_timur'){?> class="col-md-6" <?php }  else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_lombok_timur.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-yellow text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Timur</div>
                                <div style="color: white" class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_lombok_timur'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>

                        </a>
                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_timur'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_lombok_timur.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Timur</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_lombok_timur'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_tengah'){?> class="col-md-6" <?php }  else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_lombok_tengah.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-blue-dark text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Tengah</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_lombok_tengah'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>

                        </a>
                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_tengah'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_lombok_tengah.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Tengah</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_lombok_tengah'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_barat'){?> class="col-md-6" <?php }  else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_lombok_barat.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-pink text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Barat</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_lombok_barat'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>

                        </a>
                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_lombok_barat'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_lombok_barat.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Lombok Barat</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_lombok_barat'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_dompu'){?> class="col-md-6" <?php }  else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_dompu.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Dompu</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_dompu'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>

                        </a>
                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_dompu'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_dompu.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Dompu</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_dompu'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_bima'){?> class="col-md-6" <?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="harmonisasi/v/pemkab_bima.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-purple-inverse text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Bima</div>
                                <div style="color: #f9fffb" class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_berita', array('zona_dokumen'=>'pemkab_bima'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Harmonisasi</div>
                            </div>

                        </a>
                    </div>
                    <div <?php if($_SESSION['nama_zona']=='pemkab_bima'){?> class="col-md-6" <?php } else if($_SESSION['nama_zona']=='superadmin'){ ?> class="col-md-3 hidden"<?php } else { ?> class="col-md-3 hidden" <?php } ?> >
                        <a href="pemda/draft/pemkab_bima.html" style="text-decoration: none">
                            <div class="widget widget-stats bg-gradient-red-orange text-inverse">
                                <div class="stats-icon stats-icon-lg stats-icon-square">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </div>
                                <div class="stats-desc">Pemkab Bima</div>
                                <div class="stats-number">
                                    <?php
                                    if($level=='pelaksana'){
                                        $this->db->where('id_user',$cek->id_user);
                                    }
                                    echo number_format($this->db->get_where('tbl_draft', array('zona_dokumen'=>'pemkab_bima'))->num_rows(),0,",","."); ?>
                                </div>
                                <div class="stats-progress progress">
                                    <div class="progress-bar" style="width: 70.1%;"></div>
                                </div>
                                <div class="stats-desc">Total Dokumen Draft Raperda</div>
                            </div>
                        </a>

                    </div>
                    <!--sampai sini-->
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="realisasi-card card">
                            <div class="card-body">
                                <!--grafik data paling awal bar chart zona daerah-->
                                <canvas id="bar_chart_zona_daerah" height="175">tes</canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-content-accordion-1 c-theme dashboard-all">
                    <div class="panel-group" id="accordion" role="tablist">
                        <?php
                        //echo "<pre>"; print_r($array_daerah) ;
                        //echo "<pre>"; print_r($zona_daerah_list_ii) ; die;
                        ?>

                        <?php
                            $isFirst = true;
                            foreach ($array_daerah as $key=>$val){
                                //echo $val->id_zona;
                                ?>
                                <div class="panel">
                                    <div class="panel-heading dipa-accordion-btn" role="tab"
                                         id="heading<?php echo $val->id_zona; ?>" style="color: white">
                                        <h4 class="panel-title">
                                            <a class="c-font-bold c-font-19"  data-toggle="collapse" data-parent="#accordion"
                                               href="#collapse<?php echo $val->id_zona; ?>" aria-expanded="true"
                                               aria-controls="collapse<?php echo $val->id_zona; ?>">
                                                <?php echo $val->nama_panjang; ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?php echo $val->id_zona; ?>"
                                         class="panel-collapse collapse <?php if ($isFirst) { ?> in <?php } ?>"
                                         role="tabpanel"
                                         aria-labelledby="heading<?php echo $val->id_zona; ?>">
                                        <div class="panel-body c-font-18">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="realisasi-card card">
                                                        <div class="card-body">
                                                            <div class="penyerapan-chart row">
                                                                <div class="col-md-5">
                                                                    <canvas id="chart_penyerapan<?php echo $val->id_zona; ?>"></canvas>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="dashboard-progress">
                                                                        <div class="progress-title" style="color: white">
                                                                            TOTAL DOKUMEN HARMONISASI
                                                                        </div>
                                                                        <div class="text-white progress-angka">
                                                                            <?php

                                                                            $total_dokumen_per_zona =  $this->db->get_where('tbl_berita',array(
                                                                                    'zona_dokumen'=>$val->nama_zona,

                                                                                ));

                                                                            $total_dokumen_per_zona_selesai =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,
                                                                                'status'=>"selesai",

                                                                            ));

                                                                            $total_dokumen_per_zona_belum_selesai =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,
                                                                                'status !='=>"selesai",

                                                                            ));

                                                                            $persentase_total = ($total_dokumen_per_zona->num_rows() * 100) / ($total_dokumen_per_zona_selesai->num_rows() + $total_dokumen_per_zona_belum_selesai->num_rows()) ;
                                                                            //cukz
                                                                            $persentase_total_formatted = number_format($persentase_total,2,",","");
                                                                            echo $total_dokumen_per_zona->num_rows() ." DOKUMEN"." (".$persentase_total_formatted."%)";
                                                                            ?>
                                                                        </div>
                                                                        <div class="progress">
                                                                            <div class="progress-bar progress-bar-striped" role="progressbar"
                                                                                 style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="dashboard-progress">
                                                                        <div class="progress-title">DOKUMEN DIHARMONISASI (SELESAI)</div>
                                                                        <div class="text-white progress-angka">
                                                                            <?php

                                                                            $total_dokumen_per_zona =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,

                                                                            ));

                                                                            $total_dokumen_per_zona_selesai =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,
                                                                                'status'=>"selesai",

                                                                            ));

                                                                            $total_dokumen_per_zona_belum_selesai =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,
                                                                                'status !='=>"selesai",

                                                                            ));

                                                                            $persentase_total_selesai = ($total_dokumen_per_zona_selesai->num_rows() * 100) / ($total_dokumen_per_zona_selesai->num_rows() + $total_dokumen_per_zona_belum_selesai->num_rows()) ;
                                                                            $persentase_total_selesai_formatted = number_format($persentase_total_selesai,2,",","");
                                                                            echo $total_dokumen_per_zona_selesai->num_rows() ." DOKUMEN"." (".$persentase_total_selesai_formatted."%)";
                                                                            /*cuky*/
                                                                            ?>

                                                                        </div>
                                                                        <div class="progress">
                                                                            <div class="progress-bar progress-bar-success progress-bar-striped " role="progressbar"
                                                                                 aria-valuenow="<?php echo $persentase_total_selesai;  ?>"
                                                                                 aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $persentase_total_selesai; ?>%">
                                                                                <span class="sr-only"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dashboard-progress">
                                                                        <div class="progress-title">DOKUMEN DIHARMONISASI (BELUM SELESAI)</div>
                                                                        <div class="text-white progress-angka">
                                                                            <?php

                                                                            $total_dokumen_per_zona =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,

                                                                            ));

                                                                            $total_dokumen_per_zona_selesai =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,
                                                                                'status'=>"selesai",

                                                                            ));

                                                                            $total_dokumen_per_zona_belum_selesai =  $this->db->get_where('tbl_berita',array(
                                                                                'zona_dokumen'=>$val->nama_zona,
                                                                                'status !='=>"selesai",

                                                                            ));



                                                                            $persentase_total_belum_selesai = ($total_dokumen_per_zona_belum_selesai->num_rows() * 100) / ($total_dokumen_per_zona_selesai->num_rows() + $total_dokumen_per_zona_belum_selesai->num_rows()) ;
                                                                            $persentase_total_belum_selesai_formatted = number_format($persentase_total_belum_selesai,2,",","");
                                                                            echo $total_dokumen_per_zona_belum_selesai->num_rows() ." DOKUMEN"." (".$persentase_total_belum_selesai_formatted."%)";
                                                                            ?>
                                                                        </div>
                                                                        <div class="progress">


                                                                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                                                                 aria-valuenow="<?php echo $persentase_total_belum_selesai;  ?>"
                                                                                 aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $persentase_total_belum_selesai; ?>%">
                                                                                <span class="sr-only"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $isFirst = false;
                            }
                            //die;
                        ?>

                    </div>
                </div>





            </div>

        </div>

	</div>






</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script>
    var pemda_id = <?php echo json_encode($pemda_id);  ?>;
    console.log(pemda_id);

    const realisasi_harmonisasi_total = <?php echo json_encode($realisasi_harmonisasi_total); ?>;
    console.log(realisasi_harmonisasi_total);

    const selesai_only = <?php echo json_encode($selesai_only); ?>;
    console.log(selesai_only);

    const belum_selesai_only = <?php echo json_encode($belum_selesai_only); ?>;
    console.log(belum_selesai_only);

    const array_daerah = <?php echo json_encode($array_daerah); ?>;
    //console.log(array_daerah);

    const total = <?php echo json_encode($total); ?>;
    //console.log("total : " + total);

    pemda_id.forEach(myFunction);

    function myFunction(value, key) {
        //console.log(key);
        var kode_pemda = key;
        //console.log(realisasi_harmonisasi_total[key]);

        var options = {
            tooltips : {
            enabled:true
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        console.log(ctx);
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        //console.log(ctx.chart.data);
                        dataArr.map(data => {
                            sum += data;
                        });

                        //sum = realisasi_satker_total[kode_satker] + sisa_satker_aktual[kode_satker];
                    }
                }
            }
        };
        //console.log(kode_pemda);


        var ctx = document.getElementById('chart_penyerapan' + value).getContext('2d');
        console.log(value);

        var chart_penyerapan = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Harmonisasi Selesai', 'Harmonisasi Belum Selesai'],
                datasets: [{
                    /*cuky*/
                    data: [selesai_only[key],belum_selesai_only[key]],
                    backgroundColor: [
                        'rgba(0, 172, 172, 1)',
                        'rgba(234, 66, 114, 1)'
                    ],
                    borderColor: [
                        'rgba(45, 53, 60, 1)',
                        'rgba(45, 53, 60, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: options
        });

    }
</script>

<script>

    var zona_daerah_list = <?php echo json_encode($zona_daerah_list_ii);  ?>;
    var nama_zona_daerah = [];
    var persen_realisasi = [];

    //console.log(zona_daerah_list);
    /*didapat dari tbl_zona*/
    zona_daerah_list.forEach(fungsi);
    function fungsi(val, key) {
        //console.log(key);
                nama_zona_daerah[key] = val;
                let realisasi = realisasi_harmonisasi_total[key];
                //console.log(realisasi_harmonisasi_total[val.id_zona]);
              // console.log(realisasi_harmonisasi_total);
                //console.log("jumlah upload : " + realisasi);
                //console.log(val);
                //console.log(key);
                //let sisa = sisa_harmonisasi_aktual[val.id];

                persen_realisasi[key] = (Math.round(((realisasi / total) * 100) * 100) / 100).toFixed(2)
                //console.log("persen realisasi: " + persen_realisasi[key-1]);

    }

    //console.log(persen_realisasi);
    // console.log(realisasi_harmonisasi_total);


    const labels = nama_zona_daerah;
    const ctx = document.getElementById('bar_chart_zona_daerah');
    const myChart = new Chart(ctx,{
        type : 'bar',
        data : {
            labels : nama_zona_daerah,
            datasets : [{
                label: 'Presentase Produk Hukum Daerah Diharmonisasi (%)',
                data: persen_realisasi,//[100,2,4,5,6,7,8,9,10,11,12,13,14,5.5,16,17,18,19,20,21,22,23,24,25], //[100.0,75.6,87.8,100.0,91.6,84.9,74.4,86.2,71.7,86.8,83.0,78.5,75.9,85.5,91.6,89.5,94.9,84.0,64.7,90.3,67.9,90.2,80.8,88.4,92.3]
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }],
        },
        options: {
            legend: {
                labels: {
                    fontColor: 'white'
                }
            },
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        fontColor: 'white'
                    }
                }],
                xAxes: [{
                    ticks: {
                        autoSkip: false,
                        maxRotation: 90,
                        minRotation: 90,
                        padding: 20,
                        fontColor: 'white'
                    }
                }]
            }
            ,plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: (value, ctx) => {
                        return value;
                    },
                    color: 'cyan',
                }
            }
        }
    });
</script>
<!-- end #content -->
