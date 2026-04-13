/**
 * Volleyball Allianz – main.js
 */
(function () {
  'use strict';

  /* ── Tab-Filter auf der Übersichtsseite ── */
  const teamCards = document.querySelectorAll('.team-card[data-kategorie]');
  const tabBtn = document.querySelectorAll('.tab-btn');
  const initialFilter = 'damen';
  const initialBtn = document.querySelector(`.tab-btn[data-filter="${initialFilter}"]`);

  // Initial state
  teamCards.forEach(card => {
    const kat = (card.dataset.kategorie || '').toLowerCase();
    card.style.display = kat.includes(initialFilter) ? '' : 'none';
  });

  if (initialBtn) initialBtn.classList.add('active');

  tabBtn.forEach(btn => {
    btn.addEventListener('click', () => {
      tabBtn.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter.toLowerCase();

      teamCards.forEach(card => {
        const kat = (card.dataset.kategorie || '').toLowerCase();
        card.style.display = (filter === 'alle' || kat === filter) ? '' : 'none';
      });
    });
  });

  /* ── Sticky-Nav Schatten beim Scrollen ── */
  const header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      header.style.boxShadow = window.scrollY > 10
          ? '0 2px 20px rgba(13,59,110,0.12)'
          : '0 1px 12px rgba(13,59,110,0.06)';
    }, { passive: true });
  }

  /* ── Mobile Menu Toggle ── */
  document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const primaryMenu = document.getElementById('primary-menu');

    if (menuToggle && primaryMenu) {
      menuToggle.addEventListener('click', function () {
        primaryMenu.classList.toggle('active');
      });
    }
  });

})();