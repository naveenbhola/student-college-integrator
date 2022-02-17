import PropTypes from 'prop-types'
import React from 'react';

import './../assets/DataTableStyle.css';
import rankingConfig from './../config/rankingConfig';

class RankingPageDataTable extends React.Component {
    render(){
        let rows = [], allPublishers = [];
        this.props.tableData.forEach(
            currObj => {
                allPublishers = Object.keys(currObj.rankingData)
                if(this.props.rankingPageId == 2 || this.props.rankingPageId == 44){
                    rows.push(<tr key={currObj.instituteId}>
                            <td>{currObj.name}</td>
                            <td>{currObj.rankingData[rankingConfig.shikshaDefaultPublisherId] != null ? currObj.rankingData[rankingConfig.shikshaDefaultPublisherId].rank : currObj.rankingData[allPublishers[0]].rank}</td>
                        </tr>
                    );
                }else{
                    //allPublishers = Object.keys(currObj.rankingData)
                    rows.push(<tr key={currObj.instituteId}>
                            <td>{currObj.name}</td>
                            <td>{Object.keys(currObj.rankingData).length > 0 ? currObj.rankingData[allPublishers[0]].rank : '-'}</td>
                        </tr>
                    );
                }
            }
        );
        return (
            <div className="tbl-seo">
                <table>
                    <thead>
                    <tr>
                        <th>Institute Name</th>
                        <th>Rank</th>
                    </tr>
                    </thead>
                    <tbody>
                    {rows}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default RankingPageDataTable;

RankingPageDataTable.propTypes = {
  rankingPageId: PropTypes.any,
  tableData: PropTypes.any
}