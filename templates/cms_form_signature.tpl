
<div class="col-md-8 col-sm-12 col-md-offset-2 signature-text">
	{if {$currentOrg['label']}=='IHA'}<div id="IHA">
			<h3> Privacy declaration of IHA </h3><br />
			<p>IHA collects personal data needed for you to participate in our distributions and activities. This includes:</p>
			<ul>
			<li>Name</li>
			<li>Date of Birth</li>
			<li>Nationality </li>
			<li>Address  </li>
			<li>Gender</li>
			</ul>
			<p>We are committed to protecting your privacy rights. You have the right to access, delete or change your personal data at any time. You can learn more about our privacy policies here: <a href="url"> www.iha.help/en/data-privacy</a></p>
			<p>I, <strong>{$data['firstname']} {$data['lastname']}</strong>, agree to my personal data being collected and processed for this purpose.<br />
			I also agree that my family ́s personal data is being collected and processed.</p>
		</div>
	{else}
	<ul class="nav nav-tabs">
		<li class = "active"><a href="#languagetab_en" data-toggle="tab">English</a></li>
		<li><a href="#languagetab_fr" data-toggle="tab">Français</a></li>
		<!-- <li><a href="#languagetab_ar" data-toggle="tab">العربية</a></li>
		<li><a href="#languagetab_so" data-toggle="tab">سۆرانی</a></li>
		<li><a href="#languagetab_fa" data-toggle="tab">فارسی</a></li> -->
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in active" id="languagetab_en">
			<h3>Information about new privacy policies</h3><br />
			<p>{$currentOrg['label']} wish to reassert you that we are protecting your private information, with reference to the new privacy policies that applies to EU/EES countries.</p>
			<p>For refugees that receive aid/assistance/ from {$currentOrg['label']} (clothes/shoes/food/other assistance or activities), the following information is retained:</p>
			<ul>
			<li>Name</li>
			<li>Age, (date of birth)</li>
			<li>Nationality (in some cases)</li>
			<li>Address (e.g. container number in the refugee camp)</li>
			<li>Telephone number (in some cases)</li>
			<li>Gender</li>
			</ul>
			<p>You do on your own provide us with your personal information in conversation with representatives from {$currentOrg['label']}. We need this information to ensure that you are part of our distribution and/or other services provided by the organisation, and to make sure that we have enough equipment for our beneficiaries.</p>
			<p>{$currentOrg['label']} does not share your personal information with other parties.</p>
			<p>To access the mentioned services provided by {$currentOrg['label']}, you must agree that we can continue to process and retain this information about you.</p>
			<p>I, <strong>{$data['firstname']} {$data['lastname']}</strong>, agree that my personal information is stored and processed as described in the Privacy Policy of {$currentOrg['label']}.</p><p>I also agree that my family ́s personal information is stored and processed as described above.</p>
		</div>
		<div class="tab-pane fade" id="languagetab_fr">
			<h3>Informations sur les nouvelles règles de confidentialité</h3><br />
			<p>‘{$currentOrg['label']}’ souhaite vous réaffirmer que nous protégeons vos données privées, conformément nouvelles règles de confidentialité applicables aux pays de UE/EEA.</p>
			<p>Pour les réfugiés qui reçoivent aide/assistance de ‘{$currentOrg['label']}’ (vêtements/ chaussures/nourriture/autre assistance ou activités), les données suivantes sont gardées:</p>
			<ul>
			<li>Nom</li>
			<li>Âge</li>
			<li>Nationalité</li>
			<li>Adresse (par exemple: numéro de conteneur dans le camp de réfugiés)</li>
			<li>Numéro de téléphone (dans certains cas)</li>
			<li>Le genre (dans certain cas)</li>
			</ul>
			<p>Si vous choisissez de communiquer des données personnelles, vous acceptez l’utilisation de ces données par des représentants de ‘{$currentOrg['label']}’. Nous avons besoin de ces données pour assurer que vous faites partie de notre distribution et/ou des autres services fournis, et pour assurer que nous avons suffisamment d'équipement pour nos bénéficiaires.</p>
			<p>‘{$currentOrg['label']}‘ ne partage pas des données personnelle avec des tierces parties.</p>
			<p>Pour accéder aux services mentionnés fournis par ‘{$currentOrg['label']}’ vous devez accepter que nous pouvons continuer à traiter et garder ces données à votre sujet.</p>
			<p>Je suis d’accord pour que mes données personnelles soient gardées et traitées comme décrit dans les règles de confidentialité de ‘{$currentOrg['label']}’.</p>
			<p>J’approuve également que les données personnelles de ma famille soient stockées et traitées ainsi que décrit ci-dessus</p>
		</div>
		
		<div class="tab-pane fade" id="languagetab_ar">
		</div>
		<div class="tab-pane fade" id="languagetab_so">
		</div>
		<div class="tab-pane fade" id="languagetab_fa">
		</div>
	</div>
	{/if}
	<div class="fc"></div>
	<div id="sig" ></div>
	<p style="clear: both;">
		<button id="clear">Clear</button> 
	</p>
	<textarea name="signaturefield" id="signaturefield" class="hidden">{$data[$element['field']]}</textarea>
	</div>
