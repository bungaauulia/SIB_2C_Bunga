<?php
/**
 * FILE: views/tenure_stats.php
 * FUNGSI: Menampilkan statistik masa kerja karyawan dari MATERIALIZED VIEW
 */
include 'views/header.php';
?>

<h2>Statistik Masa Kerja Karyawan</h2>

<p style="margin-bottom: 2rem; color: #666;">
 Data berikut diambil dari <code>MATERIALIZED VIEW tenure_stats_mv</code> di database PostgreSQL.
</p>

<?php if ($stats && $stats->rowCount() > 0): ?>
 <div class="dashboard-cards">
 <?php
   // Ambil semua data dari materialized view
   $stats->execute();
   $all_stats = $stats->fetchAll(PDO::FETCH_ASSOC);

   // Hitung total karyawan dari semua kategori
   $total_employees = array_sum(array_column($all_stats, 'total_employees'));
 ?>

 <div class="card">
   <h3>Total Karyawan</h3>
   <div class="number"><?php echo $total_employees; ?></div>
 </div>

 <div class="card">
   <h3>Total Kategori</h3>
   <div class="number"><?php echo count($all_stats); ?></div>
 </div>

 <div class="card">
   <h3>Kategori Terbanyak</h3>
   <div class="number">
     <?php
     usort($all_stats, fn($a, $b) => $b['total_employees'] <=> $a['total_employees']);
     echo htmlspecialchars($all_stats[0]['tenure_level']);
     ?>
   </div>
 </div>
 </div>

 <table class="data-table">
 <thead>
   <tr>
     <th>Tingkat Masa Kerja</th>
     <th>Jumlah Karyawan</th>
     <th>Nama Karyawan</th>
   </tr>
 </thead>
 <tbody>
   <?php foreach ($all_stats as $row): ?>
   <tr>
     <td><strong><?php echo htmlspecialchars($row['tenure_level']); ?></strong></td>
     <td style="text-align:center;">
       <span style="padding: 0.25rem 0.75rem; background: #667eea; color: white; border-radius: 20px;">
         <?php echo $row['total_employees']; ?>
       </span>
     </td>
     <td><?php echo htmlspecialchars($row['employee_names']); ?></td>
   </tr>
   <?php endforeach; ?>
 </tbody>
 </table>

 <div style="margin-top: 3rem;">
   <h3>Visualisasi Data</h3>

   <div style="background: white; padding: 1.5rem; border-radius: 8px; margin: 1rem 0; border-left: 4px solid #8f0f44;">
     <h4>Jumlah Karyawan per Tingkat Masa Kerja</h4>
     <?php foreach ($all_stats as $row): ?>
     <div style="margin: 0.5rem 0;">
       <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
         <span><?php echo htmlspecialchars($row['tenure_level']); ?></span>
         <span><?php echo $row['total_employees']; ?> orang</span>
       </div>
       <div style="background: #f0f0f0; border-radius: 4px; height: 20px;">
         <div style="background: #667eea; height: 100%; border-radius: 4px; width: <?php echo ($row['total_employees'] / max(array_column($all_stats, 'total_employees')) * 100); ?>%;"></div>
       </div>
     </div>
     <?php endforeach; ?>
   </div>
 </div>

<?php else: ?>
 <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
   <p style="font-size: 1.2rem; color: #666eea;">Tidak ada data masa kerja.</p>
   <p style="color: #999;">Pastikan materialized view <code>tenure_stats_mv</code> sudah dibuat dan di-refresh.</p>
   <a href="index.php?action=create" class="btn btn-primary" style="margin-top: 1rem;">Tambah Data Karyawan</a>
 </div>
<?php endif; ?>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
 <strong>Informasi:</strong> 
 Data ini diambil dari materialized view PostgreSQL yang menggunakan 
 fungsi <code>COUNT()</code>, <code>CASE WHEN</code>, dan <code>GROUP BY</code>.
</div>

<?php include 'views/footer.php'; ?>
