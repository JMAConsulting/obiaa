<?php /* Smarty version 2.6.31, created on 2022-05-30 04:27:47
         compiled from Civi/Angular/Page/Main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'Civi/Angular/Page/Main.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo '
<script type="text/javascript">
  if (CRM.hasOwnProperty(\'angularRoute\') && CRM.angularRoute) {
    location.hash = CRM.angularRoute;
  }
</script>

<crm-angular-js modules="crmApp">
  <div ng-view></div>
</crm-angular-js>
'; ?>


<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>