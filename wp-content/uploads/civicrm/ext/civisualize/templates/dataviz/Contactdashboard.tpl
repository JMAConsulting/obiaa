{crmTitle title="Overview of your CiviCRM"}
{capture assign="options"}{ldelim}width: 200{rdelim}{/capture}

<div id="dataviz">
</div>
{include file="dataviz/Contacttype.tpl" embedded=1 name="contactTypeGraph" options=$options}

{include file="dataviz/Groupbarchart.tpl" embedded=1 name="GroupBarChart"}

{literal}
<style>
#GroupBarChart {position:absolute;left:320px;}
</style>
{/literal}

