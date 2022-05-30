<?php /* Smarty version 2.6.31, created on 2022-05-30 04:27:52
         compiled from CRM/Mosaico/Page/EditorIframe.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Mosaico/Page/EditorIframe.tpl', 1, false),array('block', 'ts', 'CRM/Mosaico/Page/EditorIframe.tpl', 31, false),array('modifier', 'truncate', 'CRM/Mosaico/Page/EditorIframe.tpl', 2, false),array('modifier', 'htmlspecialchars', 'CRM/Mosaico/Page/EditorIframe.tpl', 7, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo ((is_array($_tmp=$this->_tpl_vars['config']->lcMessages)) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, "", true) : smarty_modifier_truncate($_tmp, 2, "", true)); ?>
" xml:lang="<?php echo ((is_array($_tmp=$this->_tpl_vars['config']->lcMessages)) ? $this->_run_mod_handler('truncate', true, $_tmp, 2, "", true) : smarty_modifier_truncate($_tmp, 2, "", true)); ?>
">

<head>
  <title>CiviCRM Mosaico</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <base href="<?php echo ((is_array($_tmp=$this->_tpl_vars['baseUrl'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
">

  <?php $_from = $this->_tpl_vars['scriptUrls']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['scriptUrl']):
?>
  <script type="text/javascript" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['scriptUrl'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
">
  </script>
  <?php endforeach; endif; unset($_from); ?>
  <?php $_from = $this->_tpl_vars['styleUrls']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['styleUrl']):
?>
  <link href="<?php echo ((is_array($_tmp=$this->_tpl_vars['styleUrl'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
" rel="stylesheet" type="text/css"/>
  <?php endforeach; endif; unset($_from); ?>

  <?php echo '
  <script type="text/javascript">
    $(function() {
      if (!Mosaico.isCompatible()) {
        alert(\'Your browser is out of date or you have incompatible plugins.  See https://civicrm.stackexchange.com/q/26118/225\');
        return;
      }

      var plugins = '; ?>
<?php echo $this->_tpl_vars['mosaicoPlugins']; ?>
<?php echo ';
      var config = '; ?>
<?php echo $this->_tpl_vars['mosaicoConfig']; ?>
<?php echo ';
      
      window.addEventListener(\'beforeunload\', function(e) {
        if(window.parent.document.getElementById(\'crm-mosaico\').style.display !== "none") {
          e.preventDefault();
          e.returnValue = "'; ?>
<?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Exit email composer without saving?<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '";
        }
      });

      if (window.top.crmMosaicoIframe) {
        window.top.crmMosaicoIframe(window, Mosaico, config, plugins);
      }
      else {
        alert(\'This page must be loaded in a suitable IFRAME.\');
      }
    });
  </script>
  '; ?>

</head>

<body class="mo-standalone">
</body>

</html>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>