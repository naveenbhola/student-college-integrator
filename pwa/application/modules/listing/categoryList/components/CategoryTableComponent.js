import React from 'react';
import {getRupeesDisplayableAmount} from './../../course/utils/listingCommonUtil';
import styles from './../assets/categoryTableComponent.css';

class CategoryTableComponent extends React.Component {
    constructor(props)
    {
        super(props);
    }
    
    getContainerData(){
        let html = (this.props.categoryData.categoryInstituteTuple).map(function (data, key){
                        return(<tr key={key}>
                            <td>{data.name}</td>
                            <td>{data.courseTupleData.name}</td>
                            <td>{data.courseTupleData.fees!=null?getRupeesDisplayableAmount(data.courseTupleData.fees):'-'}</td>
                        </tr>)
                    });
        return html;
    }

    render(){
        var self = this;
        let containerData = self.getContainerData();
            return (
                <div className={(this.props.show=='true') ? 'tbl-seo show' : 'tbl-seo hide'}>
                    <table cellPadding="0" cellSpacing="0">
                        <tbody>
                        <tr>
                            <th width="40%">Institute Name</th>
                            <th width="40%">Course Name</th>
                            <th width="20%">Fees</th>
                        </tr>
                        {containerData}
                    </tbody>
                    </table>
                </div>
            );
    }
}

export default CategoryTableComponent;