import React from 'react';


const GalleryAmp = (props)=>{
  var data = props.data;
  var totalcount = 0;
  var videos = typeof data.videos != 'undefined' || data.videos.length !=0 ? data.videos : '';
  var photos = typeof data['photos'] != 'undefined' || data['photos'].lenght !=0 ? data['photos'] : '';
  var photoSections =  typeof data['photoSections'] != 'undefined' || data['photoSections'].lenght !=0 ? data['photoSections']: '';


  const mediacount = () => {
   var SectionObjLength = 0;
   var imghtml=[];
   var photoSections =  data['photoSections'];
   var photos = typeof data['photos'] != 'undefined' ? data['photos']: '';
   var videos = typeof data['videos'] != 'undefined' ? data['videos'] : '';
   if(photoSections != 'undefined' && photoSections.length > 0){
     for(var i in photoSections){
        SectionObjLength = photos[photoSections[i]];
        totalcount += SectionObjLength.length;
     }

     totalcount += videos.length;
     return (
       <h2 class="color-3 f16 heading-gap font-w6">Gallery <span class="pos-rl color-9 f12">(Showing 1 of {totalcount}  Photos {  Object.keys(videos).length > 0 ? '& Videos' : ''} )</span></h2>
     )
   }
 }

const medialAlbums = ()=>{
  var imagecardsarray = [];
  if(photoSections){
      for(var i in photoSections){
         photos[photoSections[i]].map((item) => {
             imagecardsarray.push(
               <div class="slide">
                  <amp-img class="no-out" src={item.mediaUrl} on="tap:lightbox11" role="button" tabindex="0" width="400" height="300" layout="responsive" alt="a sample image"></amp-img>
                  <div class="caption gal-cap">
                      {photoSections[i]} - { item.mediaTitle }
                  </div>
               </div>
             )
      })
    }
    if(videos){
      for(var i in videos){
           var videoUrl = videos[i]['mediaUrl'].split('/v/');
          imagecardsarray.push(
            <div class="slide">
                <amp-youtube class="no-out"
                    data-videoid={videoUrl[1]}
                    layout="responsive"
                    width="400" height="300"></amp-youtube>
            </div>
          )

        }
      }
  }
  return imagecardsarray;
}

const videoThumbs = ()=>{
  if(videos){
    for(var i in videos){
         var videoUrl = videos[i]['mediaUrl'].split('/v/');
        return(
          <div class="slide">
              <amp-youtube class="no-out"
                  data-videoid={videoUrl[1]}
                  layout="responsive"
                  width="400" height="300"></amp-youtube>
          </div>
        )

      }
    }
}


const sliderThumbs = ()=> {
 var sliderimgsarray = [];
   if(photoSections){
     var myIndexCount = 0;
      for(var i in photoSections){
         photos[photoSections[i]].map((item, index) => {
           sliderimgsarray.push(
             <button on={`tap:carousel-with-carousel-preview.goToSlide(index=${myIndexCount})`}>
              <amp-img src={`${item.thumbUrl}`} width="60" height="40" layout="responsive" alt="a sample image"></amp-img>
             </button>
           )
           myIndexCount++;
         })
      }
      if(videos){
          for(var i in videos){
             var videothumb = videos[i]['thumbUrl'];
              sliderimgsarray.push(
                <button on={`tap:carousel-with-carousel-preview.goToSlide(index=${myIndexCount})`}>
                  <amp-img src={`${videothumb}`} width="60" height="40" layout="responsive" alt="a sample image"></amp-img>
                </button>
              )
              myIndexCount++;
            }
          }
     }
     return sliderimgsarray;
}



 return(
   <section class="">
      {mediacount()}
      <div class="data-card m-5btm">
      <div class="card-cmn color-w">
         <div>
            <div>

               <amp-carousel id="carousel-with-carousel-preview" class="m-n ga-analytic" data-vars-event-name="GALLERY" layout="responsive" width="400" height="300" type="slides">
                {medialAlbums()}
               </amp-carousel>

               <amp-carousel class="carousel-preview ga-analytic" data-vars-event-name="GALLERY" width="auto" height="48" layout="fixed-height" type="carousel">
                  {sliderThumbs()}
              </amp-carousel>
            </div>
         </div>
         </div>
      </div>
      <amp-image-lightbox id="lightbox11" layout="nodisplay"></amp-image-lightbox>
   </section>
 )
}

export default GalleryAmp;
