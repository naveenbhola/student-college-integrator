import PropTypes from 'prop-types'
import React from 'react';
import ReactDOM from 'react-dom';
import Swipeable from './../../../reusable/components/Swipeable';
import './../assets/galleryList.css';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';

function bindFunctions(functions)
{
	functions.forEach( f => (this[f] = this[f].bind(this)));
}

class GalleryLayer extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = { imageLoaded: false };
		bindFunctions.call(this, [
			'gotoNext',
			'gotoPrev',
			'handleImageLoaded',
			'closeLigtbox'
		]);
	}

	handleImageLoaded () {
		this.setState({ imageLoaded: true });
	}

	//function is used for loading images
	preloadImage (idx, onload) {
		const data = this.props.images[idx];
		if (!data) return;
		if(data.mediaType != 'photos')
			return;

		const img = new Image();

		//error handling
		img.onerror = onload;
		img.onload = onload;
		img.src = data.mediaUrl;

		return img;
	}
	preloadVideo(idx,onload)
	{
		const data = this.props.images[idx];
		if(!data) return;

		var ifrm = document.createElement("iframe");
		ifrm.setAttribute("src", "https://www.youtube.com/embed/gXF-IKJFxAs");
		ifrm.style.width = "640px";
		ifrm.style.height = "480px";
		ifrm.onerror = onload;
		ifrm.onload = onload;
		return ifrm;
	}
	componentWillReceiveProps (nextProps) {
		// preload images
		if (nextProps.preloadNextImage) {
			const currentIndex = this.props.currentImage;
			const nextIndex = nextProps.currentImage + 1;
			const prevIndex = nextProps.currentImage - 1;
			let preloadIndex;

			if (currentIndex && nextProps.currentImage > currentIndex) {
				preloadIndex = nextIndex;
			} else if (currentIndex && nextProps.currentImage < currentIndex) {
				preloadIndex = prevIndex;
			}

			// if we know the user's direction just get one image
			// otherwise, to be safe, we need to grab one in each direction
			if (preloadIndex) {
				if(preloadIndex >= 0 && preloadIndex < this.props.images.length)
				{
					const data = this.props.images[preloadIndex];
					if(data.mediaType == 'photos')
					{
						this.preloadImage(preloadIndex);
					}
				}
			} else {
				if(prevIndex >= 0)
				{
					let data = this.props.images[prevIndex];
					if(data.mediaType == 'photos')
					{
						this.preloadImage(prevIndex);
					}
				}
				if(nextIndex < this.props.images.length)
				{
					let nextData = this.props.images[nextIndex];
					if(nextData.mediaType == 'photos')
					{
						this.preloadImage(nextIndex);
					}
				}
			}
		}

		// preload current image
		if (this.props.currentImage !== nextProps.currentImage || !this.props.isOpen && nextProps.isOpen) {

			const data = this.props.images[nextProps.currentImage];

			if(data.mediaType == 'photos')
			{
				const img = this.preloadImage(nextProps.currentImage, this.handleImageLoaded);
				this.setState({ imageLoaded: img.complete });
			}
			else if(data.mediaType == 'video')
			{
				/*console.log('extends video');
				const img = this.preloadVideo(nextProps.currentImage, this.handleImageLoaded);*/
				this.setState({ imageLoaded: false });
			}
		}
	}
	gotoNext (event) {
		const { currentImage, images } = this.props;
		const { imageLoaded } = this.state;

		if (!imageLoaded || currentImage === (images.length - 1)) return;

		if (event) {
			event.preventDefault();
			event.stopPropagation();
		}

		this.props.onClickNext();
	}
	gotoPrev (event) {
		const { currentImage } = this.props;
		const { imageLoaded } = this.state;

		if (!imageLoaded || currentImage === 0) return;

		if (event) {
			event.preventDefault();
			event.stopPropagation();
		}

		this.props.onClickPrev();
	}
	render()
	{
		return (
			<div>
				{this.renderLayerHtml()}
			</div>
		)
	}
	renderArrowPrev () {
		if (this.props.currentImage === 0) return null;

		return (
			<i className='bck-icn' id="prevClick" onClick={this.gotoPrev}></i>
		);
	}
	renderArrowNext () {
		if (this.props.currentImage === (this.props.images.length - 1)) return null;

		return (
			<i className='nxt-icn' id="nextClick" onClick={this.gotoNext}></i>
		);
	}
	renderLoader () {
		const { imageLoaded } = this.state;
		return (
			<div className={ !imageLoaded ? 'active' : 'hide'}>
				<div style={{textAlign: 'center', marginTop: '106px', marginBottom: '10px', display: 'block'}} id="loader-id">
					<img border="0" alt="" id="loadingImage1" className="small-loader" style={{borderadius:'50%',height: '40px',width: '40px'}} src="//images.shiksha.ws/pwa/public/images/ShikshaMobileLoader.gif"/>
				</div>
			</div>
		);
	}
	renderLayerHtml()
	{
		const {
			isOpen,
		} = this.props;
		const { imageLoaded } = this.state;
		if (!isOpen)
		{
			return <span key="closed" />;
		}
		PreventScrolling.disableScrolling();
		return ReactDOM.createPortal(
			<div id='gal-layer-z-index' className='gal-layer gal-layer-large bcglayer'>
				{this.renderHeader()}
				<div style={{ height: '-webkit-calc(100% - 80px)',
					width: '100%',
					overflow: 'auto',
					position: 'relative'}}>
					<div className='galry-block opn-glry'>
						{this.renderMedia()}
						{imageLoaded && this.renderTitle()}
						{this.renderLoader()}
					</div>
				</div>
				<div className='slider-count'>
					{this.renderArrowPrev()}
					{this.renderSliderCount()}
					{this.renderArrowNext()}
				</div>
			</div>,
			document.getElementById('gallery-container'),
		);
	}
	renderSliderCount()
	{
		return (
			<span><p className='mediaCount'>{this.props.currentImage +1}</p> of <p className='mediaCount'>{this.props.images.length}</p></span>
		);
	}
	renderMedia()
	{
		const {
			currentImage,
			images
		} = this.props;

		if (!images || !images.length) return null;

		const media = images[currentImage];
		if(media.mediaType == 'photos')
		{
			return this.renderImages();
		}
		else if(media.mediaType == 'video')
		{
			return this.renderVideos();
		}

	}
	renderVideos () {
		const {
			currentImage,
			images
		} = this.props;

		const { imageLoaded } = this.state;

		if (!images || !images.length) return null;

		const image = images[currentImage];

		var self = this;
		var mediaUrl = image.mediaUrl;
		mediaUrl = mediaUrl.replace('youtube.com/v/','youtube.com/embed/');

		return (
			<Swipeable onSwipedLeft={this.gotoNext} onSwipedRight={this.gotoPrev}>
				<div className='swipe-iframe'></div>
				<iframe
					ref={node => {
						if (!node) {
							return;
						}
						const iframe = ReactDOM.findDOMNode(node); // eslint-disable-line  
						iframe.addEventListener('load', () => {
							self.handleImageLoaded();
						});
					}}
					className = { imageLoaded ? 'active aimg'  : 'hide' }
					alt={image.tagName}
					src={mediaUrl + '?autoplay=1&fs=0'}
					style={{
						cursor: 'auto',
						height: '225px'
					}}
					scrolling="yes"
					wmode="transparent"
					type="application/x-shockwave-flash"
				/>
			</Swipeable>
		);
	}

	renderTitle()
	{
		const {
			currentImage,
			images
		} = this.props;

		if (!images || !images.length) return null;

		const image = images[currentImage];

		if(image.mediaTitle && image.mediaTitle.length > 0)
		{
			return (<div className='mediaclass'>
				<p id="mediaTitle" className='mediaTitle'>{image.mediaTitle}</p>
			</div>)
		}
	}

	// swipeHandle(evt, dir, phase, swipetype, distance)
	// {
	// 	console.log('swipetype',evt, dir, phase, swipetype, distance);
	// }

	renderImages () {
		const {
			currentImage,
			images,
		} = this.props;

		const { imageLoaded } = this.state;

		if (!images || !images.length) return null;

		const image = images[currentImage];


		return (
			<figure>
				<Swipeable onSwipedLeft={this.gotoNext} onSwipedRight={this.gotoPrev}>
					<img
						className = { imageLoaded ? 'active aimg' : 'hide' }
						alt={image.tagName}
						src={image.mediaUrl}
						style={{
							cursor:  'auto',
						}}
					/>
				</Swipeable>
			</figure>
		);
	}
	closeLigtbox()
	{
		const {onClose} = this.props;
		onClose();
		PreventScrolling.enableScrolling(true);
	}
	renderHeader () {
		const {
			showCloseButton,
			currentImage,
			images,
			openGalleryList
		} = this.props;

		const image = images[currentImage];

		return (
			<div className='gal-hd'>
				<a className='grid-icon' href="javascript:void(0)" onClick={openGalleryList}></a>
				{ showCloseButton && <a href="javascript:void(0);" onClick={this.closeLigtbox}>×</a>}
				<p className='para-L2' id="galleryHeading">{image.tagName}</p>
			</div>
		);
	}
}

export default GalleryLayer;

GalleryLayer.propTypes = {
	currentImage: PropTypes.any,
	images: PropTypes.any,
	isOpen: PropTypes.any,
	onClickNext: PropTypes.any,
	onClickPrev: PropTypes.any,
	onClose: PropTypes.any,
	openGalleryList: PropTypes.any,
	preloadNextImage: PropTypes.any,
	showCloseButton: PropTypes.any
}