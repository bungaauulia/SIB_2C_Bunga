<?php
/**
 * FILE: views/employee_overview.php
 * FUNGSI: Menampilkan ringkasan umum data karyawan dari VIEW PostgreSQL
 */
include 'views/header.php';
?>

<h2>Ringkasan Data Karyawan</h2>

<p style="margin-bottom: 2rem; color: #666;">
 Data ini diambil dari VIEW <code>employee_overview_view</code> di database PostgreSQL.
</p>

<?php
$data = $stats->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($data): ?>
 <div class="dashboard-cards">
     <div class="card">
         <h3>Total Karyawan</h3>
         <div class="number"><?php echo $data['total_employees']; ?></div>
     </div>
     <div class="card">
         <h3>Total Gaji</h3>
         <div class="number">Rp <?php echo number_format($data['total_salary'], 0, ',', '.'); ?></div>
     </div>
     <div class="card">
         <h3>Rata-rata Masa Kerja</h3>
         <div class="number"><?php echo $data['avg_tenure_months']; ?> bulan</div>
     </div>
 </div>

 <!-- Tabel Ringkasan -->
 <table class="data-table" style="margin-top: 2rem;">
     <thead>
         <tr>
             <th>Statistik</th>
             <th>Nilai</th>
         </tr>
     </thead>
     <tbody>
         <tr>
             <td>Total Karyawan</td>
             <td><?php echo $data['total_employees']; ?></td>
         </tr>
         <tr>
             <td>Total Gaji</td>
             <td>Rp <?php echo number_format($data['total_salary'], 0, ',', '.'); ?></td>
         </tr>
         <tr>
             <td>Rata-rata Masa Kerja</td>
             <td><?php echo $data['avg_tenure_months']; ?> bulan</td>
         </tr>
     </tbody>
 </table>

<?php else: ?>
 <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
     <p style="font-size: 1.2rem; color: #666;">Tidak ada data overview karyawan.</p>
     <p style="color: #999;">Pastikan VIEW <code>employee_overview_view</code> sudah dibuat di database dan berisi data.</p>
     <a href="index.php?action=create" class="btn btn-primary" style="margin-top: 1rem;">Tambah Data Karyawan</a>
 </div>
<?php endif; ?>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
 <strong>Informasi:</strong> 
 Data ini diambil dari VIEW PostgreSQL dengan fungsi agregat 
 <code>COUNT()</code>, <code>SUM()</code>, dan perhitungan masa kerja menggunakan 
 <code>AGE()</code>.
</div>

<?php include 'views/footer.php'; ?>
