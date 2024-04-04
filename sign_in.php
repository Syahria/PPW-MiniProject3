<?php
session_start();

require "loginSystem/connect.php";

if (isset($_POST["signIn"])) {
    $jenis_login = $_POST["jenis_login"];

    if ($jenis_login === 'member') {
        $nama = strtolower($_POST["nama"]);
        $nisn = $_POST["nisn"];
        $password = $_POST["password_member"]; // Ubah menjadi $_POST["password_member"]

        // Validasi semua input diisi
        if (empty($nama) || empty($nisn) || empty($password)) {
            $error = true;
        } else {
            $result = mysqli_query($connect, "SELECT * FROM member WHERE nama = '$nama' AND nisn = '$nisn'");
            if (mysqli_num_rows($result) === 1) {
                $pw = mysqli_fetch_assoc($result);
                if (password_verify($password, $pw["password"])) {
                    $_SESSION["signIn"] = true;
                    $_SESSION["member"]["nama"] = $nama;
                    $_SESSION["member"]["nisn"] = $nisn;
                    header("Location: DashboardMember/dashboardMember.php");
                    exit;
                }
            }
            $error = true; // Jika tidak ada hasil dari query atau password tidak cocok
        }
    } elseif ($jenis_login === 'admin') {
        $nama_admin = strtolower($_POST["nama_admin"]);
        $password = $_POST["password_admin"]; // Ubah menjadi $_POST["password_admin"]

        // Validasi nama admin dan password diisi
        if (empty($nama_admin) || empty($password)) {
            $error = true;
        } else {
            $result = mysqli_query($connect, "SELECT * FROM admin WHERE nama_admin = '$nama_admin'");
            if (mysqli_num_rows($result) === 1) {
                $pw = mysqli_fetch_assoc($result);
                if (password_verify($password, $pw["password"])) {
                    $_SESSION["signIn"] = true;
                    $_SESSION["admin"]["nama_admin"] = $nama_admin;
                    header("Location: DashboardAdmin/dashboardAdmin.php");
                    exit;
                }
            }
            $error = true; // Jika tidak ada hasil dari query atau password tidak cocok
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="card p-2 mt-5">
            <div class="position-absolute top-0 start-50 translate-middle">
                <img src="assets/adminLogo.png" class="" alt="adminLogo" width="85px">
            </div>
            <h1 class="pt-5 text-center fw-bold">Sign In</h1>
            <hr>
            <form action="" method="post" class="row g-3 p-4 needs-validation" novalidate>
                <label for="jenis_login" class="form-label">Jenis Login</label>
                <div class="input-group mt-0">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-users"></i></span>
                    <select class="form-select" id="jenis_login" name="jenis_login" required>
                        <option selected disabled>Choose</option>
                        <option value="member">Member</option>
                        <option value="admin">Admin</option>
                    </select>
                    <div class="invalid-feedback">
                        Pilih jenis login!
                    </div>
                </div>
                <div id="memberLogin" style="display: none;">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <div class="input-group mt-0">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Member" required>
                        <div class="invalid-feedback">
                            Masukkan nama anda!
                        </div>
                    </div>
                    <label for="nisn" class="form-label">NISN</label>
                    <div class="input-group mt-0">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-hashtag"></i></span>
                        <input type="number" class="form-control" name="nisn" id="nisn" placeholder="NISN Member" required>
                        <div class="invalid-feedback">
                            Masukkan NISN anda!
                        </div>
                    </div>
                    <!-- Ubah name input password menjadi password_member -->
                    <label for="password_member" class="form-label">Password</label>
                    <div class="input-group mt-0">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" id="password_member" name="password_member" required>
                        <div class="invalid-feedback">
                            Masukkan Password anda!
                        </div>
                    </div>
                    <!-- Tambahkan link "Sign Up" hanya untuk anggota -->
                    <p class="mt-2">Don't have an account yet? <a href="sign_up.php" class="text-decoration-none text-primary">Sign Up</a></p>
                </div>
                <div id="adminLogin" style="display: none;">
                    <label for="nama_admin" class="form-label">Nama Lengkap</label>
                    <div class="input-group mt-0">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control" name="nama_admin" id="nama_admin" placeholder="Nama Admin" required>
                        <div class="invalid-feedback">
                            Masukkan nama admin anda!
                        </div>
                    </div>
                    <!-- Ubah name input password menjadi password_admin -->
                    <label for="password_admin" class="form-label">Password</label>
                    <div class="input-group mt-0">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" id="password_admin" name="password_admin" required>
                        <div class="invalid-feedback">
                            Masukkan Password anda!
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit" name="signIn">Sign In</button>
                </div>
            </form>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger mt-2" role="alert">Nama / NISN / Password tidak sesuai!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Script untuk menampilkan form berdasarkan jenis login yang dipilih
        (() => {
            'use strict';

            const jenisLogin = document.getElementById('jenis_login');
            const memberLogin = document.getElementById('memberLogin');
            const adminLogin = document.getElementById('adminLogin');

            jenisLogin.addEventListener('change', function() {
                if (jenisLogin.value === 'member') {
                    memberLogin.style.display = 'block';
                    adminLogin.style.display = 'none';
                } else if (jenisLogin.value === 'admin') {
                    memberLogin.style.display = 'none';
                    adminLogin.style.display = 'block';
                } else {
                    memberLogin.style.display = 'none';
                    adminLogin.style.display = 'none';
                }
            });

            const forms = document.querySelectorAll('.needs-validation');

            forms.forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>
