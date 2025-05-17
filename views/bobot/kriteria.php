<style>
    .kriteria-container {
        background-color: #f0f0f0; 
        padding: 10px; 
        border: 1px solid #ccc; 
        max-width: 600px; 
        margin: 0 auto;
        box-sizing: border-box;
    }
    
    .kriteria-header {
        background-color: #f0f0f0; 
        border-bottom: 1px solid #ccc; 
        padding: 5px;
    }
    
    .kriteria-content {
        padding: 10px;
    }
    
    .kriteria-table {
        width: 100%; 
        border-collapse: collapse;
    }
    
    .kriteria-table th, .kriteria-table td {
        padding: 5px;
        text-align: center;
    }
    
    .kriteria-table th:first-child {
        text-align: left;
        width: 60px;
    }
    
    .kriteria-input {
        width: 50px; 
        text-align: center; 
        border: 1px solid #ccc;
    }
    
    .simpan-btn {
        width: 100%; 
        background-color: #f0f0f0; 
        border: 1px solid #ccc; 
        padding: 8px 0; 
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }
    
    .simpan-btn:hover {
        background-color: #007bff;
        color: white;
    }
    
    .hasil-table {
        width: 100%; 
        border-collapse: collapse; 
        border: 1px solid #ddd;
    }
    
    .hasil-table th, .hasil-table td {
        padding: 8px; 
        border: 1px solid #ddd;
    }
    
    .hasil-table th {
        background-color: #f5f5f5;
    }
    
    /* Responsif untuk layar kecil */
    @media (max-width: 600px) {
        .kriteria-container {
            width: 100%;
            padding: 5px;
        }
        
        .kriteria-input {
            width: 40px;
            font-size: 14px;
        }
        
        .kriteria-table th, .kriteria-table td {
            padding: 3px;
            font-size: 14px;
        }
        
        .hasil-table th, .hasil-table td {
            padding: 5px;
            font-size: 14px;
        }
    }
    
    /* Responsif untuk layar sangat kecil */
    @media (max-width: 400px) {
        .kriteria-input {
            width: 30px;
            font-size: 12px;
        }
        
        .kriteria-table th, .kriteria-table td {
            padding: 2px;
            font-size: 12px;
        }
    }
</style>

<div class="kriteria-container">
    <div class="kriteria-header">
        <h5 style="margin: 0;">Kriteria Dokumen</h5>
    </div>
    <div class="kriteria-content">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/spk_ahp/bobot/kriteria">
            <table class="kriteria-table">
                <tr>
                    <th>Bobot</th>
                    <?php foreach ($namaKriteria as $kriteria): ?>
                        <th><?= $kriteria ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                    <tr>
                        <th><?= $namaKriteria[$i] ?></th>
                        <?php for ($j = 0; $j < count($namaKriteria); $j++): ?>
                            <td>
                                <!-- Cari dan ubah baris berikut (sekitar baris 130-145) -->
                                <?php if ($i == $j): ?>
                                    <input type="text" name="matriks[<?= $i ?>][<?= $j ?>]" value="1.0" readonly class="kriteria-input">
                                <?php elseif ($i > $j && !($i == 4 && $j == 3)): ?>
                                    <input type="text" name="matriks[<?= $i ?>][<?= $j ?>]" value="<?= number_format($matriksKriteria[$i][$j], 2) ?>" readonly class="kriteria-input">
                                <?php elseif ($i == 4 && $j == 3): ?>
                                    <input type="number" step="0.01" min="0.01" name="matriks[<?= $i ?>][<?= $j ?>]" value="<?= number_format($matriksKriteria[$i][$j], 2) ?>" required class="kriteria-input">
                                <?php else: ?>
                                    <input type="number" step="0.01" min="0.01" name="matriks[<?= $i ?>][<?= $j ?>]" value="<?= number_format($matriksKriteria[$i][$j], 2) ?>" required class="kriteria-input">
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="simpan-btn">SIMPAN</button>
            </div>
        </form>
        
        <?php if (isset($konsisten)): ?>
            <div style="margin-top: 20px;">
                <div class="<?= $konsisten ? 'alert alert-success' : 'alert alert-danger' ?>">
                    <h5>Hasil Perhitungan:</h5>
                    <p>Consistency Ratio (CR): <?= number_format($CR, 4) ?></p>
                    <p>Matriks <?= $konsisten ? 'KONSISTEN' : 'TIDAK KONSISTEN' ?></p>
                    <?php if (!$konsisten): ?>
                        <p>Nilai CR harus <= 0.1 agar matriks dianggap konsisten.</p>
                    <?php endif; ?>
                </div>
                
                <h5 style="margin-top: 15px;">Bobot Prioritas:</h5>
                <table class="hasil-table">
                    <tr>
                        <th>Kriteria</th>
                        <th>Bobot</th>
                        <th>Persentase</th>
                    </tr>
                    <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                        <tr>
                            <td><?= $namaKriteria[$i] ?></td>
                            <td><?= number_format($bobotPrioritas[$i], 4) ?></td>
                            <td><?= number_format($bobotPrioritas[$i] * 100, 2) ?>%</td>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>