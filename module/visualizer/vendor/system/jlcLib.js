/* Ce fichier est la librairie javascript de JLC. Elle est essentiellement dédiée à la cartographie sur Internet.
 * Elle contient les classes jlcForm, jlcGraphe et jlcToolTip.
 * 
*/

/** 
 * jlcForm : Class pour formulaire simple, en mode post, avec input
 * 
 * Paramêtres :
 * 	name: nom du formulaire et de sa cible (iframe)
 * 	script: nom du script avec toute son arborescence
 *	inputsNbr: nombre d'inputs
 * 	inputs: caractéristiques des inputs, de la forme :
 * 				[[type, name, value], [type, name, value], ...]
 * 
**/

class jlcForm {
	constructor(param) {
		let name = param['name'] || console.log ('name is not defined');
		let inputsNbr = param['inputsNbr'] || console.log ('Inputs number is not defined');
		
		this.form = document.createElement('form');
		this.form.action = param['script'];
		this.form.method = 'post';
		this.form.style.display = 'none';
		this.form.target = name;
		for ( let i = 0 ; i < inputsNbr; i++) {
			let input = document.createElement('input');
			input.type = param['inputs'][i][0];
			input.name = param['inputs'][i][1];
			input.value = param['inputs'][i][2];
			this.form.appendChild(input);			
		}
		document.body.appendChild(this.form);
		
		let iframe = document.createElement('iframe');
		iframe.name = name;
		iframe.style.display = 'none';
		document.body.appendChild(iframe);
	}

	submit() {
		this.form.submit();//lancement du script
	}	
}

/**
 * jlcGraphe : Class pour dessiner des graphiques d'altitudes
 * 
 * @tutorial https://instructobit.com/tutorial/90/Creating-line-graphs-with-Javascript-using-an-HTML-canvas
 * @tutorial https://usefulangle.com/post/19/html5-canvas-tutorial-how-to-draw-graphical-coordinate-system-with-grids-and-axis
 * @tutorial http://www.fobec.com/tuto/1153/dessiner-graph-sur-canvas-html5.html
 * 
 * @param {string} id - id de l'élément canvas
 * @param {string} window - nom de la fenêre ou le canvas sera - ne rien mettre si fenêtre courante
 * 
**/

class jlcGraphe {
	constructor(param) {		
		let id = param['id'] || console.log ('id is not defined');
		this.win = param['window'];
		
		if (typeof(this.win) == 'object' ) {
			this.canvas = this.win.document.getElementById(id);
		}
		else
		{
			this.canvas = document.getElementById(id);
		}
		this.context = this.canvas.getContext( "2d" ); 
		this.xLeft = 45;
		this.yTop = 40;
		this.xRight = 15;
		this.yBottom = 40;
		this.canvasW = this.canvas.width;
		this.canvasH = this.canvas.height;
		this.graphWidth = this.canvasW - this.xLeft - this.xRight;
		this.graphHeight = this.canvasH - this.yTop - this.yBottom;
		this.pointsUsed = [];
		
		// X et Y axes
		this.context.beginPath();
		this.context.strokeStyle = "#696969";
		this.context.lineWidth = 3;
		this.context.moveTo( this.xLeft + this.graphWidth , this.yTop + this.graphHeight);  
		this.context.lineTo( this.xLeft, this.yTop + this.graphHeight );  
		this.context.lineTo( this.xLeft, this.yTop );  
		this.context.stroke();
		// labels principaux x et y
		this.context.textAlign = "center";
		this.context.font='normal 12px Arial';
		this.context.fillStyle = '#2F4F4F';
		this.context.fillText('Altitude', this.xLeft/2, 15);// label principal y
		this.context.fillText('(m)', this.xLeft/2, 30);// label principal y
		this.context.fillText('Distance (km)', this.canvasW/2, this.canvasH - 5);// label principal x
	}
	
