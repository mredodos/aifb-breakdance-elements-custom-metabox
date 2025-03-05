/**
 * Meta Box Field Admin JavaScript
 */

(function ($) {
  'use strict';

  /**
   * Inizializza le funzionalità per i campi Meta Box nell'admin
   */
  function initAdminMetaBoxFields() {
    // Gestisci la selezione del tipo di campo
    initFieldTypeSelection();

    // Gestisci l'opzione "Is Clonable"
    initClonableOption();
  }

  /**
   * Inizializza la selezione del tipo di campo
   */
  function initFieldTypeSelection() {
    $('.aifb-admin-metabox-field-type-select').on('change', function () {
      var fieldType = $(this).val();

      // Nascondi tutte le impostazioni specifiche per tipo
      $('.aifb-admin-metabox-field-type-settings').hide();

      // Mostra le impostazioni specifiche per il tipo selezionato
      $('.aifb-admin-metabox-field-type-settings-' + fieldType).show();
    });

    // Trigger change per impostare lo stato iniziale
    $('.aifb-admin-metabox-field-type-select').trigger('change');
  }

  /**
   * Inizializza l'opzione "Is Clonable"
   */
  function initClonableOption() {
    $('.aifb-admin-metabox-field-clonable-toggle').on('change', function () {
      var isChecked = $(this).prop('checked');

      // Mostra/nascondi le impostazioni per i campi clonabili
      if (isChecked) {
        $('.aifb-admin-metabox-field-clonable-settings').show();
      } else {
        $('.aifb-admin-metabox-field-clonable-settings').hide();
      }
    });

    // Trigger change per impostare lo stato iniziale
    $('.aifb-admin-metabox-field-clonable-toggle').trigger('change');
  }

  // Inizializza quando il documento è pronto
  $(document).ready(function () {
    initAdminMetaBoxFields();
  });
})(jQuery);
