/**
 * Meta Box Field JavaScript
 */

(function ($) {
  'use strict';

  /**
   * Inizializza le funzionalità per i campi Meta Box
   */
  function initMetaBoxFields() {
    // Gestisci i campi URL
    initUrlFields();

    // Gestisci i campi immagine
    initImageFields();

    // Gestisci i campi video
    initVideoFields();
  }

  /**
   * Inizializza le funzionalità per i campi URL
   */
  function initUrlFields() {
    $('.aifb-metabox-field-link').on('click', function (e) {
      // Aggiungi qui eventuali funzionalità aggiuntive per i link
      // Ad esempio, tracciamento degli eventi, ecc.
    });
  }

  /**
   * Inizializza le funzionalità per i campi immagine
   */
  function initImageFields() {
    $('.aifb-metabox-field-image').on('click', function (e) {
      // Aggiungi qui eventuali funzionalità aggiuntive per le immagini
      // Ad esempio, lightbox, zoom, ecc.
    });
  }

  /**
   * Inizializza le funzionalità per i campi video
   */
  function initVideoFields() {
    $('.aifb-metabox-field-video').each(function () {
      // Aggiungi qui eventuali funzionalità aggiuntive per i video
      // Ad esempio, autoplay, controlli personalizzati, ecc.
    });
  }

  // Inizializza quando il documento è pronto
  $(document).ready(function () {
    initMetaBoxFields();
  });
})(jQuery);