	addGraph(dist,alt,lonTrace, stepInc, seuil) {
		
		let altMin = Math.min.apply(null, alt);
		let altMax = Math.max.apply(null, alt);
		let arrayLen = alt.length;		
		
		// détermination échelle en x
		this.xScale = this.graphWidth / lonTrace;// pixels par km

		let xStep = 1;// en km, distance entre chaque point label en x
		if ( lonTrace > 25 ) {
			xStep = 2;
		}
		if ( lonTrace > 50 ) {
			xStep = 5;
		}
		if ( lonTrace > 100 ) {
			xStep = 10;
		}
		if ( lonTrace > 200 ) {
			xStep = 15;
		}
		let nbreXSteps = parseInt(lonTrace / xStep);
		
		// label x
		this.context.textAlign = "center";// ces 3 lignes à suivre : pour label x
		this.context.font='normal 12px Arial';
		this.context.fillStyle = '#2F4F4F';
		for (let i = 0; i < nbreXSteps + 1; i++) {
			this.context.fillText(i * xStep, this.xLeft + i * xStep * this.xScale, 20 + this.yTop + this.graphHeight);
		}
		// points des label en x
		this.context.fillStyle = '#2F4F4F';
        for (let i = 0; i < nbreXSteps + 1; i++) {
             this.context.beginPath();
             this.context.arc(this.xLeft + i * xStep * this.xScale, this.yTop + this.graphHeight, 3, 0, Math.PI * 2);
             this.context.fill();
        }		
		
		// calcul des extrèmes en y
		let yStep = 100;// en mètres
		if ( altMax - altMin > 1000 ) {
			yStep = 200;
		}
		this.yMin = -1;
		let yMax = -1;
		let i = 1;
		let j = 1;
		while (this.yMin == -1){
			if (altMin < yStep * i){
				this.yMin = yStep * (i - 1);
			}
			i++;
		}
		while (yMax == -1){
			if (altMax <= yStep * j){
				yMax = yStep * j;
				//console.log(altMax +', '+ yMax);
			}
			j++
		}
		let deltaY = yMax - this.yMin;
		
		// 	détermination échelle en y	
		this.yScale = this.graphHeight / deltaY;// pixels par mètre
		
		let nbreYSteps = deltaY / yStep;
		let arrayYSteps = [];
		if (nbreYSteps > 1) {
			for (let i = 0; i < nbreYSteps; i++) {
				arrayYSteps.push(this.yMin + yStep * (i+1));
			}			
		}
		else
		{
			arrayYSteps = [50, yMax];
		}
		
		// Lignes x intermediaires, this.yMin est la référence zéro du graphe
		let arrayYStepsLen = arrayYSteps.length;
		this.context.textAlign = "right";// ces 3lignes à suivre : pour label y
		this.context.font='normal 12px Arial';
		this.context.fillStyle = '#2F4F4F';
		for (let i = 0; i < arrayYStepsLen; i++) {
			this.context.beginPath();
			this.context.strokeStyle = '#6495ED';
			this.context.lineWidth = 1;
			this.context.moveTo( this.xLeft, this.yTop + this.graphHeight - (arrayYSteps[i] - this.yMin) * this.yScale );
			this.context.lineTo( this.xLeft + this.graphWidth, this.yTop + this.graphHeight - (arrayYSteps[i] - this.yMin) * this.yScale );
			this.context.stroke();
			
			// label y
			this.context.fillText(arrayYSteps[i], this.xLeft - 10, 3 + this.yTop + this.graphHeight - (arrayYSteps[i] - this.yMin) * this.yScale);
		}
		// label y de la réference zéro
		this.context.fillText(this.yMin, this.xLeft - 10, 3 + this.yTop + this.graphHeight);
		
		// construction de la courbe altimétrique, this.yMin est la référence zéro du graphe
		this.courbe = this.canvas.getContext( "2d" );// contexte dédié
		this.courbe.beginPath();
		this.courbe.lineJoin = "round";  
		this.courbe.strokeStyle = '#DF4926'; 
		this.courbe.lineWidth = 4;
		// premier point de la courbe  
		this.courbe.moveTo( this.xLeft, this.yTop + this.yMin * this.yScale + this.graphHeight - alt[ 0 ] * this.yScale );
		this.pointsUsed [0] = [this.xLeft, this.yTop + this.yMin * this.yScale + this.graphHeight - alt[ 0 ] * this.yScale,dist[0]];
		// les autres points
		let n = 1;
		let altRef = alt[0];
		for( let i = 1; i < arrayLen - 1; i += 1 ){// le dernier point n'est pas pris en compte car fait ci-dessous systématiquement
			const deltaAlti = alt[i] - altRef;
			if( Math.abs(deltaAlti) > seuil) {// affichage si la diff est supérieure au seuil
				let x = this.xLeft + this.xScale * dist[i];
				let y = this.yTop + this.yMin * this.yScale + this.graphHeight - alt[i] * this.yScale;
				let z = dist[i];
				this.courbe.lineTo(x, y);
				this.pointsUsed [n] = [x, y, z];
				altRef = alt[i];
				n += 1;
			}
		}
		// dernier point de la courbe  
		this.courbe.lineTo( this.xLeft + this.graphWidth, this.yTop + this.yMin * this.yScale + this.graphHeight - alt[ arrayLen - 1 ] * this.yScale );
		this.pointsUsed [n] = [this.xLeft + this.graphWidth, this.yTop + this.yMin * this.yScale + this.graphHeight - alt[ arrayLen - 1 ] * this.yScale, dist[arrayLen - 1]];
		// affichage de la coubre 
		this.courbe.stroke();
	}
		
