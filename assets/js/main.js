/**
 * Premium Persian Tourism - Core JavaScript Interactions
 * Completely upgraded with dynamic AJAX-based search & filter system (Priority 2).
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Mobile Side Drawer Navigation Panel (Refined with ARIA accessibility checks)
  var burgerBtn = document.querySelector('.burger-menu-btn');
  var mobileDrawer = document.getElementById('mobile-nav-drawer');
  var drawerOverlay = document.getElementById('drawer-bg-overlay');
  var drawerCloseBtn = document.querySelector('.drawer-close-btn');

  function openDrawer() {
    if (mobileDrawer && drawerOverlay) {
      mobileDrawer.classList.add('open');
      drawerOverlay.classList.add('open');
      mobileDrawer.setAttribute('aria-hidden', 'false');
      if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeDrawer() {
    if (mobileDrawer && drawerOverlay) {
      mobileDrawer.classList.remove('open');
      drawerOverlay.classList.remove('open');
      mobileDrawer.setAttribute('aria-hidden', 'true');
      if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }
  }

  if (burgerBtn) {
    burgerBtn.addEventListener('click', openDrawer);
  }
  if (drawerCloseBtn) {
    drawerCloseBtn.addEventListener('click', closeDrawer);
  }
  if (drawerOverlay) {
    drawerOverlay.addEventListener('click', closeDrawer);
  }

  // 2. Interactive Lazy Load Aparat embeds
  var lazyAparats = document.querySelectorAll('.ppt-aparat-lazy-embed');
  lazyAparats.forEach(function (embed) {
    embed.addEventListener('click', function () {
      var aparatId = embed.getAttribute('data-aparat-id');
      if (aparatId) {
        var iframe = document.createElement('iframe');
        iframe.setAttribute('src', 'https://www.aparat.com/video/video/embed/videohash/' + aparatId + '/vt/frame');
        iframe.setAttribute('allowFullScreen', 'true');
        iframe.setAttribute('webkitallowfullscreen', 'true');
        iframe.setAttribute('mozallowfullscreen', 'true');
        iframe.setAttribute('style', 'position: absolute; top:0; left:0; width:100%; height:100%; border:0;');

        embed.innerHTML = '';
        embed.appendChild(iframe);
      }
    });
  });

  // 3. Audio Player (Radio Safar) Integration
  var playButtons = document.querySelectorAll('.audio-control-btn');
  playButtons.forEach(function (btn) {
    var audioUrl = btn.getAttribute('data-audio-url');
    if (!audioUrl) return;

    var audio = new Audio(audioUrl);
    var isPlaying = false;
    var playerBox = btn.closest('.ppt-player-box');
    var progressBar = playerBox ? playerBox.querySelector('.audio-progress') : null;
    var timeDisplay = playerBox ? playerBox.querySelector('.audio-time') : null;
    var progressTrack = playerBox ? playerBox.querySelector('.audio-progress-bar') : null;

    btn.addEventListener('click', function () {
      if (isPlaying) {
        audio.pause();
        btn.innerHTML = '&#9654;'; // Play icon
        isPlaying = false;
      } else {
        // Pause any other active player first
        document.querySelectorAll('audio').forEach(function (el) {
          el.pause();
        });
        document.querySelectorAll('.audio-control-btn').forEach(function (b) {
          b.innerHTML = '&#9654;';
        });

        audio.play();
        btn.innerHTML = '&#10074;&#10074;'; // Pause icon
        isPlaying = true;
      }
    });

    audio.addEventListener('timeupdate', function () {
      if (audio.duration) {
        var percent = (audio.currentTime / audio.duration) * 100;
        if (progressBar) {
          progressBar.style.width = percent + '%';
        }
        if (timeDisplay) {
          var mins = Math.floor(audio.currentTime / 60);
          var secs = Math.floor(audio.currentTime % 60);
          if (secs < 10) secs = '0' + secs;
          timeDisplay.innerText = mins + ':' + secs;
        }
      }
    });

    audio.addEventListener('ended', function () {
      btn.innerHTML = '&#9654;';
      isPlaying = false;
      if (progressBar) {
        progressBar.style.width = '0%';
      }
    });

    if (progressTrack) {
      progressTrack.addEventListener('click', function (e) {
        var rect = progressTrack.getBoundingClientRect();
        // Calculate offset based on RTL/LTR tracking
        var clickX = e.clientX - rect.left;
        var width = rect.width;
        // In RTL, progress grows from right to left
        var percent = (rect.right - e.clientX) / width;
        if (percent < 0) percent = 0;
        if (percent > 1) percent = 1;
        if (!isNaN(audio.duration)) {
          audio.currentTime = percent * audio.duration;
        }
      });
    }
  });

  // 4. Active Table of Contents Highlighter on Scroll
  var tocLinks = document.querySelectorAll('.toc-box ul li a');
  var headers = [];

  tocLinks.forEach(function (link) {
    var targetId = link.getAttribute('href');
    if (targetId && targetId.startsWith('#')) {
      var headerEl = document.querySelector(targetId);
      if (headerEl) {
        headers.push({ link: link, element: headerEl });
      }
    }
  });

  if (headers.length > 0) {
    window.addEventListener('scroll', function () {
      var scrollPos = window.scrollY || window.pageYOffset;
      var activeIndex = -1;

      for (var i = 0; i < headers.length; i++) {
        if (scrollPos >= (headers[i].element.offsetTop - 120)) {
          activeIndex = i;
        }
      }

      tocLinks.forEach(function (link) {
        link.classList.remove('active');
      });

      if (activeIndex !== -1) {
        headers[activeIndex].link.classList.add('active');
      }
    });
  }

  // 5. Leaflet Map Interactive loader (Lazy-load system)
  var mapElement = document.getElementById('ppt-single-map');
  if (mapElement && typeof L !== 'undefined') {
    var lat = parseFloat(mapElement.getAttribute('data-lat'));
    var lng = parseFloat(mapElement.getAttribute('data-lng'));
    var label = mapElement.getAttribute('data-label') || 'موقعیت مکانی';

    if (!isNaN(lat) && !isNaN(lng)) {
      var mapObj = L.map('ppt-single-map', {
        scrollWheelZoom: false
      }).setView([lat, lng], 14);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(mapObj);

      L.marker([lat, lng]).addTo(mapObj)
        .bindPopup(label)
        .openPopup();
    }
  }

  // 6. Premium Advanced AJAX Discovery Filter & Sorting (Priority 2)
  var filterContainer = document.querySelector('.ppt-ajax-filter-row');
  var editorialGrid  = document.querySelector('.editorial-grid');

  if (filterContainer && editorialGrid && typeof ppt_vars !== 'undefined') {
    var inputs = filterContainer.querySelectorAll('select, input');

    var performFilter = function () {
      var params = new URLSearchParams();
      params.append('action', 'ppt_ajax_filter');

      inputs.forEach(function (input) {
        if (input.value) {
          params.append(input.name, input.value);
        }
      });

      // Show temporary loading spinner/indicator inside the grid
      editorialGrid.style.opacity = '0.5';

      fetch(ppt_vars.ajax_url + '?' + params.toString())
        .then(function (res) { return res.json(); })
        .then(function (data) {
          editorialGrid.style.opacity = '1';
          if (data && data.success && data.data && data.data.html) {
            editorialGrid.innerHTML = data.data.html;
          }
        })
        .catch(function (err) {
          editorialGrid.style.opacity = '1';
          console.error('AJAX Filter failed:', err);
        });
    };

    inputs.forEach(function (input) {
      input.addEventListener('change', performFilter);
      if (input.tagName === 'INPUT') {
        input.addEventListener('keyup', function () {
          // debounce slightly
          clearTimeout(window.ppt_filter_timer);
          window.ppt_filter_timer = setTimeout(performFilter, 300);
        });
      }
    });
  }
});
