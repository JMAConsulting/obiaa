{if !$embedded}
{crmTitle title="Contact Creation by Date"}
<div id="dataviz">
</div>
{/if}

{crmScript ext="eu.tttp.civisualize" file="js/nvd3/nv.d3.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/tooltip.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/utils.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/interactiveLayer.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/models/legend.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/models/axis.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/models/scatter.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/models/line.js"}
{crmScript ext="eu.tttp.civisualize" file="js/nvd3/src/models/lineChart.js"}

{literal}
<style>

body {
  overflow-y:scroll;
}

text {
  font: 12px sans-serif;
}

svg {
  display: block;
}

#chart1 svg {
  height: 500px;
  min-width: 200px;
  min-height: 100px;
/*
  margin: 50px;
  Minimum height and width is a good idea to prevent negative SVG dimensions...
  For example width should be =< margin.left + margin.right + 1,
  of course 1 pixel for the entire chart would not be very useful, BUT should not have errors
*/

}
#chart1 {
  margin-top: 200px;
  margin-left: 100px;
}
</style>
<body class='with-3d-shadow with-transitions'>

<div id="chart1" >
  <svg style="height: 500px;"></svg>
</div>

<script>

// We need all our libraries loaded before we start.
(function() { function bootViz() {
  // Use our versions of the libraries.
  var d3 = CRM.civisualize.d3, dc = CRM.civisualize.dc, crossfilter = CRM.civisualize.crossfilter;

  // Wrapping in nv.addGraph allows for '0 timeout render', stores rendered charts in nv.graphs, and may do more in the future... it's NOT required
  var chart;

  nv.addGraph(function() {
      chart = nv.models.lineChart()
      .options({
margin: {left: 100, bottom: 100},
x: function(d,i) { return i},
showXAxis: true,
showYAxis: true,
transitionDuration: 250
})
      ;

      // chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the parent chart, so need to chain separately
      chart.xAxis
      .axisLabel("Time (s)")
      .tickFormat(d3.format(',.1f'));

      chart.yAxis
      .axisLabel('Voltage (v)')
      .tickFormat(d3.format(',.2f'))
      ;

  d3.select('#chart1 svg')
.datum(sinAndCos())
  .call(chart);

  //TODO: Figure out a good way to do this automatically
  nv.utils.windowResize(chart.update);
  //nv.utils.windowResize(function() { d3.select('#chart1 svg').call(chart) });

  chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });

  return chart;
  });

function sinAndCos() {
  var sin = [],
  cos = [],
  rand = [],
  rand2 = []
    ;

  for (var i = 0; i < 100; i++) {
    sin.push({x: i, y: i % 10 == 5 ? null : Math.sin(i/10) }); //the nulls are to show how defined works
    cos.push({x: i, y: .5 * Math.cos(i/10)});
    rand.push({x:i, y: Math.random() / 10});
    rand2.push({x: i, y: Math.cos(i/10) + Math.random() / 10 })
  }

  return [
  {
area: true,
        values: sin,
        key: "Sine Wave",
        color: "#ff7f0e"
  },
  {
values: cos,
        key: "Cosine Wave",
        color: "#2ca02c"
  },
  {
values: rand,
        key: "Random Points",
        color: "#2222ff"
  }
  ,
    {
values: rand2,
        key: "Random Cosine",
        color: "#667711"
    }
  ];
}
  }

  // Boot our script as soon as ready.
  CRM.civisualizeQueue = CRM.civisualizeQueue || [];
  CRM.civisualizeQueue.push(bootViz);
})();
</script>
{/literal}
