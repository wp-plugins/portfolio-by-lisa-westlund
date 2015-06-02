"use strict"

var WplwGallery = {
	
	anchors: document.getElementsByClassName('portfolio-piece-anchor'),
	isImageOpen: false,
	wrapperPos: 0,
	smallColumn: false,
		
	init: function(){		

		var i = 0;
		var j = 0;
		var idNumber = 0;
		//WplwGallery.checkPortfolioWrapperWidth();
		
		for (j = 0; j < WplwGallery.anchors.length; j++){
			WplwGallery.anchors[j].onmouseover = function(){
				WplwGallery.checkPortfolioWrapperWidth();
				var metaNumber = this.getAttribute('id');
				
				if(WplwGallery.smallColumn === true){	
					document.getElementById(metaNumber).lastChild.setAttribute('style', 'width:150%; height:150%; z-index:1; margin-left:-25%; margin-top:-25%;');
				}
				
				else{
					document.getElementById(metaNumber).lastChild.removeAttribute('style');
				}
			}
		}

		//Loop through images and set onlick events		
		for (i = 0; i < WplwGallery.anchors.length; i++){
			
			idNumber ++;
			WplwGallery.anchors[i].setAttribute('id', idNumber);
		
		/*	if(WplwGallery.smallColumn === true){
				document.getElementsByClassName('portfolio-piece')[i].setAttribute('style', 'max-width:100%; width:100%');
			}
		*/	
			var portfolioAnchor = WplwGallery.anchors[i];
			
			portfolioAnchor.onclick = function(e){
				
				e.preventDefault();
				
				//Checks window width
				if(window.innerWidth > 594){	
					
					if(WplwGallery.isImageOpen === true){
						WplwGallery.closeLargeImage();
					}
					
					var imageNumber = this.getAttribute('id');
					var imageElement = this.getElementsByTagName('img');
					var imgSource = imageElement[0].getAttribute('src');
			
					WplwGallery.isImageOpen = true;
	
					WplwGallery.viewLargeImage(imgSource, imageNumber);
				
				}
				
				else{
					return false;
				}				
			};	
		}	
			
	},
	
	viewLargeImage: function(imgSource, imageNumber){
		
		//Create nodes
		var image = document.createElement('img');
		image.setAttribute('src', imgSource);
		image.setAttribute('id', 'large-image');
		
		var closeWindowAnchor = document.createElement('a');
		closeWindowAnchor.setAttribute("href", "#");
		
			//Close window onclick
			closeWindowAnchor.onclick = function(e){
				e.preventDefault();
				WplwGallery.closeLargeImage();
			};
		
		var largeImage = document.createElement('div');
		largeImage.className = 'large-portfolio-piece';
		
		//Position popup window
		var scrollPos = window.pageYOffset + 70;
		WplwGallery.wrapperPos = document.getElementById('portfolio-wrapper').getBoundingClientRect().top + scrollPos;
		var pos = scrollPos - WplwGallery.wrapperPos + 150;		
		largeImage.setAttribute('style', 'top:' + pos  + 'px;');		
		
		//Insert nodes in document
		largeImage.appendChild(image);
		largeImage.appendChild(closeWindowAnchor);
		
		//Insert popup after clicked image
		document.getElementById('portfolio-wrapper').appendChild(largeImage);
		var popup = document.getElementsByClassName('large-portfolio-piece')[0];
		var insertBeforeID = parseInt(imageNumber);
		document.getElementById('portfolio-wrapper').insertBefore(popup, document.getElementById('portfolio-wrapper').childNodes[insertBeforeID]);
	},
	
	closeLargeImage: function(){
		var largeImage = document.getElementsByClassName('large-portfolio-piece')[0];
		document.getElementById('portfolio-wrapper').removeChild(largeImage);
	
		WplwGallery.isImageOpen = false;
			
	},
	
	checkPortfolioWrapperWidth: function(){
		if(parseFloat(window.getComputedStyle(document.getElementById('portfolio-wrapper')).width) < 594 && window.innerWidth > 1025){
			WplwGallery.smallColumn = true;
		}
		
		else{
			WplwGallery.smallColumn = false;
		}
	}
};

window.onload = WplwGallery.init;