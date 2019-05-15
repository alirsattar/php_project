
<div class="org-chart" data-pluslet-id="<?php echo $this->_pluslet_id; ?>">
    <div class="pure-u-0-12">

    <?php echo "
    <script type=\"text/javascript\">
        const allStaff = $this->jsonObj
    </script>"
    ?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            let data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');
            
            let dataFormatted = [];
            let nodes = [];

            const formatData = (staffersArray)=> {
                let container = [];
                for(let staffer of staffersArray){
                    let fullName = `${staffer.fname} ${staffer.lname}`;
                    let superName;
                    let superId = (!!staffer.supervisor_id ? +staffer.supervisor_id : false);
                    if(superId){
                        let supervisor;
                        try{
                            supervisor = staffersArray.find((s)=> +s.staff_id == +superId);
                            if(supervisor && supervisor['fname']){
                                superName = `${supervisor['fname']} ${supervisor['lname']}`;
                            };
                        }
                        catch(e){
                            console.log(e);
                        }
                    };
                    container.push([
                        {   v: fullName,
                            f: `<a href="#">${fullName}</a>`},
                        superName,
                        'Click for Details'
                    ]);
                };
                return container;
            };

            dataFormatted.push(...formatData(allStaff));

            data.addRows(dataFormatted);

            // Create the chart.
            var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
            // Draw the chart, setting the allowHtml option to true for the tooltips.
            chart.draw(data, {size: 'small', allowHtml:true});
        }
    </script>

    <div style="display: flex; justify-content: center;">
        <h1>Organizational Chart</h1>
    </div>

    <div id="chart_div"></div>
    </div>

    <div id="edit-box">
        <input type="text">
    </div>
</div>