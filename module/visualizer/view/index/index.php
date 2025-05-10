	<div id="main">
		<div id="map" class="map">
			<div id="toolTip1"></div>
			<div id="toolTip2"></div>
			<div id="toolTip3"></div>
		</div>
		<div id="divResolution" style="width: 10cm;"></div>
		<div id="colDroite" class="colDroite">
			<button id="collapsible" class="collapsible">Réduire</button>
			<div id="collapsibleDiv">				
				<div id="titre" class="div_titre">
					<!--div class="used"><a href="../systeme/conditionsUtilisation.pdf" target="_blank">Conditions d'utilisation</a></div-->
					<div class="used"><a href="module/visualizer/vendor/system/conditionsUtilisation.pdf" target="_blank">Conditions d'utilisation</a></div>
				</div>
				<div id ="tabChoice" class="tab">
					<button id = "choice1" class="tablinks active" title="content1" onclick="editorChoice(this)">Itinéraires</button>
					<button id = "choice2" class="tablinks" title="content2" onclick="editorChoice(this)">Services</button>
					<button id = "choice3" class="tablinks" title="content3" onclick="editorChoice(this)">Impression</button>
					<div id="centreInit">
						<button type="button" onclick="centrageInit()">Centrage initial</button>
					</div>					
				</div>				
				<div id="content1" class="contentChoice">
					<fieldset id="listeTraces"  class="infoItin">
						<legend align="center">Les itinéraires</legend>
							<span id="traces"></span>	
						<button id="toutVoirT" type="button" onclick="visualiserTraces(fichiersTraces)">Tout visualiser</button><br/>
						<span id="afficherT" class="montrer"><button type="button" onclick="afficherTraces()">Tout afficher</button>
						<button type="button" onclick="masquerTraces()">Tout masquer</button></span>
					</fieldset>						
				</div>
				<div id="content2" class="contentChoice">
					<fieldset id="listeServices" class="infoServices">
						<legend align="center">Les services</legend>
							<span id="services"></span>	
						<button id="toutVoirS" type="button" onclick="visualiserServices(fichiersServices)">Tout visualiser</button><br/>
						<span id="afficherS" class="montrer"><button type="button" onclick="afficherServices()">Tout afficher</button>
						<button type="button" onclick="masquerServices()">Tout masquer</button></span>
					</fieldset>						
				</div>
				<div id="content3" class="contentChoice">
					<div id="idImp">
						<fieldset class="idImp">
						<legend align="center">Impression</legend>
							<form name="formImp" action="JavaScript:validImp()">
								<span class="MeP">Mise en page : </span>
								<label><input type="radio" name="orientation" value="portrait" checked/>Portrait</label>
								<label><input type="radio" name="orientation" value="paysage"/>Paysage</label><br/><br/>
								<span class="MeP">Taille : </span>
								<SELECT class="bouton" name="taille" size=1>
								  <OPTION value="A4">A4</OPTION>
								  <OPTION value="A3">A3</OPTION>
								</SELECT>
								<span class="MeP">Echelle : </span>
								<SELECT class="bouton" name="liste" size=1>
								  <OPTION value="1/25000">1/25000</OPTION>
								  <OPTION value="Comme à l'écran">Comme à l'écran</OPTION>
								</SELECT>
								<input class="bouton" name="envoyer" value="Valider" type="submit" />
							</form>
						</fieldset>
					</div>						
				</div>				
			</div>
		</div>
		<div id="prepaImp">
			<p id="boutonsImp">Imprimer carte au 1/25000</p>
			<button id="paysage" type="button" onclick="Paysage()">Paysage</button>
			<button id="portrait" type="button" onclick="Portrait()">Portrait</button>
			<button id="aperImp" type="button" onclick="aperImp()">Aperçu</button>
			<button id="fermerImp" type="button" onclick="fermerImp()">Fermer</button>
		</div>
		<div id="divModif" class="divModif" style="display: none;">
			<p id="boutonsModif"></p>
			<div id="modifStyle"></div>			
			<button id="modifOut" type="button" onclick="sortirModif(); return false;">Fermer</button>
			<button id="modifOk" type="button" onclick="appliquerModif()">Appliquer</button>			
		</div>
		<div id="divImp" class="divImp">
			<button id="cancelImp" type="button" onclick="annulerImp()">Annuler</button>
			<button id="impPage" type="button" onclick="window.print(); return false;">Imprimer</button>
			<div id="impAvert"></div>			
		</div>
		<div id="impMap"></div>
		<div id="aperImp25000">L'aperçu est en cours de préparation ...</div>
	</div>