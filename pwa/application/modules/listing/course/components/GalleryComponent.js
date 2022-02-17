import PropTypes from 'prop-types'
import React from 'react';
import './../assets/gallery.css';
import './../assets/courseCommon.css';
import GalleryList from './GalleryListComponent';
import GalleryLayer from './GalleryLayerComponent';
import Lazyload from './../../../reusable/components/Lazyload';

function bindFunctions(functions)
{
	functions.forEach( f => (this[f] = this[f].bind(this)));
}

var gallerySchema = '';

class Gallery extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
			displayList : false
		}

		bindFunctions.call(this, [
			'closeLightbox',
			'gotoNext',
			'gotoPrevious',
			'openGalleryList',
			'closeGalleryList',
		]);
	}
	formatHtml()
	{
		var data = this.props.data;
		var displayData = {};
		displayData['html'] = [];
		displayData['isPhotoExist'] = false;
		displayData['isVideoExist'] = false;
		displayData['totalImages'] = 0;
		displayData['totalVideos'] = 0;
		displayData['displayedImages'] = 0;
		this.formatPhotos(data,displayData);
		return displayData;
	}
	formatPhotos(data,displayData)
	{
		var self = this;
		var name = this.props.name;
		var part1Html = [];
		var part2Html = [];
		var part3Html = [];
		var totalImages = 0;
		var mediaHtml = [];
		var photos = typeof data['photos'] != 'undefined' ? data['photos'] : {};
		var indexCount = 0;
		let photoSections = typeof data['photoSections'] != 'undefined' ? data['photoSections'] : [];		for(let i in photoSections)
	{
		totalImages += photos[photoSections[i]].length;
		if(displayData['displayedImages'] >= 5)
		{
			continue;
		}
		else
		{
			photos[photoSections[i]].map(function(keyData){
				displayData['displayedImages']++;
				if(displayData['displayedImages'] <=2)
				{
					let imgUrl = keyData['widgetThumbUrl'];
					part1Html.push(<a href="javascript:void(0);" className='img-size' onClick={self.openLightbox.bind(self,indexCount)} key={displayData['displayedImages']}>
						<img src={imgUrl} />
					</a>);
					gallerySchema += '<script type="application/ld+json">{"@context" : "http://schema.org","@type" : "ImageObject", "name" : "'+name+'  -  '+keyData['mediaTitle']+'","contentUrl" : "'+keyData['mediaUrl']+'"}</script>';
				}else if(displayData['displayedImages'] >= 2 && displayData['displayedImages'] <=4)
				{
					let imgUrl = keyData['thumbUrl'];
					part2Html.push(<a href="javascript:void(0);" className='img-size2' onClick={self.openLightbox.bind(self,indexCount)} key={displayData['displayedImages']}>
						<img src={imgUrl} />
					</a>);
					gallerySchema += '<script type="application/ld+json">{"@context" : "http://schema.org","@type" : "ImageObject", "name" : "'+name+' - '+keyData['mediaTitle']+'","contentUrl" : "'+keyData['mediaUrl']+'"}</script>';
				}else if(displayData['displayedImages'] == 5){
					part3Html.push(<a href="javascript:void(0);" className='img-size2' onClick={self.openLightbox.bind(self,indexCount)} key={displayData['displayedImages']}>
						<img src={keyData['thumbUrl']} />
					</a>);
					gallerySchema += '<script type="application/ld+json">{"@context" : "http://schema.org","@type" : "ImageObject", "name" : "'+name+' - '+keyData['mediaTitle']+'","contentUrl" : "'+keyData['mediaUrl']+'"}</script>';
				}
				indexCount = indexCount + 1;
			});
		}
	}
		displayData['totalImages'] = totalImages;
		var videos = typeof data['videos'] != 'undefined' && data['videos'].length > 0 ? data['videos'] : [];
		if(displayData['displayedImages'] < 5)
		{
			for(let i in videos)
			{
				displayData['displayedImages']++;
				if(displayData['displayedImages'] <=2)
				{
					let imgUrl = videos[i]['thumbUrl'];
					part1Html.push(<a href="javascript:void(0);" className='img-size' onClick={this.openLightbox.bind(this,indexCount)} key={displayData['displayedImages']}>
						<img src={imgUrl} />
					</a>);
					gallerySchema +=  '<script type="application/ld+json">{"@context" : "http://schema.org","@type" : "VideoObject","url" : "'+videos[i]['mediaUrl']+'","name" : "'+name+' - '+videos[i]['mediaTitle']+'","description" : "'+name+' - '+videos[i]['mediaTitle']+'","uploadDate" : "'+    +'","thumbnailUrl" : "'+videos[i]['thumbUrl']+'"}</script>';
				}else if(displayData['displayedImages'] >= 2 && displayData['displayedImages'] <=4)
				{
					let imgUrl = videos[i]['thumbUrl'];
					part2Html.push(<a href="javascript:void(0);" className='img-size2' key={displayData['displayedImages']} onClick={this.openLightbox.bind(this,indexCount)}>
						<img src={imgUrl} />
					</a>);
					gallerySchema +=  '<script type="application/ld+json">{"@context" : "http://schema.org","@type" : "VideoObject","url" : "'+videos[i]['mediaUrl']+'","name" : "'+name+' - '+videos[i]['mediaTitle']+'","description" : "'+name+' - '+videos[i]['mediaTitle']+'","uploadDate" : "'+    +'","thumbnailUrl" : "'+videos[i]['thumbUrl']+'"}</script>';
				}else if(displayData['displayedImages'] == 5){
					part3Html.push(<a href="javascript:void(0);" className='img-size2' key={displayData['displayedImages']} onClick={this.openLightbox.bind(this,indexCount)}>
						<img src={videos[i]['thumbUrl']} />
					</a>);
					gallerySchema +=  '<script type="application/ld+json">{"@context" : "http://schema.org","@type" : "VideoObject","url" : "'+videos[i]['mediaUrl']+'","name" : "'+name+' - '+videos[i]['mediaTitle']+'","description" : "'+name+' - '+videos[i]['mediaTitle']+'","uploadDate" : "'+    +'","thumbnailUrl" : "'+videos[i]['thumbUrl']+'"}</script>';
				}
				indexCount = indexCount + 1;
			}
		}
		if(typeof videos != 'undefined' && Array.isArray(videos) && videos.length > 0)
		{
			displayData['totalVideos'] = videos.length;
		}
		if(part1Html.length > 0)
		{
			mediaHtml.push(<div className='top-col one-img' key="top-col">{part1Html}</div>);
		}
		if(part2Html.length > 0)
		{
			var viewMoreHtml = [];
			if((displayData['totalVideos'] + displayData['totalImages']) > 5 )
			{
				viewMoreHtml.push(<p className='block-lyr' key="countval"><a href="javascript:void(0)" className='viewMoreCount' onClick={this.openGalleryList}>+{(displayData['totalVideos'] + displayData['totalImages']) - 5}</a></p>);
			}
			mediaHtml.push(<div className='bottom-col one-img' key="bottom-col">{part2Html} {part3Html.length > 0 && <div className='block-Dv'>{part3Html} {viewMoreHtml}</div> }</div>);
		}
		displayData['html'] = mediaHtml;
	}
	openGalleryList()
	{
		this.setState({displayList : true});
		this.closeLightbox();
	}
	closeGalleryList()
	{
		this.setState({displayList : false});
	}
	openLightbox (index, event) {
		if (event) {
			event.preventDefault();
		}
		this.setState({
			currentImage: index,
			lightboxIsOpen: true
		});
	}
	closeLightbox () {
		if (this.props.lightboxWillClose) {
			this.props.lightboxWillClose.call(this);
		}

		this.setState({
			currentImage: 0,
			lightboxIsOpen: false
		});
	}

	gotoPrevious () {
		this.setState({
			currentImage: this.state.currentImage - 1
		});
	}

	gotoNext () {
		this.setState({
			currentImage: this.state.currentImage + 1
		});
	}

	convertMediaObjectIntoArray()
	{
		let photos = typeof this.props.data['photos'] != 'undefined' ? this.props.data['photos'] : {};
		var mediaData = [];
		var listHeading = '';
		let photosArray = Object.keys(photos);
		if(photosArray.length == 1 && photosArray.indexOf('Others') > -1)
		{
			listHeading = 'Photos';
		}
		let photoSections = typeof this.props.data['photoSections'] != 'undefined' ? this.props.data['photoSections'] : [];
		for(var pKey in photoSections)
		{
			photos[photoSections[pKey]].map(function(keyData){
				mediaData.push(Object.assign({'tagName' : (listHeading != '' ? listHeading : photoSections[pKey]) ,'mediaType': 'photos'},keyData));
			});
		}
		let videos = typeof this.props.data['videos'] != 'undefined' && this.props.data['videos'].length > 0 ? this.props.data['videos'] : [];

		videos.map(function(keyData){
			mediaData.push(Object.assign({'tagName' : 'Videos','mediaType': 'video'},keyData));
		});
		return mediaData;
	}

	getOnClickPrevFn () {
		return this.gotoPrevious;
	}

	getOnClickNextFn () {
		return this.gotoNext;
	}
	render()
	{
		gallerySchema = '';
		var data = this.formatHtml();
		let mediaData = this.convertMediaObjectIntoArray();
		let totalMedia = data['totalImages'] + data['totalVideos'];
		let mediaText = '';
		if( data['totalImages'] > 0)
			mediaText += 'Photos ';
		if(data['totalVideos'] > 0 && mediaText != '')
			mediaText += ' & Videos';
		if(data['totalVideos'] > 0 && mediaText == '')
			mediaText += 'Videos';
		let countText = totalMedia > 5 ? 5 : totalMedia;
		return (
			<section className='galleryBnr listingTuple' id="gallery">
				<div className='_container'>
					<h2 className='tbSec2'>Gallery <span className='head-L5'>(Showing {countText} of {totalMedia}  {mediaText})</span></h2>
					<div className='_subcontainer'>
						<Lazyload offset={100} once>
							{data['html']}
						</Lazyload>
					</div>
				</div>
				<GalleryList onRef={ref => (this.galleryList = ref)} data={this.props.data} openList={this.state.displayList} onClose={this.closeGalleryList} openLightbox={this.openLightbox.bind(this)}/>
				<GalleryLayer
					images={mediaData}
					currentImage={this.state.currentImage}
					isOpen={this.state.lightboxIsOpen}
					onClickNext={this.getOnClickNextFn()}
					onClickPrev={this.getOnClickPrevFn()}
					showCloseButton={this.props.showCloseButton}
					onClose={this.closeLightbox}
					openGalleryList = {this.openGalleryList}
				/>
			</section>
		)
	}
}
Gallery.defaultProps = {
	id: "Gallery",
	currentImage: 0,
	preloadNextImage: true,
	isOpen: false,
	showCloseButton: true,
};

export function getGallerySchema(){
	return gallerySchema;
}

export default Gallery;

Gallery.propTypes = {
  currentImage: PropTypes.number,
  data: PropTypes.any,
  id: PropTypes.string,
  isOpen: PropTypes.bool,
  lightboxWillClose: PropTypes.any,
  name: PropTypes.any,
  preloadNextImage: PropTypes.bool,
  showCloseButton: PropTypes.bool
}