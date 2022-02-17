import PropTypes from 'prop-types'
import React from 'react';
import './../assets/galleryList.css';
import FullPageLayer from './../../../common/components/FullPageLayer';

class GalleryList extends React.Component
{
	constructor(props)
	{
		super(props);
	}
	generateHtml()
	{
		var data  = this.props.data;
		var photos = typeof data['photos'] != 'undefined' ? data['photos'] : {};
		var html  = [];
		var count = -1;
		var self = this;
		let photosArray = Object.keys(photos);
		var listHeading = '';
		if(photosArray.length == 1 && photosArray.indexOf('Others') > -1)
		{
			listHeading = 'Photos';
		}
		let photoSections = typeof data['photoSections'] != 'undefined' ? data['photoSections'] : [];
		for(let i in photoSections)
		{
			var childList = photos[photoSections[i]].map(function(keyData){
				count = count +1;
				return (
					<div className='img-blck' key={photoSections[i].replace(/\ /g,'_') + '_child'+count} id={photoSections[i].replace(/\ /g,'_') + '_child'+count}>
						<a href="javascript:void(0)" onClick={self.props.openLightbox.bind(self,count)}>
							<img src={keyData['thumbUrl'] }  width="94" height="76"/>
						</a>
					</div>
				);
			});
			html.push(<div className='galry-block' key={photoSections[i].replace(/\ /g,'_') + '_parent'}><strong>{ listHeading != '' ? listHeading : photoSections[i]}</strong><ul>
				<li>{childList}</li></ul></div>);
		}
		var videos = typeof data['videos'] != 'undefined' && data['videos'].length > 0 ? data['videos'] : [];
		if(Array.isArray(videos) && videos.length > 0)
		{
			let childList = videos.map(function(keyData){
				count = count +1;
				return (
					<div className='img-blck' key={'videos_child'+count} id={'videos_child'+count}>
						<a href="javascript:void(0)" onClick={self.props.openLightbox.bind(self,count)}>
							<img src={keyData['thumbUrl'] }  width="94" height="76"/>
						</a>
					</div>
				);
			});
			html.push(<div className='galry-block' key='videos_parent'><strong>Videos</strong><ul>
				<li>{childList}</li></ul></div>);
		}
		return html;
	}
	render()
	{
		var html ;

		if(!this.props.openList)
		{
			return null;
		}

		html = this.generateHtml();

		return (
			<div className='glry-div' id="gallery-div">
				<FullPageLayer onRef={ref => (this.galleryListRef = ref)} data={html} heading="Gallery" onClose={this.props.onClose} isOpen={this.props.openList}/>
			</div>
		)
	}
}
export default GalleryList;

GalleryList.propTypes = {
  data: PropTypes.any,
  onClose: PropTypes.any,
  openLightbox: PropTypes.any,
  openList: PropTypes.any
}