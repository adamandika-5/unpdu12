<footer class="footer">
  <div>© <?= date('Y') ?> <?= e(APP_NAME) ?> • Website Profil Desa</div>
</footer>

<script>
  const slides = document.querySelectorAll('.hero-slide');
  if (slides.length > 0) {
    let i = 0;
    setInterval(() => {
      slides[i].classList.remove('active');
      i = (i + 1) % slides.length;
      slides[i].classList.add('active');
    }, 5000);
  }
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const isDesktopHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  const dropdownLis = document.querySelectorAll('.navbar .dropdown');
  const detailsList = Array.from(dropdownLis)
    .map(li => li.querySelector('details'))
    .filter(Boolean);

  const closeAll = (except) => {
    detailsList.forEach(d => {
      if (d !== except) d.removeAttribute('open');
    });
  };

  if (isDesktopHover) {
    dropdownLis.forEach(li => {
      const d = li.querySelector('details');
      if (!d) return;

      li.addEventListener('mouseenter', () => {
        closeAll(d);
        d.setAttribute('open', '');
      });

      li.addEventListener('mouseleave', () => {
        d.removeAttribute('open');
      });
    });
  }

  detailsList.forEach(d => {
    d.addEventListener('toggle', () => {
      if (d.open) closeAll(d);
    });
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.navbar')) closeAll(null);
  });

  document.querySelectorAll('.navbar .dropdown-menu a').forEach(a => {
    a.addEventListener('click', () => closeAll(null));
  });
});
</script>

</body>
</html>
