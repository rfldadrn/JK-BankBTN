        </main>
    </div>
</div>
<script>
// Confirm delete
function confirmDelete(url) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = 'csrf_token';
        csrf.value = '<?= Helper::csrfToken() ?>';
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
</body>
</html>
