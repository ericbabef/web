<?php $title = 'Manzé créol'; ?>

<?php ob_start(); ?>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
	<div class="navbar-header">
	  <div class="navbar-icon-container">
		<a href="#" class="navbar-icon pull-right visible-xs" id="nav-btn"><i class="fa fa-bars fa-lg white"></i></a>
	  </div>
	  <!--<a class="navbar-brand" href="#">Manzé créol</a>-->
	  <img class="navbar-brand" src='public/images/RdM-standard.jpg'><a class="navbar-brand" href="#">Manzé créol</a>
	</div>
	<div class="navbar-collapse collapse">
	  <form class="navbar-form navbar-right hidden-xs" role="search">
		<div class="form-group has-feedback">
			<input id="searchbox" type="text" placeholder="Ex : Paris" class="form-control">
			<span id="searchicon" class="fa fa-search form-control-feedback"></span>
		</div>
	  </form>
	  <ul class="nav navbar-nav">
		<li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="about-btn"><i class="fa fa-question-circle white"></i>&nbsp;&nbsp;A propos</a></li>
		<li class="dropdown">
			<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-share white"></i>&nbsp;&nbsp;Partage<b class="caret"></b></a>
			<ul class="dropdown-menu" style="padding: 10px; min-width: 100px;">
			  <li>
				<div id="fb-root"></div>
				<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-share-button" data-layout="button_count" data-href="http://ericbabef.alwaysdata.net/webmapping/rdm/"></div>
			  </li>
			</ul>
		</li>
	  </ul>
	</div><!--/.navbar-collapse -->
  </div>
</div>

<div id="container">
  <div id="map"></div>
  
  <form class="navbar-form navbar-right mapsearch visible-xs" role="search">
		<div class="form-group has-feedback">
			<input id="searchbox2" type="text" placeholder="Ex : Paris" class="form-control">
			<span id="searchicon" class="fa fa-search form-control-feedback"></span>
		</div>
  </form>
  
</div>
<div id="loading">
  <div class="loading-indicator">
	<div class="progress progress-striped active">
	  <div class="progress-bar progress-bar-info progress-bar-full"></div>
	</div>
  </div>
</div>

<div class="modal fade" id="aboutModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Bienvenue sur Manzé créol !</h4>
		
	  </div>
	  <div class="modal-body">
		<ul class="nav nav-tabs" id="aboutTabs">
		  <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-question-circle"></i>&nbsp;A propos du project</a></li>             
		  <li><a href="#metadonnees" data-toggle="tab"><i class="fa fa-exclamation-circle"></i>&nbsp;Metadonnées</a></li>
		  <li><a href="#remerciements" data-toggle="tab"><i class="glyphicon glyphicon-ok"></i>&nbsp;Remerciements</a></li>
		  
		</ul>
		<div class="tab-content" id="aboutTabsContent" style="padding-top: 10px;">
		  <div class="tab-pane fade active in" id="about">
			<p>Géolocalisation des établissements proposant des produits réunionnais en métropole</p>
			<div class="panel panel-primary">
			  <div class="panel-heading">Utilisation de l'application</div>
			 <ul class="list-group">
				<li class="list-group-item">Rechercher un établissement en utilisant le formulaire de recherche. Utiliser des mots clés comme la ville ou le nom de l'établissement.</li>
				<li class="list-group-item">Vous pouvez vous géolocaliser (en activant votre GPS), et ainsi voir les établissements proches.</li>
				<li class="list-group-item">La fenêtre explicative s'adapte en fonction de la taille de l'écran. Ainsi, sur écran réduit, des boutons apparaissent. Vous pourrez donc téléphonner, envoyer des mails, ou consulter le site web de l'établissement si la donnée est renseignée.</li>
			 </ul>
			</div>
		  </div>
		
			<div id="metadonnees" class="tab-pane fade text-danger">
				<h4>Localisation des établissements  </h4>
				<p><a href="http://www.reunionnaisdumonde.com/" target="_blank">Réunionnais du monde</a></p>
				<p>Je ne garantie pas la fiabilité de ces données, elles ont été extraites du site, puis géocodées. Ce processus, si l'adresse est inexacte ou imprésise, retournera une localisation approximative. </p>
			</div>
		  
		  <div class="tab-pane fade" id="remerciements">
			<div class="panel panel-primary">
			  <div class="panel-heading">Remerciements</div>
			 <ul class="list-group">
				<li class="list-group-item">Géocodage en masse : <a href="http://www.batchgeocodeur.mapjmz.com/" target="_blank">Batch geocodeur</a></li>
				<li class="list-group-item">Template : <a href="https://github.com/bmcbride/bootleaf" target="_blank">bootleaf</a></li>
			 </ul>
			</div>
		  </div>
		  
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	  </div>
	</div>
  </div>
</div>

<div class="modal fade" id="featureModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-primary" id="feature-title"></h4>
			</div>
			<div id="feature-info" class="modal-body"></div>
			<div class="modal-footer">
				<div class="btn-group btn-group-justified visible-xs" role="group" aria-label="Actions">
					<a id="callBtn" class="btn btn-default" role="button" aria-label="Appeler"><i class="glyphicon glyphicon-earphone"></i><span class="hidden-xs"> Appeler</span></a>
					<a id="mailBtn" class="btn btn-default" role="button" aria-label="Email"><i class="glyphicon glyphicon-envelope"></i><span class="hidden-xs"> Email</span></a>
					<a id="lienBtn" class="btn btn-default" role="button" aria-label="Site Web" target="_blank"><i class="glyphicon glyphicon-globe"></i><span class="hidden-xs"> Site Web</span></a>
					<!--<a id="ItineraireBtn" class="btn btn-default" role="button" aria-label="Itineraire"><i class="glyphicon glyphicon-screenshot"></i><span class="hidden-xs"> Itineraire</span></a>-->
				</div>
				<button type="button" class="btn btn-default hidden-xs" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Fermer</button>
			</div>
		</div>
	</div>
</div><!-- /popupmap -->

<div class="modal fade" id="attributionModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button class="close" type="button" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">
		  Réalisée par <a href='http://ericbabef.alwaysdata.net' target='_blank'>Eric Babef</a><br />Source : <a href='http://www.reunionnaisdumonde.com/' target='_blank'>Réunionnais du monde</a>
		</h4>
	  </div>
	  <div class="modal-body">
		<div id="attribution"></div>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>