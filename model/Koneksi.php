<?php

class koneksi {

    private $conn;

    public function __construct() {

        // ---- BACA ENV RAILWAY ----
        $host = getenv("MYSQLHOST");
        $user = getenv("MYSQLUSER");
        $pass = getenv("MYSQLPASSWORD");
        $db   = getenv("MYSQLDATABASE");
        $port = getenv("MYSQLPORT");

        // ---- DEBUG jika ENV tidak terbaca ----
        if (!$host || !$user || !$pass || !$db || !$port) {
            die("
                <h3>ENV Railway TIDAK TERBACA ❌</h3>
                MYSQLHOST = $host <br>
                MYSQLUSER = $user <br>
                MYSQLPASSWORD = " . ($pass ? "[ADA]" : "[KOSONG]") . " <br>
                MYSQLDATABASE = $db <br>
                MYSQLPORT = $port <br>
            ");
        }

        // ---- CAST PORT ----
        $port = (int)$port;

        // ---- KONEKSI ----
        $this->conn = new mysqli($host, $user, $pass, $db, $port);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function getConnection() {
        return $this->conn;
    }
}

$koneksi = new koneksi();
?>
