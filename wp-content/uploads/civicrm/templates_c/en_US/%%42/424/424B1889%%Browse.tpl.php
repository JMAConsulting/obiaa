<?php /* Smarty version 2.6.31, created on 2022-05-30 04:27:44
         compiled from CRM/Mailing/Page/Browse.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Mailing/Page/Browse.tpl', 1, false),array('block', 'crmButton', 'CRM/Mailing/Page/Browse.tpl', 24, false),array('block', 'ts', 'CRM/Mailing/Page/Browse.tpl', 24, false),array('block', 'icon', 'CRM/Mailing/Page/Browse.tpl', 100, false),array('function', 'counter', 'CRM/Mailing/Page/Browse.tpl', 48, false),array('function', 'cycle', 'CRM/Mailing/Page/Browse.tpl', 50, false),array('function', 'crmURL', 'CRM/Mailing/Page/Browse.tpl', 57, false),array('modifier', 'escape', 'CRM/Mailing/Page/Browse.tpl', 57, false),array('modifier', 'mb_truncate', 'CRM/Mailing/Page/Browse.tpl', 58, false),array('modifier', 'replace', 'CRM/Mailing/Page/Browse.tpl', 73, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if ($this->_tpl_vars['sms']): ?>
  <?php $this->assign('newMassUrl', 'civicrm/sms/send'); ?>
  <?php $this->assign('qVal', '&sms=1'); ?>
  <?php $this->assign('linkTitle', 'New SMS'); ?>
  <?php $this->assign('componentName', 'Mass SMS'); ?>
<?php else: ?>
  <?php $this->assign('newMassUrl', 'civicrm/mailing/send'); ?>
  <?php $this->assign('qVal', ''); ?>
  <?php $this->assign('linkTitle', 'New Mailing'); ?>
  <?php $this->assign('componentName', 'Mailings'); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['showLinks']): ?>
    <div class="action-link">
      <?php $this->_tag_stack[] = array('crmButton', array('accesskey' => 'N','p' => $this->_tpl_vars['newMassUrl'],'q' => 'reset=1','icon' => 'envelope')); $_block_repeat=true;smarty_block_crmButton($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['linkTitle']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmButton($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><br/><br/>
    </div>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Mailing/Form/Search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['rows']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/pager.tpl", 'smarty_include_vars' => array('location' => 'top')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/pagerAToZ.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <?php echo '<table class="selector row-highlight"><thead class="sticky">'; ?><?php $_from = $this->_tpl_vars['columnHeaders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header']):
?><?php echo '<th>'; ?><?php if (! empty ( $this->_tpl_vars['header']['sort'] )): ?><?php echo ''; ?><?php $this->assign('key', $this->_tpl_vars['header']['sort']); ?><?php echo ''; ?><?php echo $this->_tpl_vars['sort']->_response[$this->_tpl_vars['key']]['link']; ?><?php echo ''; ?><?php elseif (! empty ( $this->_tpl_vars['header']['name'] )): ?><?php echo ''; ?><?php echo $this->_tpl_vars['header']['name']; ?><?php echo ''; ?><?php endif; ?><?php echo '</th>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</thead>'; ?><?php echo smarty_function_counter(array('start' => 0,'skip' => 1,'print' => false), $this);?><?php echo ''; ?><?php $_from = $this->_tpl_vars['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?><?php echo '<tr id="crm-mailing_'; ?><?php echo $this->_tpl_vars['row']['id']; ?><?php echo '" class="'; ?><?php echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this);?><?php echo ' crm-mailing crm-mailing_status-'; ?><?php echo $this->_tpl_vars['row']['status']; ?><?php echo '"><td class="crm-mailing-name">'; ?><?php echo $this->_tpl_vars['row']['name']; ?><?php echo '</td>'; ?><?php if ($this->_tpl_vars['multilingual']): ?><?php echo '<td class="crm-mailing-language">'; ?><?php echo $this->_tpl_vars['row']['language']; ?><?php echo '</td>'; ?><?php endif; ?><?php echo '<td class="crm-mailing-status crm-mailing_status-'; ?><?php echo $this->_tpl_vars['row']['status']; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['row']['status']; ?><?php echo '</td><td class="crm-mailing-created_by"><a href ='; ?><?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view','q' => "reset=1&cid="), $this);?><?php echo ''; ?><?php echo $this->_tpl_vars['row']['created_id']; ?><?php echo ' title="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['created_by'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?><?php echo '">'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['created_by'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 20, "...") : smarty_modifier_mb_truncate($_tmp, 20, "...")); ?><?php echo '</a></td><td class="crm-mailing-created_date">'; ?><?php echo $this->_tpl_vars['row']['created_date']; ?><?php echo '</td><td class="crm-mailing-scheduled_by"><a href ='; ?><?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view','q' => "reset=1&cid="), $this);?><?php echo ''; ?><?php echo $this->_tpl_vars['row']['scheduled_id']; ?><?php echo ' title="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['scheduled_by'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?><?php echo '">'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['scheduled_by'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 20, "...") : smarty_modifier_mb_truncate($_tmp, 20, "...")); ?><?php echo '</a></td><td class="crm-mailing-scheduled">'; ?><?php echo $this->_tpl_vars['row']['scheduled']; ?><?php echo '</td><td class="crm-mailing-start">'; ?><?php echo $this->_tpl_vars['row']['start']; ?><?php echo '</td><td class="crm-mailing-end">'; ?><?php echo $this->_tpl_vars['row']['end']; ?><?php echo '</td>'; ?><?php if (call_user_func ( array ( 'CRM_Campaign_BAO_Campaign' , 'isCampaignEnable' ) )): ?><?php echo '<td class="crm-mailing-campaign">'; ?><?php echo $this->_tpl_vars['row']['campaign']; ?><?php echo '</td>'; ?><?php endif; ?><?php echo '<td>'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['action'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'xx', $this->_tpl_vars['row']['id']) : smarty_modifier_replace($_tmp, 'xx', $this->_tpl_vars['row']['id'])); ?><?php echo '</td></tr>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</table>'; ?>


    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/pager.tpl", 'smarty_include_vars' => array('location' => 'bottom')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php if ($this->_tpl_vars['showLinks']): ?>
      <div class="action-link">
            <?php $this->_tag_stack[] = array('crmButton', array('accesskey' => 'N','p' => $this->_tpl_vars['newMassUrl'],'q' => 'reset=1','icon' => 'envelope')); $_block_repeat=true;smarty_block_crmButton($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['linkTitle']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmButton($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><br/>
      </div>
    <?php endif; ?>

<?php elseif ($this->_tpl_vars['isSearch'] == 1): ?>
    <?php if ($this->_tpl_vars['archived']): ?>
        <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/mailing/browse/archived','q' => "reset=1"), $this);?>
<?php echo $this->_tpl_vars['qVal']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('browseURL', ob_get_contents());ob_end_clean(); ?>
        <?php $this->assign('browseType', 'Archived'); ?>
    <?php elseif ($this->_tpl_vars['unscheduled']): ?>
        <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/mailing/browse/unscheduled','q' => "scheduled=false&reset=1"), $this);?>
<?php echo $this->_tpl_vars['qVal']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('browseURL', ob_get_contents());ob_end_clean(); ?>
        <?php $this->assign('browseType', 'Draft and Unscheduled'); ?>
    <?php else: ?>
        <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/mailing/browse/scheduled','q' => "scheduled=true&reset=1"), $this);?>
<?php echo $this->_tpl_vars['qVal']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('browseURL', ob_get_contents());ob_end_clean(); ?>
        <?php $this->assign('browseType', 'Scheduled and Sent'); ?>
    <?php endif; ?>
    <div class="status messages">
        <table class="form-layout">
            <tr><?php $this->_tag_stack[] = array('icon', array('icon' => "fa-info-circle")); $_block_repeat=true;smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
               <?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['componentName'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>No %1 match your search criteria. Suggestions:<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
      </tr>
                <div class="spacer"></div>
                <ul>
                <li><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Check your spelling.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></li>
                <li><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Try a different spelling or use fewer letters.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></li>
                </ul>
            <tr><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['browseURL'],'2' => $this->_tpl_vars['browseType'],'3' => $this->_tpl_vars['componentName'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Or you can <a href='%1'>browse all %2 %3</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></tr>
        </table>
    </div>
<?php elseif ($this->_tpl_vars['unscheduled']): ?>

    <div class="messages status no-popup">
            <?php $this->_tag_stack[] = array('icon', array('icon' => "fa-info-circle")); $_block_repeat=true;smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
            <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => $this->_tpl_vars['newMassUrl'],'q' => 'reset=1'), $this);?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean(); ?>
            <?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['componentName'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>There are no Unscheduled %1.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
      <?php if ($this->_tpl_vars['showLinks']): ?><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['crmURL'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You can <a href='%1'>create and send one</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php endif; ?>
   </div>

<?php elseif ($this->_tpl_vars['archived']): ?>
    <div class="messages status no-popup">
            <?php $this->_tag_stack[] = array('icon', array('icon' => "fa-info-circle")); $_block_repeat=true;smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>&nbsp
            <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/mailing/browse/scheduled','q' => 'scheduled=true&reset=1'), $this);?>
<?php echo $this->_tpl_vars['qVal']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean(); ?>
            <?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['crmURL'],'2' => $this->_tpl_vars['componentName'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>There are no Archived %2. You can archive %2 from <a href='%1'>Scheduled or Sent %2</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
   </div>
<?php else: ?>
    <div class="messages status no-popup">
            <?php $this->_tag_stack[] = array('icon', array('icon' => "fa-info-circle")); $_block_repeat=true;smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_icon($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
            <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => $this->_tpl_vars['newMassUrl'],'q' => 'reset=1'), $this);?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean(); ?>
            <?php ob_start(); ?><?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/mailing/browse/archived','q' => 'reset=1'), $this);?>
<?php echo $this->_tpl_vars['qVal']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('archiveURL', ob_get_contents());ob_end_clean(); ?>
            <?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['componentName'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>There are no Scheduled or Sent %1.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
      <?php if ($this->_tpl_vars['showLinks']): ?><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['crmURL'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You can <a href='%1'>create and send one</a><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php endif; ?><?php if ($this->_tpl_vars['archiveLinks']): ?><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['archiveURL'],'2' => $this->_tpl_vars['componentName'])); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?> OR you can search the <a href='%1'>Archived %2</a><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php endif; ?>.
   </div>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>