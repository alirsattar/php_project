
<div class="org-chart" data-pluslet-id="<?php echo $this->_pluslet_id; ?>">
    <div class="pure-u-0-12">

    <?php echo "
    <script type=\"text/javascript\">
        const allStaff = $this->jsonObj
    </script>"
    ?>

    <!-- Bring in Google Charts package -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            let data = new google.visualization.DataTable();

            // Setting up Google Charts 'columns'. These will determine what info is presented.
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');

            let dataFormatted = [];
            let nodes = [];

            // Prepare data for Google Charts presentation
            const formatData = (staffersArray)=> {
                let container = [];
                for(let staffer of staffersArray){
                    let fullName = `${staffer.fname} ${staffer.lname}`;
                    let superName;
                    let superId = (!!staffer.supervisor_id ? +staffer.supervisor_id : false);

                    // Formatting URL string, so user can click on individual staff member in org chart and be taken to their profile page
                    let url = `http://localhost:3000/subjects/staff_details.php?name=` + staffer['email'].split('@')[0];

                    console.log(url);

                     // If staff member has a supervisor
                    if(superId){
                        let supervisor;
                        // Made this a try-catch because of weird type errors -- probably due to PHP json_encode shenanigans
                        try{
                            // If an item matching this ID is found in the array
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
                            f: `<a href="${url}" target="_blank">${fullName}</a>`}, // Link taking user to that staff member's profile page
                        superName,
                        'Click for Details'
                    ]);
                };
                return container;
            };

            dataFormatted.push(...formatData(allStaff));

            data.addRows(dataFormatted);

            // Create the chart
            let chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
            // Draw the chart, setting the allowHtml option to true for the tooltips
            chart.draw(data, {size: 'small', allowHtml:true});
        }
    </script>

    <div style="display: flex; justify-content: center;">
        <h1>Organizational Chart</h1>
    </div>

    <div id="chart_div"></div>
    </div>
</div>