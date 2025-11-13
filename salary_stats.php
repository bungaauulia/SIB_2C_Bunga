<?php
/**
 * FILE: views/salary_stats.php
 * FUNGSI: Menampilkan statistik gaji per departemen dari VIEW salary_stats
 */
include 'views/header.php';
?>

<h2>Statistik Gaji per Departemen</h2>

<p style="margin-bottom: 2rem; color: #666;">
 Data berikut diambil dari VIEW <code>salary_stats</code> di database PostgreSQL.
</p>

<?php if ($stats->rowCount() > 0): ?>
 <?php $all_stats = $stats->fetchAll(PDO::FETCH_ASSOC); ?>

 <div class="dashboard-cards">
 <?php
 $total_avg = array_sum(array_column($all_stats, 'avg_salary')) / count($all_stats);
 $max_salary = max(array_column($all_stats, 'max_salary'));
 $min_salary = min(array_column($all_stats, 'min_salary'));
 ?>
 <div class="card"><h3>Rata-rata Gaji Global</h3><div class="number">Rp <?= number_format($total_avg, 0, ',', '.') ?></div></div>
 <div class="card"><h3>Gaji Tertinggi</h3><div class="number">Rp <?= number_format($max_salary, 0, ',', '.') ?></div></div>
 <div class="card"><h3>Gaji Terendah</h3><div class="number">Rp <?= number_format($min_salary, 0, ',', '.') ?></div></div>
 </div>

 <table class="data-table">
 <thead>
   <tr><th>Departemen</th><th>Rata-rata</th><th>Minimum</th><th>Maksimum</th></tr>
 </thead>
 <tbody>
   <?php foreach ($all_stats as $row): ?>
   <tr>
     <td><strong><?= htmlspecialchars($row['department']) ?></strong></td>
     <td>Rp <?= number_format($row['avg_salary'], 0, ',', '.') ?></td>
     <td>Rp <?= number_format($row['min_salary'], 0, ',', '.') ?></td>
     <td>Rp <?= number_format($row['max_salary'], 0, ',', '.') ?></td>
   </tr>
   <?php endforeach; ?>
 </tbody>
 </table>

<?php else: ?>
 <div style="text-align:center;padding:3rem;background:#f8f9fa;border-radius:8px;">
   <p style="font-size:1.2rem;color:#666;">Tidak ada data gaji.</p>
 </div>
<?php endif; ?>

<?php include 'views/footer.php'; ?>
