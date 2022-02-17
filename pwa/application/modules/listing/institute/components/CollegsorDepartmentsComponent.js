import React from 'react';
import specificCss from './../assets/css/colleges.css';

class CollegsorDepartments extends React.Component {

  render() {
    return (<section>
      <div className="collegedpts listingTuple">
        <div className="_container">
          <h2 className="tbSec2">Colleges/Departments<span className="clg-label-tip">
              <i className="info-icon"></i>
            </span>
          </h2>
          <div className="_subcontainer">
            <div className="_titleDpt">
              <strong>Most viewed constituent colleges and departments of University of Delhi</strong>
            </div>
            <div className="wrapperAround">
              <div className="_flexirow">
                <div className="flexi_img"><img src="" alt=""/></div>
                <div className="flexi_column">
                  <p className="_clgname">Faulty of Management Studies</p>
                  <div className="ratingv1">
                    <div className="rating-block">5.0 rating</div>
                  </div>
                </div>
              </div>
              <div className="_flexirow">
                <div className="flexi_img"><img src="" alt=""/></div>
                <div className="flexi_column">
                  <p className="_clgname">Faulty of Management Studies</p>
                  <div className="ratingv1">
                    <div className="rating-block">5.0 rating</div>
                  </div>
                </div>
              </div>
              <div className="_flexirow">
                <div className="flexi_img"><img src="" alt=""/></div>
                <div className="flexi_column">
                  <p className="_clgname">Faulty of Management Studies</p>
                  <div className="ratingv1">
                    <div className="rating-block">5.0 rating</div>
                  </div>
                </div>
              </div>
            </div>

            <div className="tac _padaround btn-topMrgn">
              <buttom className="button button--secondary rippleeffect arrow">View All 10 Courses
                
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>)
  }
}

export default CollegsorDepartments;
