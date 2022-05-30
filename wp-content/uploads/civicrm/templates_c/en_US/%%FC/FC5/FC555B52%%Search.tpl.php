<?php /* Smarty version 2.6.31, created on 2022-05-30 04:27:44
         compiled from CRM/Mailing/Form/Search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Mailing/Form/Search.tpl', 1, false),array('block', 'ts', 'CRM/Mailing/Form/Search.tpl', 30, false),array('function', 'help', 'CRM/Mailing/Form/Search.tpl', 13, false),array('function', 'cycle', 'CRM/Mailing/Form/Search.tpl', 33, false),array('modifier', 'crmAddClass', 'CRM/Mailing/Form/Search.tpl', 14, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><div class="crm-block crm-form-block crm-search-form-block">
  <table class="form-layout">
    <tr>
       <td><?php echo $this->_tpl_vars['form']['mailing_name']['label']; ?>
 <?php echo smarty_function_help(array('id' => "id-mailing_name"), $this);?>
<br />
        <?php echo ((is_array($_tmp=$this->_tpl_vars['form']['mailing_name']['html'])) ? $this->_run_mod_handler('crmAddClass', true, $_tmp, 'big') : smarty_modifier_crmAddClass($_tmp, 'big')); ?>

      </td>
    </tr>
    <tr>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Core/DatePickerRangeWrapper.tpl", 'smarty_include_vars' => array('fieldName' => 'mailing','to' => '','from' => '','colspan' => '2','class' => '','hideRelativeLabel' => 0)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </tr>
    <tr>
      <td colspan="1"><?php echo $this->_tpl_vars['form']['sort_name']['label']; ?>
 <?php echo smarty_function_help(array('id' => "id-create_sort_name"), $this);?>
<br />
        <?php echo ((is_array($_tmp=$this->_tpl_vars['form']['sort_name']['html'])) ? $this->_run_mod_handler('crmAddClass', true, $_tmp, 'big') : smarty_modifier_crmAddClass($_tmp, 'big')); ?>

        <br/><br/>
        <div class="crm-search-form-block-is_archive">
          <?php echo $this->_tpl_vars['form']['is_archived']['label']; ?>
 <?php echo smarty_function_help(array('id' => 'is_archived'), $this);?>
<br/>
          <?php echo $this->_tpl_vars['form']['is_archived']['html']; ?>

        </div>
      </td>
      <?php if (! empty ( $this->_tpl_vars['form']['mailing_status'] )): ?>
         <td width="100%"><label><?php if ($this->_tpl_vars['sms'] == 1): ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>SMS Status<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php else: ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Mailing Status<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php endif; ?></label><br />
           <div class="listing-box" style="height: auto">
             <?php $_from = $this->_tpl_vars['form']['mailing_status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mailing_status_val']):
?>
               <div class="<?php echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this);?>
">
                 <?php echo $this->_tpl_vars['mailing_status_val']['html']; ?>

               </div>
            <?php endforeach; endif; unset($_from); ?>
            <div class="<?php echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this);?>
">
              <?php echo $this->_tpl_vars['form']['status_unscheduled']['html']; ?>

            </div>
          </div><br />
        </td>
      <?php endif; ?>
    </tr>

        <?php if (! empty ( $this->_tpl_vars['form']['language'] )): ?>
      <tr>
        <td><?php echo $this->_tpl_vars['form']['language']['label']; ?>
 <?php echo smarty_function_help(array('id' => "id-language"), $this);?>
<br />
          <?php echo ((is_array($_tmp=$this->_tpl_vars['form']['language']['html'])) ? $this->_run_mod_handler('crmAddClass', true, $_tmp, 'big') : smarty_modifier_crmAddClass($_tmp, 'big')); ?>

        </td>
      </tr>
    <?php endif; ?>

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Campaign/Form/addCampaignToSearch.tpl", 'smarty_include_vars' => array('campaignTrClass' => '','campaignTdClass' => '')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <tr>
      <td><?php echo $this->_tpl_vars['form']['buttons']['html']; ?>
</td><td colspan="2"></td>
    </tr>
  </table>
</div>

<?php echo '
<script type="text/javascript">
  CRM.$(function($) {
    var archiveOption = $("input[name^=\'is_archived\']:radio");
    $(\'#status_unscheduled\').change(function() {
      if ($(this).prop(\'checked\') ) {
        archiveOption.prop({checked: false, disabled: true}).change();
      } else {
        archiveOption.prop(\'disabled\', false);
      }
    }).trigger(\'change\');
    archiveOption.change(function() {
      if ($("input[name^=\'is_archived\']:radio:checked").length) {
        $(\'#status_unscheduled\').prop({checked: false, disabled: true}).change();
      } else {
        $(\'#status_unscheduled\').prop(\'disabled\', false);
      }
    }).trigger(\'change\');
  });
</script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>