	moveOnTrk(id,action1,action2) {
		
		var xLeft = this.xLeft;
		var yTop = this.yTop;
		var graphHeight = this.graphHeight;
		var graphWidth = this.graphWidth;
		var win = this.win;
		var canvas = this.canvas;
		var courbe = this.courbe;
		var xScale = this.xScale;
		var yScale = this.yScale;
		var yMin = this.yMin;
		
		let canvas2;			
		if (typeof(win) == 'object' ) {
			canvas2 = win.document.getElementById(id);
		}
		else
		{
			canvas2 = document.getElementById(id);
		}
		let canvasMouse = canvas2.getContext( "2d" );// contexte dédié
				
		this.canvas.addEventListener('mouseout', function(event) {
			action1();
			canvasMouse.clearRect( 0, 0, canvas2.width, canvas2.height );
		});
		
		this.canvas.addEventListener('mousemove', function(event) {
			let limites = canvas2.getBoundingClientRect();// position du canvas dans la fénêtre
			const xRect = limites.left;// coord x coin haut gauche dans la fenêtre 
			const yRect = limites.top;// coord y coin haut gauche dans la fenêtre
			let x = event.clientX - xRect;
			let y = event.clientY - yRect;
			
			if (courbe.isPointInStroke(x, y)) {// dessin des parallèles en x et y			
				action1();
				canvasMouse.clearRect( 0, 0, canvas2.width, canvas2.height );
				let pointX = ((x - xLeft) / xScale).toFixed(2);
				let pointY = ((graphHeight + yTop - y) / yScale + yMin).toFixed(0);
				if (pointX >= 0 && pointY >= 0) {
					action2(pointX,pointY);
					// valeurs pointX et pointY à afficher
					canvasMouse.textAlign = "start";
					canvasMouse.fillStyle = '#a52a2a';
					canvasMouse.font='bold italic 14px Arial';
					canvasMouse.fillText(pointY + ' m', xLeft + graphWidth / 2 + 3, yTop - 10);// afficher pointY
					canvasMouse.textAlign = "end";
					canvasMouse.fillStyle = '#a52a2a';
					canvasMouse.font='bold italic 14px Arial';
					canvasMouse.fillText(pointX + ' km,', xLeft + graphWidth / 2 - 3, yTop - 10);// afficher pointX
					
					// ligne y parallèle axe y
					canvasMouse.beginPath();
					canvasMouse.strokeStyle = "#3cb371";
					canvasMouse.lineWidth = 2;
					canvasMouse.moveTo(x, yTop);  
					canvasMouse.lineTo(x, yTop + graphHeight);
					canvasMouse.stroke();
					  
					// ligne x parallèle axe x
					canvasMouse.beginPath();
					canvasMouse.strokeStyle = "#3cb371";
					canvasMouse.lineWidth = 2;
					canvasMouse.moveTo(xLeft, y);  
					canvasMouse.lineTo(xLeft + graphWidth, y);
					canvasMouse.stroke();
				}
			  }
			  else
			  {
				action1();
				canvasMouse.clearRect( 0, 0, canvas2.width, canvas2.height );
			  }			
		});
	}
	
