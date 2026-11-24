<div class="col-md-8 col-sm-12 col-md-offset-2 signature-text">
  {if {$currentOrg['id']}==3}
    <div id="IHA">
      <ul class="nav nav-tabs">
        <li class = "active"><a href="#languagetab_en" data-toggle="tab">English</a></li>
        <li><a href="#languagetab_ar" data-toggle="tab">العربية</a></li>
        <li><a href="#languagetab_fa" data-toggle="tab">فارسی</a></li>
        <li><a href="#languagetab_km" data-toggle="tab">Kurmancî</a></li>
        <li><a href="#languagetab_tu" data-toggle="tab">TÜRKÇE</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade in active" id="languagetab_en">
          <center><h3>Data Consent Form</h3></center><br />
          <p>I understand that the <i>InterEuropean Human Aid Association - IHA</i> (hereafter Organisation) collects, stores and processes personal data from its service-users in order to provide humanitarian assistance and support. I hereby provide my consent for the Organisation to collect, store and process my personal data in accordance with the European General Data Protection Regulation (GDPR) and humanitarian principles.</p>
          <p>I understand that my personal data may include but is not limited to: <i>name, contact information, date of birth, gender, nationality, and any other information</i> necessary for the purposes of providing assistance and support.</p>
          <p>I understand that my personal data will be processed securely and confidentially by the Organisation and will only be shared with authorised personnel who need access to it for the purpose of providing assistance and support.</p>
          <p>I understand that I have the right to access and request correction or deletion of my personal data at any time by contacting the Organisation.</p>
          <p>I understand that my personal data may be used for statistical analysis and reporting purposes, but will <i>always be anonymized to protect my identity</i>.</p>
          <p>I understand that my consent is voluntary and that I have the right to withdraw my consent at any time by contacting the Organisation.</p>
          <p><strong>My signature below indicates my consent for InterEuropean Human Aid Association - IHA to collect, store, and process my personal data, as well as my child's data if applicable, for the purposes described above.</strong></p>
          <ul>
            <li>Name: <strong>{$data['firstname']} {$data['lastname']}</strong></li>
            <li>Date of Birth: <strong>{$data['date_of_birth']}</strong></li>
            {if {$data['members']}}<li>Children first and last names:
              {foreach from=$data['members'] item=member name=memberLoop}
                <strong>{$member['firstname']} {$member['lastname']}</strong>{if !$smarty.foreach.memberLoop.last}, {/if}
              {/foreach}
            </li>{/if}
          </ul>
        </div>
        <div class="tab-pane fade" id="languagetab_ar" dir="rtl">
          <center><h3>نموذج موافقة البيانات</h3></center><br>
                <p>
أفهم أن جمعية المساعدات الإنسانية الأوروبية (المشار إليها فيما بعد باسم المنظمة) تجمع وتخزن وتعالج البيانات الشخصية من مستخدمي خدماتها من أجل تقديم المساعدة الإنسانية والدعم. بموجب هذا أوافق على أن تقوم المنظمة بجمع وتخزين ومعالجة بياناتي الشخصية وفقًا للوائح حماية البيانات العامة الأوروبية (GDPR) والمبادئ الإنسانية.
                </p>
                <p>
