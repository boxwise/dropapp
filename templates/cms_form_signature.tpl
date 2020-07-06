
<div class="col-md-8 col-sm-12 col-md-offset-2 signature-text">
	{if {$currentOrg['label']}=='IHA'}
		<div id="IHA">
			<ul class="nav nav-tabs">
				<li class = "active"><a href="#languagetab_en" data-toggle="tab">English</a></li>
				<li><a href="#languagetab_ar" data-toggle="tab">العربية</a></li>
				<li><a href="#languagetab_km" data-toggle="tab">Kurmancî</a></li>
				<!-- <li><a href="#languagetab_so" data-toggle="tab">سۆرانی</a></li>
				<li><a href="#languagetab_fa" data-toggle="tab">فارسی</a></li> -->
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="languagetab_en">
					<h3> Privacy declaration of IHA </h3><br />
					<p>IHA collects personal data needed for you to participate in our distributions and activities. This includes:</p>
					<ul>
						<li>Name</li>
						<li>Date of Birth</li>
						<li>Nationality </li>
						<li>Address  </li>
						<li>Gender</li>
					</ul>
					<p>We are committed to protecting your privacy rights. You have the right to access, delete or change your personal data at any time. You can learn more about our privacy policies here: <a href="https://www.iha.help/en/data-privacy"> www.iha.help/en/data-privacy</a></p>
					<p>I, <strong>{$data['firstname']} {$data['lastname']}</strong>, agree to my personal data being collected and processed for this purpose.<br />
					I also agree that my family's personal data is being collected and processed.</p>
				</div>
				<div class="tab-pane fade" id="languagetab_ar" dir="rtl">
					<p>
	تجمع IHA البيانات الشخصية اللازمة لكي تشارك في عمليات التوزيع والأنشطة الخاصة بنا.
	هذا يشمل:
					</p>
					<ul>
						<li>
	اسم
						</li>
						<li>
	تاريخ الولادة
						</li>
						<li>
	جنسية
						</li>
						<li>
	عنوان
						</li>
						<li>
	ذكر أو أنثى 
						</li>
					</ul>
					<p>
	نحن ملتزمون بحماية حقوق الخصوصية الخاصة بك.  لديك الحق في الوصول إلى أو حذف أو تغيير بياناتك الشخصية في أي وقت.  يمكنك معرفة المزيد حول سياسات الخصوصية الخاصة بنا هنا: <a href="https://www.iha.help/ar/data-privacy"> www.iha.help/ar/data-privacy</a>
					</p>
					<p>
	أوافق (الاسم) على جمع بياناتي الشخصية ومعالجتها لهذا الغرض.
	أوافق أيضًا على أن البيانات الشخصية لعائلتي يتم جمعها ومعالجتها.
					</p>
				</div>
				<div class="tab-pane fade" id="languagetab_km">
					<p>IHA daneyên kesane yên ku ji bo beşdarî di belavkirin û çalakiyên me de hewce dike kom dike. Ev tê de ye: </p>
					<ul>
						<li>Nav</li>
						<li>Dîroka jidayikbûnê</li>
						<li>Netewbûn</li>
						<li>Navnîşan</li>
						<li>Seks</li>
					</ul>
					<p>Em soz didin ku mafê we yê nepenî biparêzin.  Mafê we heye ku di her kêliyê de bigihîje, jêbirin an biguhezin daneyên kesane.  Hûn dikarin di derheqê polîtîkayên nepeniya me de bêtir fêr bibin: <a href="https://www.iha.help/en/data-privacy"> www.iha.help/en/data-privacy</a></p>
					<p>Ez razî me, <strong>{$data['firstname']} {$data['lastname']}</strong>, da ku ji bo vê armancê daneyên kesane yên min kom bike û Armanc bike. </br>
					Ez di heman demê de bipejirînim ku daneyên kesane yên malbata min hatine komkirin û pêvajoyê ne.</p>
				</div>
			</div>
		</div>	
	{else}
	<ul class="nav nav-tabs">
		<li class = "active"><a href="#languagetab_en" data-toggle="tab">English</a></li>
		<li><a href="#languagetab_fr" data-toggle="tab">Français</a></li>
		<li><a href="#languagetab_ar" data-toggle="tab">العربية</a></li>
		<!-- <li><a href="#languagetab_so" data-toggle="tab">سۆرانی</a></li> -->
		<li><a href="#languagetab_fa" data-toggle="tab">فارسی</a></li> 
		<li><a href="#languagetab_som" data-toggle="tab">Somali</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in active" id="languagetab_en">
			{if {$currentOrg['id']}==14}
				<h3>Information about new privacy policies</h3><br />
				<p>OA/CESRT wishes to reassure you that we are protecting your private information, with reference to the new privacy policies that applies to EU/EES countries.</p>
				<p>For beneficiaries that receive aid from the OA/CESRT Distribution Centre, the following information is retained:</p>
				<ul>
				<li>Name</li>
				<li>Age (date of birth)</li>
				<li>Nationality (in some cases)</li>
				<li>Address (usually this is the camp name)</li>
				<li>Telephone number (in some cases)</li>
				<li>Gender</li>
				</ul>
				<p>You do on your own provide OA/CESRT representatives with your personal information at the OA/CESRT Distribution Centre. We need this information to ensure that you are part of our distribution services provided by the organisation. This is important to schedule appointments and to help ensure we have enough equipment for our beneficiaries.</p>
				<p>So that the OA/CESRT Distribution Centre runs efficiently, please agree that we can continue to process and retain this information about you. OA/CESRT does not share your personal information with other parties.</p>
				<p>To access the mentioned services provided by {$currentOrg['label']}, you must agree that we can continue to process and retain this information about you.</p>
				<p>I, <strong>{$data['firstname']} {$data['lastname']}</strong>, agree that my personal information is stored and processed as described in the Privacy Policy of OA/CESRT.
				<p>I also agree that my family ́s personal information is stored and processed as described above.</p>
			{else}
				<h3>Information about new privacy policies</h3><br />
				<p>{$currentOrg['label']} wish to reassert you that we are protecting your private information, with reference to the new privacy policies that applies to EU/EES countries.</p>
				<p>For refugees that receive aid/assistance/ from {$currentOrg['label']} (clothes/shoes/food/other assistance or activities), the following information is retained:</p>
				<ul>
				<li>Name</li>
				<li>Age (date of birth)</li>
				<li>Nationality (in some cases)</li>
				<li>Address (e.g. container number in the refugee camp)</li>
				<li>Telephone number (in some cases)</li>
				<li>Gender</li>
				</ul>
				<p>You do on your own provide us with your personal information in conversation with representatives from {$currentOrg['label']}. We need this information to ensure that you are part of our distribution and/or other services provided by the organisation, and to make sure that we have enough equipment for our beneficiaries.</p>
				<p>{$currentOrg['label']} does not share your personal information with other parties.</p>
				<p>To access the mentioned services provided by {$currentOrg['label']}, you must agree that we can continue to process and retain this information about you.</p>
				<p>I, <strong>{$data['firstname']} {$data['lastname']}</strong>, agree that my personal information is stored and processed as described in the Privacy Policy of {$currentOrg['label']}.</p><p>I also agree that my family ́s personal information is stored and processed as described above.</p>
			{/if}
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
		<div class="tab-pane fade" id="languagetab_ar" style="text-align:right" dir="rtl">
			<h3>
				معلومات حول سياسات الخصوصية الجديدة
			</h3><br />
			<p>
				ترغب منظمة
				{$currentOrg['label']}
				 في إعادة تأكيد أننا نحمي معلوماتك الخاصة ، مع الإشارة إلى سياسات الخصوصية الجديدة التي تنطبق على دول الاتحاد الأوروبي / المنطقة الاقتصادية الأوروبية 
				 .<br />
 				للاجئين الذين يتلقون مساعدات / مساعدة / من منظمة 
				{$currentOrg['label']}
				  (ملابس / أحذية / طعام / مساعدات أو أنشطة أخرى) ، يتم الاحتفاظ بالمعلومات التالية
				:
			</p>
			<ul>
				<li>
					اسم
				</li>
				<li>
					العمر ، (تاريخ الميلاد)
				</li>
				<li>
					الجنسية (في بعض الحالات)
				</li>
				<li>
					العنوان (مثل رقم الكرفانة في مخيم اللاجئين)
				</li>
				<li>
					رقم الهاتف (في بعض الحالات)
				</li>
				<li>
					جنس
				</li>
			</ul>
			<p>
 				أنت وحدك تزودنا بمعلوماتك الشخصية في محادثة مع ممثلين من منظمة
				{$currentOrg['label']}. <br />
				نحتاج إلى هذه المعلومات للتأكد من أنك جزء من خدمات التوزيع و / أو الخدمات الأخرى التي تقدمها المؤسسة ، وللتأكد من أن لدينا ما يكفي من المعدات للمستفيدين.<br />
				   لا يقوم منظمة 
				   {$currentOrg['label']}
				     بمشاركة معلوماتك الشخصية مع أطراف أخرى.<br /> 
 				للوصول إلى الخدمات المذكورة التي تقدمها ، يجب أن توافق على أنه يمكننا مواصلة معالجة هذه المعلومات الخاصة بك والاحتفاظ بها .<br />
 				أوافق على أن معلوماتي الشخصية يتم تخزينها ومعالجتها كما هو موضح في سياسة الخصوصية الخاصة {$currentOrg['label']}. <br />
 				أوافق أيضًا على أن المعلومات الشخصية لعائلتي يتم تخزينها ومعالجتها كما هو موضح أعلاه.
			</p>
		</div>
		<div class="tab-pane fade" id="languagetab_so">
		</div>
		<div class="tab-pane fade" id="languagetab_fa" style="text-align:right" dir="rtl">
			<h3>
				اطلاعات جدید راجع به خط مش (سیاست)های حفظ حریم شخصی	
			</h3><br />
			<p>
				سازمان
			 	{$currentOrg['label']}
				امیدوار است که به شما اطمینان دهد، با در نظر گرفتن سیاست های جدید حفظ حریم شخصی که در کشور های اتحادیه اروپا و EES تطبیق میشود از اطلاعات شخصی شما محافظت نماییم .<br />

				برای مهاجرانی که کمک از سازمان 
				{$currentOrg['label']}
				 دریافت میکنند،مثل(لباس،کفش،غذا یا کمک های دیگر و  فعالیت های دیگر ، اطلاعات ذیل ثبت میگردد
				:
			</p>
			<ul>
				<li>
					اسم
				</li>
				<li>
					سن(تاریخ تولد)
				</li>
				<li>
					ملیت(در صورت نیاز)
				</li>
				<li>
					آدرس (شماره کانکس در کمپ مهاجران)
				</li>
				<li>
					شماره تلفن(در صورت نیاز)
				</li>
				<li>
					جنسیت
				</li>
			</ul>
			<p>


				شما خودتان این اطلاعات شخصی را  طی گفتگو با نماینده ما در سازمان  
				{$currentOrg['label']}
				  در میان بگذارید. ما به این اطلاعات جهت اطمینان بخاطر آمار توزیع و سایر خدماتی که از طرف سازمان فراهم میگردد لازم داریم، و این ما را مطمئن میسازد که چقدر تجهیزات برای بهره برداران داریم. 
				سازمان 
				{$currentOrg['label']}
				اطلاعات شخصی شما را در اختیار ارگان های دیگر  نمی گذارد.<br />
				برای دسترسی به خدمات ذکر شده که توسط سازمان 
				{$currentOrg['label']}
				 فراهم میگردد، شما باید موافقت نمایید که اطلاعات شخصی شما برای ادامه ی پروسه ثبت میگردد. <br />

				من موافق هستم که اطلاعات شخصی ام طوری که ذکر شد برای پروسه حفظ حریم خصوصی در سازمان 
				{$currentOrg['label']}
				 ثبت گردد.<br />
				من همچنان موافقم که اطلاعات شخصی خانواده ام طبق پروسه شرح داده شده فوق ثبت گردد.
 			</p>
		</div>
		<div class="tab-pane fade" id="languagetab_som">
			<h3>Warbixin ku saabsan Qaanunka xog qarinteena cusub.</h3><br />
			<p>‘{$currentOrg['label']}’ Waxay jeclaan laheyd inaan ku damaanaad qaadno inaan ilaalino maclaamaadkaaga shaqsiga ah anagoo ku dhaqmeyna xeerarka cusub ee dhowrista xogta shaqsiyaad ee wadamada Miwodaga Yurub (EU) iyo Wadamada Dhaqaale Wadaaga Yurub (EES).</p>
			<p>Ka faa’ideysteyaasha gargaaka ka helaya Xarunta Qeybinta Ee ‘{$currentOrg['label']}’, Waxaa looga baahan yahay waxyaabahan soo socda:</p>
			<ul>
			<li>Magaca</li>
			<li>Da’da (taariikh dhalashada)</li>
			<li>Wadanka dhalashada (Mararka qaar)</li>
			<li>Adreyska aad degan tahay: (Kaamka magaciisa)</li>
			<li>Telefoon number (mararka qaar)</li>
			<li>Lab/ Dhedig</li>
			</ul>
			<p>Adiga ayaa lagaa rabaa inaad macluumaadkaaga shaqsiga ah u soo sheegto wakiilada ‘{$currentOrg['label']}’. Waxan ugu baahanahay macluumaadkaan si aan u xaqiijino inaad qeyb ka tahay barnaamijkeena wax qeybinta ee ah hey’adu sameyso, sidoo kale waxay muhuum u yihiin si loo qorsheeyo balamaha si aan u xaqiijino inaan u heyno ka faa’ideysteyaasheena alaab ku filan.</p>
			<p>‘{$currentOrg['label']}‘ lama wadaageyso macluumaadkaaga shaqsiga ah qeybaha kale.</p>
			<p>Si ay xarumaha qeybinta ‘{$currentOrg['label']}’ ugu shaqeyaan si sax ah, fadlan aqbal inaan qorno oo aana keydino macluumaadkaaga shaqsiga ah.</p>
			<p>Anigoo ah, <strong>{$data['firstname']} {$data['lastname']}</strong>, Waxaan aqbalayaa in macluumaadkeyga shaqsiga ah lagu qoro laguna keydiyo sida ay qabaan Qawaaniinta Xog Dhowristaf ‘{$currentOrg['label']}’.</p>
			<p>Sidoo kale waxaan aqbalayaa in macluumaadka shaqsiga ah ee qoyskeyga lagu qoro looguna keediyo sida kor ku xusan.</p>
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