	followLine(id,action1,action2) {
		let canvas3;			
		if (typeof(this.win) == 'object' ) {
			canvas3 = this.win.document.getElementById(id);
		}
		else
		{
			canvas3 = document.getElementById(id);
		}
		
		let lineToFollow = canvas3.getContext("2d");// contexte dédié
		lineToFollow.fillStyle = '#3cb371';
		let pointsNbr = this.pointsUsed.length;
		let pointsUsed = this.pointsUsed;
		let pointTime = parseInt(8000 / pointsNbr);
		if (pointsNbr < 50) {
			pointTime = 200;
		}
		
		let i= 0;		
		followAction(i);
		function followAction(i) {
			//followLine.clearRect( 0, 0, canvas3.width, canvas3.height );
			action1();
			action2(pointsUsed[i][2].toFixed(2),0);
			lineToFollow.beginPath();
			lineToFollow.arc(pointsUsed[i][0], pointsUsed[i][1], 5, 0, Math.PI * 2);
			lineToFollow.fill();
			i++;
			if (i ==  pointsNbr) {
				setTimeout(function(){ 
							lineToFollow.clearRect( 0, 0, canvas3.width, canvas3.height );
							action1();
						},
					25
				);
				return;
			}
			//setTimeout(followAction,25,i);
			setTimeout(function(){ 
						lineToFollow.clearRect( 0, 0, canvas3.width, canvas3.height );
						followAction(i);
					},
				//75
				pointTime
			);
		}
	}	

	clear() {
		this.context.clearRect( 0, 0, this.canvasW, this.canvasH );
	}
}


/**
 * jlcToolTip : Class pour ToolTip (Infobulle)
 * 
 * Cette class est associée au fichier infobulle.css
 * Paramêtres :
 * 	id: id de la div tooltip
 * 	idRecept: id de la div où le toolTip s'affichera
 * 
**/

class jlcToolTip {
	constructor(param) {		
		let id = param['id'];
		let idRecept = param['idRecept'];
		this.top = -20;
		this.toolTipDivLeft = -10;
		this.toolTipRecept = document.getElementById(idRecept);
		this.hideInfo = true;
		
		this.toolTipDiv = document.createElement('div');
		this.toolTipDiv.setAttribute('id',id);
		this.infoContent = document.createElement('div');
		this.infoContent.setAttribute('id',id + '_cont');
		this.toolTipDiv.appendChild(this.infoContent);
		this.toolTipRecept.appendChild(this.toolTipDiv);// toolTipDiv dans div map pour affichage en plein écran
		//document.body.appendChild(this.toolTipDiv);
		this.toolTipDiv.style.display = 'none';	
		
		var toolTipDiv = this.toolTipDiv;
		var toolTipDivLeft = this.toolTipDivLeft;
		var toolTipDivOffsetHT = parseInt(this.toolTipDiv.offsetHeight) + this.top;
		this.displayToolTip = function(e) {
			console.log(e);
			//let u = e.pageY;
			//let l = e.pageX;
			let u = e.layerX;
			let l = e.layerY;
			toolTipDiv.style.top = (u - toolTipDivOffsetHT) + 'px';
			toolTipDiv.style.left = (l + toolTipDivLeft) + 'px';
		};
		//this.active();		
	}

	show(val) {
		if (this.hideInfo == true){
			this.toolTipRecept.addEventListener('mousemove', this.displayToolTip);
			this.hideInfo = false;
		}
		this.infoContent.innerHTML = val;
		this.toolTipDiv.style.width = 'auto';
		this.toolTipDiv.style.display = 'block';
	}
	
	hide() {
		this.toolTipDiv.style = '';
		this.toolTipDiv.style.display = 'none';
		this.infoContent.innerHTML = '';
		this.toolTipRecept.removeEventListener('mousemove', this.displayToolTip);
		this.hideInfo = true;
	}
}
