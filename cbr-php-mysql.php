<?php
include("../koneksi.php");
// aku mencintaimu dengan sepenuh hati, hati yang tak bernyali VP
?>

<html >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Sistem Pakar Metode CBR (Case Based Reasoning)</title>
        <style type="text/css">
            <!--
            body,td,th {
                font-family: Georgia, Times New Roman, Times, serif;
                font-size: 13px;
                color: #333333;
            }
            .style1 {
                color: #000099;
                font-size: 24px;
            }
            a:link {
                text-decoration: none;
                color: #333333;
            }
            a:visited {
                text-decoration: none;
                color: #333333;
            }
            a:hover {
                text-decoration: underline;
                color: #FF0000;
            }
            a:active {
                text-decoration: none;
                color: #333333;
            }
            .style2 {font-weight: bold}
            -->
        </style></head>

    <body>
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000099">
            <tr>
                <td height="50" bgcolor="#FFFFFF"><span class="style1">Sistem Pakar Metode CBR (Case Based Reasoning)</span></td>
            </tr>
            <tr>
                <td height="35" bgcolor="#FFFFFF"><span class="style2"><a href="index.php">Home</a> | <a href="cbr-php-mysql.php">Konsultasi Pakar Metode Case Based Reasoning</a> | <a href="login.php">Login</a></span></td>
            </tr>
            <tr>
                <td align="center" valign="top" bgcolor="#FFFFFF"><br />
                    <strong>Analisa Menggunakan Sistem Pakar Metode CBR (Case Based Reasoning)</strong><br />
                    <br />
                    <?php
                    if (!isset($_POST['button'])) {
                        ?>
                        <form name="form1" method="post" action="">
                            <br>
                            <table align="center" width="600" border="1" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td id="ignore" bgcolor="#DBEAF5" width="300"><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><font size="2">GEJALA</font> </font></strong></div></td>
                                    <?php
                                    $q = mysql_query("select * from gejala ORDER BY id_gejala"); // tampilkan semua gejala
                                    while ($r = mysql_fetch_array($q)) {
                                        ?>        
                                    <tr>
                                        <td width="600"> 
                                            <input id="gejala<?php echo $r['id_gejala']; ?>" name="gejala<?php echo $r['id_gejala']; ?>" type="checkbox" value="true">
                                            <?php echo $r['nama_gejala']; ?> &nbsp;  <strong><?php echo $r['bobot']; ?></strong><br/>                
                                        </td>
                                    </tr>
                                <?php } ?>	
                                <tr>
                                    <td><input type="submit" name="button" value="Proses"></td>
                                </tr>
                            </table>
                            <br>
                        </form>
                        <?php
                    } else {
                        ?>
                        <br/><br/> <strong>---------------------------------------------------------------------------------------------------------------------</strong>   <br/><br/>
                        <div id="perhitungan" style="display:none;">
                            <br/><br/>Database Baisi Kasus :<br/><br/>
                            <table width="700" border="0" cellpadding="5" cellspacing="1" bgcolor="#000099">
                                <tr>
                                    <td width="200" bgcolor="#FFFFFF">Nomor Kasus</td>
                                    <td width="250" bgcolor="#FFFFFF">Nama Penyakit</td>    
                                    <td width="250" bgcolor="#FFFFFF">Nama Gejala</td>
                                    <td width="250" bgcolor="#FFFFFF">Bobot</td>
                                </tr>
                                <?php
                                // ---------------------------------- Mulai dari sini --------------------------------------------------------------

                                $querybasiskasus = mysql_query("SELECT * FROM basis_kasus ORDER BY no_kasus, id_penyakit, id_gejala");
                                $no_kasus = "";



                                while ($databasiskasus = mysql_fetch_array($querybasiskasus)) {
                                    $querypenyakit = mysql_query("SELECT * FROM penyakit WHERE id_penyakit = '$databasiskasus[id_penyakit]'");
                                    $datapenyakit = mysql_fetch_array($querypenyakit);
                                    $querygejala = mysql_query("SELECT * FROM gejala WHERE id_gejala = '$databasiskasus[id_gejala]'");
                                    $datagejala = mysql_fetch_array($querygejala);

                                    $databobot = mysql_query("SELECT * FROM basis_kasus WHERE id_gejala = '$databasiskasus[id_gejala]'");
                                    $dabobot = mysql_fetch_array($databobot);


                                    $ka = mysql_query("SELECT no_kasus, SUM(bobot) AS total FROM basis_kasus GROUP BY no_kasus");


                                    // $hitung = mysql_query("SELECT g.nama_gejala , sum(bk.bobot) FROM gejala g, basis_kasus bk WHERE g.id_gejala = '$databasiskasus[id_gejala]' GROUP BY g.nama_gejala");
                                    $hasilhitung = mysql_fetch_array($ka);
                                    ?>

                                    <?php
                                    // -----------------------------------------------------------
                                    // $qry_jumlah_b = mysql_query("SELECT SUM( g.bobot )FROM basis_kasus b, gejala g WHERE b.id_gejala = g.id_gejala GROUP BY b.no_kasus");
                                    $data_b = mysql_fetch_array($ka);
                                    ?>


                                    <tr>
                                        <?php
                                        // cocokan disini 
                                        if ($no_kasus != $databasiskasus['no_kasus']) {
                                            $queryjumlah = mysql_query("SELECT * FROM basis_kasus WHERE no_kasus = '$databasiskasus[no_kasus]'");
                                            $jumlahbaris = mysql_num_rows($queryjumlah);
                                            ?>
                                            <td bgcolor="#FFFFFF" rowspan="<?php echo $jumlahbaris; ?>"> <?php echo $databasiskasus['no_kasus']; ?></td>
                                            <td bgcolor="#FFFFFF" rowspan="<?php echo $jumlahbaris; ?> "> <?php echo $datapenyakit['nama_penyakit']; ?></td>
                                            <?php
                                        }
                                        ?>
                                        <td bgcolor="#FFFFFF"><?php echo $datagejala['nama_gejala']; ?></td>
                                        <td bgcolor="#FFFFFF"><?php echo $dabobot['bobot']; ?></td>
                                        <?php
                                        // $nilai[] = $datahitung['bobot'];
                                        //$totalnya = array_sum($nilai);
                                        ?>

                                    </tr>
                                    <?php
                                    $no_kasus = $databasiskasus['no_kasus'];
                                }
                                ?>
                            </table>


                            <?php // --------------------------------------------------------------------------------------------------------------------- ?>
                            <br/><br/> <strong>---------------------------------------------------------------------------------------------------------------------</strong>   <br/><br/>
                            <br/><br/> <strong>   Gejala Dipilih : </strong>   <br/><br/>
                            <table width="300" border="0" cellpadding="5" cellspacing="1" bgcolor="#000099">
                                <?php
                                $querygejala = mysql_query("SELECT * FROM gejala ORDER BY id_gejala ASC");
                                while ($datagejala = mysql_fetch_array($querygejala)) {
                                    if (@$_POST['gejala' . $datagejala['id_gejala']] == 'true') {
                                        ?>        
                                        <tr>
                                            <td bgcolor="#FFFFFF"> 
                                                <?php echo $datagejala['nama_gejala']; ?>

                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }


                                //------------------------------------------------------------------------------------------------------------------------
                                ?>
                            </table> 

                            <br/><br/> <strong>---------------------------------------------------------------------------------------------------------------------</strong>   <br/><br/>


                            <br/><br/>Perhitungan :<br/><br/>
                            <?php
                            echo "<table width=\"700\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" >";

                            echo "<td>No Kasus</td>";
                            echo "<td>Gejala Cocok</td>";
                            echo "<td>Bobot Cocok</td>";
                            echo "<td><strong>Total Bobot</strong></td>";
                            echo "<td> Jml Gejala Kasus</td>";
                            echo "<td bgcolor=\"#FFFFFF\">Jml Gejala Dipilih</td>";
                            echo "<td>Pembagi</td>";
                            echo "<td>Nilai Hasil</td>";

                            echo "</tr>";

                            $no_kasus_hasil = array();
                            $id_penyakit_hasil = array();
                            $nama_penyakit_hasil = array();
                            $nilai_hasil = array();

                            $i = 0;
                            $querykasus = mysql_query("SELECT no_kasus, SUM( bobot ) AS total FROM basis_kasus GROUP BY no_kasus ORDER BY no_kasus ASC");

                         //   $ini = mysql_query("SELECT no_kasus, SUM( bobot ) AS total FROM basis_kasus GROUP BY no_kasus");
                           // $hitungbobotbe = mysql_fetch_array($ini);

                            while ($datakasus = mysql_fetch_array($querykasus)) {
                                $no_kasus_hasil[$i] = $datakasus['no_kasus'];
                                $jml_gejala_dipilih = 0;
                                $jml_gejala_kasus = 0;
                                $jml_gejala_cocok = 0;
                                $bobotcocok = 0;

                                $querygejala = mysql_query("SELECT * FROM gejala ORDER BY id_gejala ASC");
                                while ($datagejala = mysql_fetch_array($querygejala)) {
                                    if (@$_POST['gejala' . $datagejala['id_gejala']] == 'true') {

                                        $jml_gejala_dipilih++;

                                        // SELECT bk.no_kasus, sum(g.bobot) FROM basis_kasus bk, gejala g WHERE bk.no_kasus = '2' and g.id_gejala = bk.id_gejala
                                        //$querybasiskasus = mysql_query("SELECT * , sum(g.bobot) FROM basis_kasus WHERE no_kasus = '$datakasus[no_kasus]'");
                                        $menghitungbobot = mysql_query("SELECT bk.no_kasus, sum(g.bobot) as total FROM basis_kasus bk, gejala g WHERE bk.no_kasus = '$datakasus[no_kasus]' and g.id_gejala = bk.id_gejala");
                                  //      $totalhasil = mysql_fetch_array($ka);
                                        $querybasiskasus = mysql_query("SELECT * FROM basis_kasus bk, gejala g WHERE bk.no_kasus = '$datakasus[no_kasus]' and g.id_gejala = bk.id_gejala");
                                        $jml_gejala_kasus = 0;
                                        while ($databasiskasus = mysql_fetch_array($querybasiskasus)) {
                                            $jml_gejala_kasus++;
                                            // apabila id gejala sama dengan yang dipilih
                                            if ($datagejala['id_gejala'] == $databasiskasus['id_gejala']) {
                                                // ambil bobot untuk gejala tersebut


                                                $jml_gejala_cocok++;
                                            }
                                        }
                                    }
                                }


                                $pembagi = max($jml_gejala_dipilih, $jml_gejala_kasus);
                                if ($pembagi == 0) {
                                    $hasil = 0;
                                } else {
                                    $hasil = $jml_gejala_cocok / $datakasus['total'];
                                }
                                $nilai_hasil[$i] = $hasil;

                                $id_penyakit_hasil[$i] = "";

                                // inisialkan nama penyakit 
                                $nama_penyakit_hasil[$i] = "";


                                $querypenyakit = mysql_query("SELECT penyakit.* FROM basis_kasus LEFT JOIN penyakit ON basis_kasus.id_penyakit = penyakit.id_penyakit WHERE no_kasus = '$datakasus[no_kasus]'");
                                if ($datapenyakit = mysql_fetch_array($querypenyakit)) {
                                    $id_penyakit_hasil[$i] = $datapenyakit['id_penyakit'];
                                    $nama_penyakit_hasil[$i] = $datapenyakit['nama_penyakit'];
                                }

                                //echo $datakasus['no_kasus']." ".$namapenyakit." = ".$jml_gejala_cocok." / ".$pembagi." = ".$hasil."<br/>";
                                echo "<td bgcolor=\"#FFFFFF\"><strong>" . $datakasus['no_kasus'] . "</strong></td>";
                                echo "<td bgcolor=\"#FFFFFF\"><strong>" . $jml_gejala_cocok . " gejala</strong></td>";
                                // lakukan pembobotan disini bukan menghitun gejala
                                echo "<td bgcolor=\"#FFFFFF\">" . $bobotcocok . "</td>";
                                echo "<td bgcolor=\"#FFFFFF\"><strong>" . $datakasus['total'] . "</strong></td>";
                                echo "<td bgcolor=\"#FFFFFF\"><strong>" . $jml_gejala_kasus . "</strong></td>"; // hitung total bobot
                                echo "<td bgcolor=\"#FFFFFF\"><strong>" . $jml_gejala_dipilih . "</strong></td>";
                                echo "<td bgcolor=\"#FFFFFF\"><strong>" . $datakasus['total'] . "</strong></td>";
                                echo "<td bgcolor=\"#FFFFFF\">" . $jml_gejala_cocok . " /<strong>" . $datakasus['total'] . "</strong> = " . $hasil . "</td>";
                                echo "</tr>";

                                $i++;
                            }
                            echo "</table>";

                            for ($i = 0; $i < count($id_penyakit_hasil); $i++) {
                                for ($j = $i + 1; $j < count($id_penyakit_hasil); $j++) {
                                    if ($nilai_hasil[$j] > $nilai_hasil[$i]) {
                                        $tmp_no_kasus = $no_kasus_hasil[$i];
                                        $no_kasus_hasil[$i] = $no_kasus_hasil[$j];
                                        $no_kasus_hasil[$j] = $tmp_no_kasus;

                                        $tmp_id_penyakit = $id_penyakit_hasil[$i];
                                        $id_penyakit_hasil[$i] = $id_penyakit_hasil[$j];
                                        $id_penyakit_hasil[$j] = $tmp_id_penyakit;

                                        // ambil nama penyakit  -----------------------------------------
                                        $tmp_nama_penyakit = $nama_penyakit_hasil[$i];
                                        $nama_penyakit_hasil[$i] = $nama_penyakit_hasil[$j];
                                        $nama_penyakit_hasil[$j] = $tmp_nama_penyakit;



                                        $tmp_nilai = $nilai_hasil[$i];
                                        $nilai_hasil[$i] = $nilai_hasil[$j];
                                        $nilai_hasil[$j] = $tmp_nilai;
                                    }
                                }
                            }


                            echo "<br/>Penyakit Terbesar = " . $id_penyakit_hasil[0] . "." . $nama_penyakit_hasil[0] . " pada Kasus Nomor " . $no_kasus_hasil[0] . ", dengan Nilai Persentase Terbesar " . round($nilai_hasil[0] * 100, 2) . "<br/>";

                            //for ($i = 0; $i < count($daftar_penyakit); $i++)
                            //{
                            //	echo $daftar_penyakit[$i]."=".$daftar_cf[$i]."<br/>";
                            // algoritmanya disini --------------------------------------------------------------------------------------------}
                            ?>





                        </div>

                        <br/><br/> <strong>---------------------------------------------------------------------------------------------------------------------</strong>   <br/><br/>

                        <br />
                        <input type="button" value="Perhitungan" onclick="document.getElementById('perhitungan').style.display = 'block';"/>
                        <br />
                        <br />
                        <table width="500" border="0" cellspacing="1" cellpadding="3" >
                            <tr>
                                <td>Ranking</td>
                                <td>Kasus</td>
                                <td>Nama Penyakit</td>
                                <td>Nilai Hasil</td>
                            </tr>
                            <?php
                            for ($i = 0; $i < count($id_penyakit_hasil); $i++) {
                                ?>
                                <tr>
                                    <td bgcolor="#FFFFFF"><?php echo ($i + 1); ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $no_kasus_hasil[$i]; ?></td>

                                    <td bgcolor="#FFFFFF"><?php echo $nama_penyakit_hasil[$i]; ?></td> 

                                    <td bgcolor="#FFFFFF"><?php echo round($nilai_hasil[$i] * 100, 2); ?> %</td>
                                </tr>
                                <?php
                            }
                            ?>                                     
                        </table>


                        <br />
                        <br />
                        <?php echo $tampung; ?>  Penyakit Terpilih = <?php echo $nama_penyakit_hasil[0]; ?> pada Kasus Nomor <?php echo $no_kasus_hasil[0]; ?>, dengan Nilai Persentase Terbesar = <?php echo round($nilai_hasil[0] * 100, 2); ?> %
                        <br />
                        <br />	
                        <?php
                    }
                    ?>
                </td>
            </tr>

        </table>
    </body>
</html>