أفهم أن بياناتي الشخصية قد تتضمن على سبيل المثال لا الحصر: الاسم ومعلومات الاتصال وتاريخ الميلاد والجنس والجنسية وأي معلومات أخرى ضرورية لأغراض تقديم المساعدة والدعم.
                </p>
                <p>
                    أفهم أن المنظمة ستعالج بياناتي الشخصية بشكل آمن وسري ولن تتم مشاركتها إلا مع الموظفين المصرح لهم الذين يحتاجون إلى الوصول إليها لغرض تقديم المساعدة والدعم.
                </p>
                <p>
                    أفهم أن لدي الحق في الوصول إلى بياناتي الشخصية وطلب تصحيحها أو حذفها في أي وقت عن طريق الاتصال بالمنظمة.
                </p>
                <p>
                    أفهم أنه قد يتم استخدام بياناتي الشخصية لأغراض التحليل الإحصائي وإعداد التقارير، ولكن سيتم دائمًا إخفاء هويتي لحماية هويتي.
                </p>
                <p>
                    أفهم أن موافقتي طوعية وأن لدي الحق في سحب موافقتي في أي وقت عن طريق الاتصال بالمنظمة
                </p>
                <p>.</p>
        <center>* * *</center>
                <p><b>
                    يشير توقيعي أدناه إلى موافقتي على قيام جمعية المساعدات الإنسانية الأوروبية - IHA بجمع وتخزين ومعالجة بياناتي الشخصية، بالإضافة إلى بيانات طفلي إذا لزم الأمر، للأغراض الموضحة أعلاه.</b>
                </p>
        <p style="line-height:0.8cm">
            <span>الاسم الأول والأخير:</spar>
            <span><strong>{$data['firstname']} {$data['lastname']}</strong></span><br>
            <span>تاريخ الميلاد:</span>
            <span>{$data['date_of_birth']}</span><br>
            <span>أسماء الأطفال الأولى والأخيرة:</span><br>
            <span>
          {if {$data['members']}}
          {foreach from=$data['members'] item=member name=memberLoop}
            <strong>{$member['firstname']} {$member['lastname']}</strong>{if !$smarty.foreach.memberLoop.last}, {/if}
          {/foreach}
          {/if}
          </span><br>
        </p>
        </div>
        <div class="tab-pane fade" id="languagetab_fa" dir="rtl">
        <center><h3>فرم رضایت داده ها</h3></center><br>
        <p>من درک می کنم که
        <i>انجمن کمک های انسانی بین اروپایی <span>- IHA</span></i>

        <span>(که از این پس سازمان نامیده می‌شود)</span>
    داده های شخصی کاربران خدمات خود را جمع آوری، ذخیره و پردازش می کند تا کمک و پشتیبانی بشردوستانه ارائه دهد. بدینوسیله رضایت خود را برای سازمان برای جمع‌آوری، ذخیره و پردازش داده‌های شخصی من مطابق با مقررات عمومی حفاظت از داده‌های اروپا (GDPR) و اصول بشردوستانه ارائه می‌دهم..
        </p>
        <p>
            من درک می کنم که داده های شخصی من به طور ایمن و محرمانه توسط سازمان پردازش می شود و فقط با پرسنل مجاز که به منظور ارائه کمک و پشتیبانی نیاز به دسترسی به آن دارند، به اشتراک گذاشته خواهد شد.
        </p>
        <p>
            من می دانم که حق دسترسی و درخواست اصلاح یا حذف اطلاعات شخصی خود را در هر زمان از طریق تماس با سازمان دارم.
        </p>
        <p>
            من می دانم که داده های شخصی من ممکن است برای اهداف تجزیه و تحلیل آماری و گزارش استفاده شود، اما همیشه برای محافظت از هویت من ناشناس باقی می ماند.
        </p>
        <p>
            من می دانم که رضایت من داوطلبانه است و این حق را دارم که در هر زمان با تماس با سازمان، رضایت خود را پس بگیرم.
        </p>
        <center>* * *</center>
          <p>امضای من در زیر نشان‌دهنده رضایت من برای انجمن کمک‌های انسانی بین‌اروپایی
            <b>- (IHA)</b>
        برای جمع‌آوری، ذخیره و پردازش داده‌های شخصی من و همچنین داده‌های فرزندم در صورت وجود، برای اهدافی است که در بالا توضیح داده شد
        </p>
        <p style="line-height:0.7cm">
            <span>نام و نام خانوادگی:</span><br>
            <span>{$data['firstname']} {$data['lastname']}</span><br>
            <span>تاریخ تولد:</span><br>
            <span>{$data['date_of_birth']}</span><br>
            <span>نام و نام خانوادگی فرزندان:</span><br>
            <span>
          {if {$data['members']}}
          {foreach from=$data['members'] item=member name=memberLoop}
            <strong>{$member['firstname']} {$member['lastname']}</strong>{if !$smarty.foreach.memberLoop.last}, {/if}
          {/foreach}
          {/if}
          </span><br>
        </p>
        </div>
        <div class="tab-pane fade" id="languagetab_km">
          <center><h3>Forma razîbûna Datayan</h3></center><br />
          <p>Ez fam dikim ku Komeleya Alîkariya Mirovî ya InterEwropî - IHA (li vir Rêxistin) daneyên kesane yên bikarhênerên karûbarê xwe berhev dike, hilîne û pêvajoyê dike da ku arîkarî û piştgirîya mirovahî peyda bike. Ez bi vê yekê razîbûna xwe pêşkêşî Rêxistinê dikim ku daneyên min ên kesane li gorî Rêziknameya Parastina Daneyên Giştî ya Ewropî (GDPR) û prensîbên mirovahî berhev bike, hilîne û pêvajoyê bike.</p>
          <p>Ez fam dikim ku daneyên min ên kesane dibe ku di nav wan de bin, lê ne bi sînorkirî ne: nav, agahdariya têkiliyê, dîroka jidayikbûnê, zayendî, hemwelatî, û her agahdariya din a ku ji bo mebestên arîkarî û piştgirîyê hewce dike.</p>
          <p>Ez fam dikim ku daneyên min ên kesane dê ji hêla Rêxistinê ve bi ewlehî û nepenî werin hilanîn û dê tenê bi personelên rayedar ên ku ji bo peydakirina arîkarî û piştgirîyê hewceyê gihîştina wê ne re were parve kirin.</p>
          <p>Ez fêm dikim ku mafê min heye ku bi riya têkiliyê bi Rêxistinê re di her kêliyê de bigihêjim û daxwaza rastkirin an jêbirina daneyên xwe yên kesane bikim.</p>
          <p>Ez fam dikim ku daneyên min ên kesane dibe ku ji bo analîzên statîstîkî û mebestên raporkirinê werin bikar anîn, lê ji bo parastina nasnameya min dê her gav bênav bibin.</p>
          <p>Ez fam dikim ku razîbûna min dilxwazî ​​ye û mafê min heye ku ez razîbûna xwe di her kêliyê de vekişînim û bi Rêxistinê re têkilî daynin.</p>
          <p><strong>Îmzeya min a li jêr razîbûna min ji bo Komela Alîkariya Mirovî ya InterEwropî - IHA nîşan dide ku daneyên min ên kesane berhev bike, hilîne û bixebite, her weha heke hebe, daneyên zarokê min, ji bo mebestên ku li jor hatine destnîşan kirin.</strong></p>
          <ul>
            <li>Nav û Paşnav: <strong>{$data['firstname']} {$data['lastname']}</strong></li>
            <li>Roja bûyînê: <strong>{$data['date_of_birth']}</strong></li>
            {if {$data['members']}}<li>Nav û paşnavên zarokan:
              {foreach from=$data['members'] item=member name=memberLoop}
                <strong>{$member['firstname']} {$member['lastname']}</strong>{if !$smarty.foreach.memberLoop.last}, {/if}
              {/foreach}
            </li>{/if}
          </ul>
        </div>
        <div class="tab-pane fade" id="languagetab_tu">
          <center><h3>Veri Onay Formu</h3></center><br />
          <p>InterEuropean Human Aid Association - IHA'nın (bundan böyle "Kurum" olarak anılacaktır) insani yardım ve destek sağlamak amacıyla hizmet kullanıcılarından kişisel veri topladığını, depoladığını ve işlediğini anlıyorum. Kişisel verilerimin Avrupa Genel Veri Koruma Yönetmeliği (GDPR) ve insani yardım ilkelerine uygun olarak Kurum tarafından toplanmasına, depolanmasına ve işlenmesine burada onay veriyorum.</p>
          <p>Kişisel verilerimin; ad, iletişim bilgileri, doğum tarihi, cinsiyet, uyruk ve destek ve yardım sağlamak amacıyla gerekli diğer bilgileri içerebileceğini anlıyorum.</p>
          <p>Kişisel verilerimin Kurum tarafından güvenli ve gizli bir şekilde işleneceğini ve yalnızca yardım ve destek sağlama amacıyla erişim ihtiyacı duyan yetkili personelle paylaşılacağını anlıyorum.</p>
          <p>Kişisel verilerime erişme, düzeltme veya silinmesini talep etme hakkımın olduğunu ve bunu yapmak için Kurum ile iletişime geçebileceğimi anlıyorum.</p>
          <p>Kişisel verilerimin istatistiksel analiz ve raporlama amaçlarıyla kullanılabileceğini, ancak kimliğimi korumak için her zaman anonim hale getirileceğini anlıyorum.</p>
          <p>Onayımın gönüllü olduğunu ve istediğim zaman Kurum ile iletişime geçerek onayımı geri çekme hakkına sahip olduğumu anlıyorum.</p>
          <p><strong>Aşağıdaki imzam, InterEuropean Human Aid Association - IHA'nın yukarıda açıklanan amaçlar doğrultusunda kişisel verilerimi ve varsa çocuğuma ait verileri toplamasına, depolamasına ve işlemesine onay verdiğimi belirtir.</strong></p>
          <ul>
            <li>Ad Soyad: <strong>{$data['firstname']} {$data['lastname']}</strong></li>
            <li>Doğum Tarihi: <strong>{$data['date_of_birth']}</strong></li>
            {if {$data['members']}}<li>Çocukların Adı Soyadı:
              {foreach from=$data['members'] item=member name=memberLoop}
                <strong>{$member['firstname']} {$member['lastname']}</strong>{if !$smarty.foreach.memberLoop.last}, {/if}
              {/foreach}
            </li>{/if}
          </ul>
        </div>
      </div>
    </div>
  {else}
    {if {$currentOrg['id']}==17}
      <ul class="nav nav-tabs">
        <li class = "active"><a href="#languagetab_en" data-toggle="tab">English</a></li>
        <li><a href="#languagetab_it" data-toggle="tab">Italiano</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade in active" id="languagetab_en">
          <h3>Information about privacy policies</h3><br />
          <p>{$currentOrg['label']} wish to reassert you that we are protecting your private information, with reference to the new privacy policies that applies to EU/EES countries.</p>
          <p>For beneficiaries using Darbazar services (supply of clothing/shoes or other services), the following information is retained:</p>
          <ul>
            <li>Name</li>
            <li>Age (date of birth)</li>
            <li>Nationality (if provided)</li>
            <li>Telephone number (if provided)</li>
            <li>Email address (if provided)</li>
            <li>Gender</li>
          </ul>
          <p>You do on your own provide us with your personal information in conversation with representatives from {$currentOrg['label']}. We need this information to ensure that you are part of the services provided by the organisation, and to make sure that we have enough equipment for our beneficiaries.</p>
          <p>{$currentOrg['label']} does not share your personal information with other parties.</p>
          <p>To access the mentioned services provided by {$currentOrg['label']}, you must agree that we can continue to process and retain this information about you.</p>
          <p>I, <strong>{$data['firstname']} {$data['lastname']}</strong>, agree that my personal information is stored and processed as described in the Privacy Policy of {$currentOrg['label']}.</p>
          <p>I also agree that my family ́s personal information is stored and processed as described above.</p>
        </div>

        <div class="tab-pane fade" id="languagetab_it">
          <h3>Informazioni sulla privacy policy</h3><br />
          <p>{$currentOrg['label']} desidera informarti che stiamo proteggendo le tue informazioni private, con riferimento alle nuove politiche sulla privacy che si applicano ai paesi dell'UE / SEE.</p>
          <p>Per i beneficiari che usufruiscono dei servizi di Darbazar (fornitura vestiti / scarpe / altro), vengono conservate le seguenti informazioni:</p>
          <ul>
            <li>Nome</li>
            <li>Età (data di nascita)</li>
            <li>Nazionalità (se comunicata)</li>
            <li>Numero di telefono (se comunicato)</li>
            <li>Indirizzo email (se comunicato)</li>
            <li>Genere</li>
          </ul>
          <p>Queste informazioni vengono fornite durante un colloquio con i rappresentanti di Darbazar. Abbiamo bisogno di queste informazioni per assicurarci che tu possa usufruire dei servizi forniti dall'organizzazione e per assicurarci di avere attrezzature sufficienti per i nostri beneficiari.</p>
          <p>{$currentOrg['label']} non condivide le tue informazioni personali con altre parti.</p>
          <p>Per accedere ai servizi menzionati forniti da {$currentOrg['label']}, devi accettare che possiamo elaborare e conservare queste informazioni su di te.</p>
          <p>Io sottoscritto, <strong>{$data['firstname']} {$data['lastname']}</strong>, accetto che le mie informazioni personali siano archiviate ed elaborate come descritto nella Privacy Policy di {$currentOrg['label']}.</p>
          <p>Accetto inoltre che le informazioni personali della mia famiglia (ove fornite) siano archiviate ed elaborate come descritto sopra.</p>
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
            <h3>Information about privacy policies</h3><br />
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
  {/if}
  {if {$data['approvalsigned']}}<p>Signed on: {$data['date_of_signature']}</p>{/if}
  <div class="fc"></div>
  <div id="sig" ></div>
  <p style="clear: both;">
    <button id="clear">Clear</button>
  </p>
  <textarea name="signaturefield" id="signaturefield" class="hidden">{$data[$element['field']]}</textarea>
  </div>
