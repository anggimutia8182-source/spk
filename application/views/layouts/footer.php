    <?php if (isset($user) && $user): ?>
        </main>
    </div> <!-- End .app-wrapper -->
    <?php endif; ?>

    <footer class="no-print" style="background: #0b1120; border-top: 1px solid rgba(255,255,255,0.08); color: #94a3b8; padding: 1.75rem 1.5rem; text-align: center; font-size: 0.85rem; margin-top: auto;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <strong style="color: #f1f5f9;">SomnoExpert SPK</strong> &copy; <?= date('Y'); ?> &mdash; Sistem Pakar Identifikasi Jenis Gangguan Tidur Menggunakan Metode Forward Chaining.
            </div>
            <div style="display: flex; gap: 1.5rem;">
                <span style="color: #64748b;"><i class="fa-solid fa-code"></i> CodeIgniter 3 & MySQL</span>
                <span style="color: #64748b;"><i class="fa-solid fa-shield-halved"></i> Role: <?= (isset($user) && $user) ? ucfirst($user->role) : 'Guest'; ?></span>
            </div>
        </div>
    </footer>

    <!-- Custom Scripts -->
    <script src="<?= base_url('assets/js/main.js'); ?>"></script>
</body>
</html>
