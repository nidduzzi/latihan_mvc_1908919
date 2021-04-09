<?php

/******************************************
PRAKTIKUM RPL
******************************************/

include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Task.class.php");

// Membuat objek dari kelas task
$otask = new Task($db_host, $db_user, $db_password, $db_name);
$otask->open();


$warning = "";
// add form data to database
if ((!empty($_POST['tname']) || isset($_POST['tname'])) &&
    (!empty($_POST['tdeadline']) || isset($_POST['tdeadline'])) &&
    (!empty($_POST['tdetails']) || isset($_POST['tdetails'])) &&
    (!empty($_POST['tsubject']) || isset($_POST['tsubject'])) &&
    (!empty($_POST['tpriority']) || isset($_POST['tpriority']))) {
        $query = "INSERT INTO tb_to_do (name_td, details_td, subject_td, priority_td, deadline_td, status_td) VALUES('".$_POST['tname']."','".$_POST['tdetails']."','".$_POST['tsubject']."','".$_POST['tpriority']."','".$_POST['tdeadline']."','Belum')";
        $otask->execute($query);
}
elseif (isset($_POST['add'])) {
    $warning = '<div class="alert alert-warning" role="alert">Please fill in all required fields!</div>';
    unset($_POST['add']);
}

// delete data from database
if(!empty($_GET['id_hapus']) || isset($_GET['id_hapus'])){
    $query = "DELETE FROM tb_to_do WHERE id=".$_GET['id_hapus'];
    $otask->execute($query);
}

// update status
if(!empty($_GET['id_status']) || isset($_GET['id_status'])){
    $query = 'UPDATE tb_to_do SET status_td="Sudah" WHERE id='.$_GET['id_status'];
    $otask->execute($query);
}

if(isset($_GET['sort']) && !empty($_GET['sort'])){
    session_start();
    $_SESSION['sort'] = $_GET['sort'];
}

// Memanggil method getTask di kelas Task
$otask->getTask();

// Proses mengisi tabel dengan data
$data = null;
$no = 1;

while (list($id, $tname, $tdetails, $tsubject, $tpriority, $tdeadline, $tstatus) = $otask->getResult()) {
	// Tampilan jika status task nya sudah dikerjakan
	if($tstatus == "Sudah"){
		$data .= "<tr>
		<td>" . $no . "</td>
		<td>" . $tname . "</td>
		<td>" . $tdetails . "</td>
		<td>" . $tsubject . "</td>
		<td>" . $tpriority . "</td>
		<td>" . $tdeadline . "</td>
		<td>" . $tstatus . "</td>
		<td>
		<button class='btn btn-danger'><a href='index.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Hapus</a></button>
		</td>
		</tr>";
		$no++;
	}

	// Tampilan jika status task nya belum dikerjakan
	else{
		$data .= "<tr>
		<td>" . $no . "</td>
		<td>" . $tname . "</td>
		<td>" . $tdetails . "</td>
		<td>" . $tsubject . "</td>
		<td>" . $tpriority . "</td>
		<td>" . $tdeadline . "</td>
		<td>" . $tstatus . "</td>
		<td>
		<button class='btn btn-danger'><a href='index.php?id_hapus=" . $id . "' style='color: white; font-weight: bold;'>Hapus</a></button>
		<button class='btn btn-success' ><a href='index.php?id_status=" . $id .  "' style='color: white; font-weight: bold;'>Selesai</a></button>
		</td>
		</tr>";
		$no++;
	}
}

// Menutup koneksi database
$otask->close();

// Membaca template skin.html
$tpl = new Template("templates/skin.html");

$tpl->replace("DATA_WARNING", $warning);
// Mengganti kode Data_Tabel dengan data yang sudah diproses
$tpl->replace("DATA_TABEL", $data);

// Menampilkan ke layar
$tpl->write();