function Puzzle(divId, width, height, src, className){
	this.divId = divId;
	this.width = width;
	this.height = height;
	this.src = src;
	this.className = className;
	this.initialImg = document.getElementById(this.divId);
	this.initialDiv = [];
	this.initialId  = [];
	this.emptyIndex = 0;
}
  
Puzzle.prototype = {
	constructor : Puzzle,
	
	loadPuzzle : function(){
		var self = this;
		var puDiv = document.createElement('div');
			puDiv.id = self.divId;
			puDiv.alt = self.src;
			puDiv.className = self.className;
			puDiv.style.width  = self.width+'px';
			puDiv.style.height = self.height+'px';
			
		var rows, cols, size, w, h;
		size = self.switchSize();
		rows = parseInt(self.width/size) + 1;
		cols = parseInt(self.height/size) + 1;
		
		w = parseInt((self.width -rows*2)/rows);
		h = parseInt((self.height-cols*2)/cols);

		for(var i = 0; i < rows*cols; i++){
			var x,y;

			x = -parseInt(i%rows)*(w+1)-1*parseInt(i%rows)-1;
			y = -parseInt(i/rows)*(h+1)-1*parseInt(i/rows)-1;

			var divPic = document.createElement('div');
			divPic.id = self.divId+'_'+i;
			divPic.style.width = w+'px';
			divPic.style.height = h+'px';
			divPic.style.margin = '1px';
			
			if(i == rows*cols-1){
				divPic.style.background = null;
			}else{
				divPic.style.backgroundImage = 'url('+self.src+')';
				divPic.style.backgroundSize = self.width+'px '+self.height+'px';
				divPic.style.backgroundPosition = x+'px '+y+'px';
			}
			divPic.style.float = 'left';
			divPic.style.cssFloat = 'left';
			divPic.style.overflow = 'hidden'; 
			divPic.style.cursor = 'pointer'; 
			
			//divPic.innerText = divPic.id;
			divPic.onclick = function(){
				var index = 0;
				for(var i = 0; i < rows*cols; i++){
					if(this.parentNode.childNodes[i].id == this.id){
						index = i;break;
					}
				}
				if(index == self.emptyIndex){
					return false;
				}else if(parseInt(index/cols) == parseInt(self.emptyIndex/rows) || index%rows == self.emptyIndex%rows){
					var m = (index%rows == self.emptyIndex%rows) ? rows : 1;
					if(index > self.emptyIndex){
						for(var i = self.emptyIndex; i < index ; i=i+m){
							var domBg = this.parentNode.childNodes[i].style.background;
							var domId = this.parentNode.childNodes[i].id;
							this.parentNode.childNodes[i].style.background = this.parentNode.childNodes[i+m].style.background;
							this.parentNode.childNodes[i].id = this.parentNode.childNodes[i+m].id;
							this.parentNode.childNodes[i+m].style.background = domBg;
							this.parentNode.childNodes[i+m].id = domId;
						}
					}else{
						for(var i = self.emptyIndex; i > index ; i=i-m){
							var domBg = this.parentNode.childNodes[i].style.background;
							var domId = this.parentNode.childNodes[i].id;
							this.parentNode.childNodes[i].style.background = this.parentNode.childNodes[i-m].style.background;
							this.parentNode.childNodes[i].id = this.parentNode.childNodes[i-m].id;
							this.parentNode.childNodes[i-m].style.background = domBg;
							this.parentNode.childNodes[i-m].id = domId;
						}
					}
					self.emptyIndex = index;
					self.checkSuccess();
				}
			};
			divPic.ondblclick = function(){if(confirm('要放弃了吗???'))self.regain();};
								
			self.initialDiv[i] = divPic;
			self.initialId[i]  = divPic.id;
		}
		
		for(var i = 0; i < rows*cols; i++){
			var index = parseInt(Math.random() * self.initialDiv.length);
			if(!self.initialDiv[index].style.background){
				self.emptyIndex = i;
			}
			puDiv.appendChild(self.initialDiv[index]);
			self.initialDiv.splice(index,1);
		}
		puDiv.onclick = function(){return false;};
		self.initialImg.parentNode.replaceChild(puDiv, self.initialImg);
	},
	//检索是否拼图完成
	checkSuccess : function(){
		var ids = document.getElementById(this.divId).getElementsByTagName('div');
		for(var k = 0; k < ids.length; k++){
			if(this.initialId[k] != ids[k].id){
				return false;
			}
		}
		this.regain();
	},
	
	findMover : function(){
		
	},
	
	regain : function(){
		var puDiv = document.getElementById(this.divId);
		puDiv.parentNode.replaceChild(this.initialImg, puDiv);
	},
	
	switchSize : function(){
		var size = (this.width < this.height) ? this.width : this.height; 
		if(size <= 100){
			return 40;
		}else if(size > 100 && size <= 200){
			return 50;
		}else if(size > 200 && size <= 300){
			return 80;
		}else if(size > 300 && size <= 400){
			return 100;
		}else{
			return parseInt(size/4);
		}
	}
};

onload = function(){
	var puzzle_style =  document.createElement('style');
	puzzle_style.type = 'text/css';
	puzzle_style.innerHTML = '.puzzle_over{background:#FF0000;filter:alpha(opacity=50);-moz-opacity:0.5;opacity: 0.3;}.puzzle_overline{background:#FFF;filter:alpha(opacity=50);-moz-opacity:0.5;opacity: 0.5;}'; 
	document.head.appendChild(puzzle_style);

	var Domimgs = document.getElementsByTagName('img');
	for(var index = 0; index < Domimgs.length; index++){
		if(Domimgs[index].width > 100 && Domimgs[index].height > 80 && Domimgs[index].className.indexOf('puzzle') != -1){
			var image = Domimgs[index];
			if(image.id == '' || image.id == undefined){
				image.id = 'Puzzle_div_'+index;
			}

			image.ondblclick = function(){
				new Puzzle(this.id, this.width, this.height, this.src, this.className).loadPuzzle();
			};
			image.onclick = function(){return false;};
		}
	}
};