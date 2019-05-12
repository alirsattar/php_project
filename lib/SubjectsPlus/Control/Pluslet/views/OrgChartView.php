
<div class="org-chart pure-g" data-pluslet-id="<?php echo $this->_pluslet_id; ?>">

<?php
echo
"<div id=\"json-container\"hidden>
    $this->jsonObj
</div>"
?>

<!-- <script type="text/javascript">

    $(document).ready(()=> {
        const allStaff = JSON.parse($('#json-container').html());
        let baseObject = allStaff[0];

        const orgChart = (staffObject)=> {
            let supervisors = allStaff.filter(emp => emp.ptags == "supervisor" && emp.staff_id != 1).sort((a, b) => a.supervisor_id === null ? -1 : 1);

            let tree = $('#tree');

            // let baseObject = supervisors[0];

            function mapIt(initial) {
                function crawl(obj) {
                    obj.subs = allStaff.filter(((emp) => emp.supervisor_id === obj.staff_id));
                    if (obj.subs.length) {
                    obj.subs.forEach((sub) => crawl(sub));
                    };
                    return obj;
                };
                return crawl(initial);
            };

            console.log(baseObject);

            mapIt(baseObject);

            function showAll(initial) {
                function log(obj) {
                    if (obj.subs.length) {
                        let supervisorNode = $(tree).find(`*[data-id="${obj.staff_id}"]`);
                        supervisorNode.append(`<ul data-id="${obj.staff_id}-subs">`);
                        // console.log(`${obj.fname} ${obj.lname} subs:`);
                        obj.subs.forEach((sub) => {
                            supervisorNode.append(createChartNode(sub));
                            // console.log(`${sub.fname} ${sub.lname} (${sub.subs.length})`);
                        });
                        // console.log('------------');
                        obj.subs.forEach((s) => {
                            log(s);
                        });
                    };
                    tree.append(createChartNode(obj));
                };
                log(initial);
            };

            showAll(baseObject);

            function createChartNode(staffMember){
                return `<li data-id="${staffMember.staff_id}">${staffMember.fname} ${staffMember.lname}</li>`
            };
        };
        orgChart(allStaff);
    });

</script>

<div id="orgchart-container">
    <ul id="tree">
    </ul>
</div>


<div class="org-chart-description">
        <?php echo $this->_body; ?>
    </div>
</div>

<?php
    debug_print_backtrace();
?>

</div> -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {packages:["orgchart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');

        // For each orgchart box, provide the name, manager, and tooltip to show.

        

        data.addRows([
            [{v:'Mike', f:'Mike<div style="color:red; font-style:italic">President</div>'},
            '', 'The President'],
            [{v:'Jim', f:'Jim<div style="color:red; font-style:italic">Vice President</div>'},
            'Mike', 'VP'],
            ['Alice', 'Mike', ''],
            ['Bob', 'Jim', 'Bob Sponge'],
            ['Carol', 'Bob', '']
        ]);

        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        // Draw the chart, setting the allowHtml option to true for the tooltips.
        chart.draw(data, {allowHtml:true});
    }
</script>

<div id="chart_div"></div>

<h1>TEST</h1>