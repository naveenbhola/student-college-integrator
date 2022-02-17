import PropTypes from 'prop-types'
import React, {Component} from 'react';
import './../assets/naukriPlacementGraph.css';
import { Chart } from 'react-google-charts';
import Gradient from './../../../common/components/Gradient';

class NaukriPlacementGraph extends Component {
    constructor(props) {
        super(props);
        this.options= {
            bar: { groupWidth: '50%' },
            vAxis: {minValue:0,title:'Salary in (lakh)'},
            legend: { position: 'none'},
            seriesType: 'bars',
            annotations: {
                 textStyle: {
                     color: 'black',
                     fontSize: 11,
                     'fill-color': '#63A74A',
                     'stroke-color':'blue'
                 },
                 alwaysOutside: true,
                 highContrast:false
            },
            chartArea : {'width': '80%',height:'80%',top:20},
            tooltip: { textStyle: { fontName: 'Verdana', fontSize: 12,fontWeight:600 } }
          },


        this.state = {
            data: [['', '',{ role: 'style' },{ role: 'annotation' },{role: 'tooltip'}]]
        };
    }    

    prepareGraphData(){
        this.totalTypeOfValuePresent=0;

        let minSalary = this.props.salaryData.minSalary;
        let avgSalary = this.props.salaryData.avgSalary;
        let maxSalary = this.props.salaryData.maxSalary;
        let maximumValue = 0;

        let data = this.state.data;

        if(minSalary!=null){
            maximumValue = Math.max(maximumValue,minSalary);
            data.push(['Min',minSalary,"#008489",minSalary+' lakh',minSalary+' lakh']);
        }
        if(avgSalary!=null){
            maximumValue = Math.max(maximumValue,avgSalary);            
            data.push(['Median',avgSalary,"#008489",avgSalary+' lakh',avgSalary+' lakh']);
        }

        if(maxSalary!=null){
            maximumValue = Math.max(maximumValue,maxSalary);            
            data.push(['Max',maxSalary,"#008489",maxSalary+' lakh',maxSalary+' lakh']);
        }
        //this.options.vAxis.maxValue = maximumValue+10;        


        if(minSalary!=null || avgSalary!=null ||  maxSalary!=null ){
            this.setState({
                data
            })
        }
    }

    componentDidMount(){
        this.prepareGraphData();

    }

    render(){    
        return(
            <div>
                <Chart
                    chartType="ColumnChart"
                    data={this.state.data}
                    options={this.options}
                    graph_id="LineChart2"
                    width="100%"
                    height="250px"
                />
            </div>
        )

    }
}

export default NaukriPlacementGraph;

NaukriPlacementGraph.propTypes = {
    salaryData: PropTypes.any
}