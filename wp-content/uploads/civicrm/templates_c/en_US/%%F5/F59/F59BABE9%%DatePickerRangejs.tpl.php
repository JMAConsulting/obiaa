<?php /* Smarty version 2.6.31, created on 2022-05-30 04:27:44
         compiled from CRM/Core/DatePickerRangejs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Core/DatePickerRangejs.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo '
  <script type="text/javascript">
    CRM.$(function($) {
      $("#'; ?>
<?php echo $this->_tpl_vars['relativeName']; ?>
<?php echo '").change(function() {
        var n = cj(this).parent().parent();
        if ($(this).val() == "0") {
          $(".crm-absolute-date-range", n).show();
        } else {
          $(".crm-absolute-date-range", n).hide();
          $(\':text\', n).val(\'\');
        }
      }).change();
    });
  </script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>